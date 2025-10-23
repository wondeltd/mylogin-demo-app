<?php

namespace App\Http\Controllers;

use App\Enums\SSOProtocol;
use App\Models\AuthTarget;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginPage(): View
    {
        $isLogoutRedirect = request()->has('is_logout_redirect');
        $authTargets = AuthTarget::all();

        return view('auth.login')->with([
            'isLogoutRedirect' => $isLogoutRedirect,
            'authTargets'      => $authTargets,
        ]);
    }

    public function redirect(AuthTarget $authTarget, Request $request): RedirectResponse
    {
        if ($request->get('protocol') === SSOProtocol::SAML->value) {
            if (! $authTarget->hasSaml()) {
                abort(404, 'SAML not configured for this authentication target');
            }

            return to_route('saml.login', ['uuid' => $authTarget->saml2Tenant->uuid, 'returnTo' => route('dashboard')]);
        }

        if (! $authTarget->hasOauth()) {
            abort(404, 'OAuth not configured for this authentication target');
        }

        $query = http_build_query([
            'client_id'     => $authTarget->oauth_client_id,
            'redirect_uri'  => $authTarget->oauth_redirect_uri,
            'response_type' => 'code',
        ]);

        $url = $authTarget->oauth_mylogin_url.'/oauth/authorize?'.$query;

        return redirect($url);
    }

    public function callback(AuthTarget $authTarget, Request $request)
    {
        if (! $authTarget->hasOauth()) {
            abort(404, 'OAuth not configured for this authentication target');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode($authTarget->oauth_client_id.':'.$authTarget->oauth_client_secret),
        ])
            ->post($authTarget->oauth_mylogin_url.'/oauth/token', [
                'grant_type'   => 'authorization_code',
                'code'         => $request->code,
                'redirect_uri' => $authTarget->oauth_redirect_uri,
            ]);

        $details = $response->json();

        if (! $response->ok() || empty($details['access_token'])) {
            logger()->error('MyLogin OAuth token exchange failed', [
                'status'       => $response->status(),
                'body'         => $response->body(),
                'details'      => $details,
                'authTargetId' => $authTarget->getKey(),
            ]);

            return to_route('login');
        }

        $userResponse = Http::withHeaders([
            'Authorization' => 'Bearer '.$details['access_token'],
        ])
            ->get($authTarget->oauth_mylogin_url.'/api/user');

        if ($userResponse->failed()) {
            logger()->error('MyLogin OAuth user fetch failed during callback', [
                'status'       => $userResponse->status(),
                'body'         => $userResponse->body(),
                'authTargetId' => $authTarget->getKey(),
            ]);

            return to_route('login');
        }

        $userData = $userResponse->json();

        if (! isset($userData['data'])) {
            logger()->error('MyLogin OAuth user response missing data key during callback', [
                'response'     => $userData,
                'authTargetId' => $authTarget->getKey(),
            ]);

            return to_route('login');
        }

        $user = User::firstOrCreate([
            'mylogin_id'     => $userData['data']['id'],
            'auth_target_id' => $authTarget->getKey(),
        ], [
            'name'     => $userData['data']['first_name'].' '.$userData['data']['last_name'],
            'email'    => $userData['data']['email'],
            'password' => Hash::make(Str::random(48)),
        ]);

        $user->update([
            'access_token'  => $details['access_token'],
            'refresh_token' => $details['refresh_token'],
        ]);

        auth()->login($user);
        session()->replace([
            'last_login_protocol' => SSOProtocol::OAuth->value,
            'auth_target_id'      => $authTarget->id,
        ]);

        return to_route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $authTarget = Auth::user()->authTarget;
        Auth::guard('web')->logout();

        if ($this->isSamlSession()) {
            return to_route('saml.logout', $authTarget->saml2Tenant->uuid);
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->away($authTarget->oauth_mylogin_url.'/oauth/logout?client_id='.$authTarget->oauth_client_id);
    }

    private function isSamlSession(): bool
    {
        return session()->get('last_login_protocol') === SSOProtocol::SAML->value;
    }

    private function isOauthSession(): bool
    {
        return session()->get('last_login_protocol') === SSOProtocol::OAuth->value;
    }
}
