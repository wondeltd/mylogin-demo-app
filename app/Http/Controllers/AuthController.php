<?php

namespace App\Http\Controllers;

use App\Enums\SSOProtocol;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Slides\Saml2\Models\Tenant;

class AuthController extends Controller
{
    public function loginPage(): View
    {
        $isLogoutRedirect = request()->has('is_logout_redirect');

        return view('auth.login')->with(['isLogoutRedirect' => $isLogoutRedirect]);
    }

    public function redirect(Request $request): RedirectResponse
    {
        if ($request->get('protocol') === SSOProtocol::SAML->value) {
            return to_route('saml.login', ['uuid' => Tenant::firstOrFail()->uuid, 'returnTo' => route('dashboard')]);
        }

        $query = http_build_query([
            'client_id' => config('services.mylogin.client_id'),
            'redirect_uri' => config('services.mylogin.redirect_uri'),
            'response_type' => 'code',
        ]);

        $url = config('services.mylogin.url').'/oauth/authorize?'.$query;

        return redirect($url);
    }

    public function callback(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode(config('services.mylogin.client_id').':'.config('services.mylogin.client_secret')),
        ])
            ->post(config('services.mylogin.url').'/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'redirect_uri' => config('services.mylogin.redirect_uri'),
            ]);

        $details = $response->json();

        if (! $response->ok() || empty($details['access_token'])) {
            return to_route('login');
        }

        $userResponse = Http::withHeaders([
            'Authorization' => 'Bearer '.$details['access_token'],
        ])
            ->get(config('services.mylogin.url').'/api/user')
            ->json();

        if ($userResponse) {

            $user = User::firstOrCreate([
                'mylogin_id' => $userResponse['data']['id'],
            ], [
                'name' => $userResponse['data']['first_name'].' '.$userResponse['data']['last_name'],
                'email' => $userResponse['data']['email'],
                'password' => Hash::make(Str::random(48)),
            ]);

            $user->update([
                'access_token' => $details['access_token'],
                'refresh_token' => $details['refresh_token'],
            ]);

            auth()->login($user);
            session()->replace(['last_login_protocol' => SSOProtocol::OAuth->value]);
        }

        return to_route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        if ($this->isSamlSession()) {
            return to_route('saml.logout', Tenant::firstOrFail()->uuid);
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->away(config('services.mylogin.url').'/oauth/logout?client_id='.config('services.mylogin.client_id'));
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
