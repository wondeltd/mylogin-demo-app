<?php

namespace App\Http\Controllers;

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
        return view('auth.login');
    }

    public function redirect()
    {

        $query = http_build_query([
            'client_id' => config('services.mylogin.client_id'),
            'redirect_uri' => config('services.mylogin.redirect_uri'),
            'response_type' => 'code',
        ]);

        $url = config('services.mylogin.url') . "/oauth/authorize?" . $query;

        return redirect($url);
    }

    public function callback(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(config('services.mylogin.client_id') . ':' . config('services.mylogin.client_secret'))
        ])
        ->post(config('services.mylogin.url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'redirect_uri' => config('services.mylogin.redirect_uri')
        ]);

        $details = $response->json();

        if (! $response->ok() || empty($details['access_token'])) {
            return to_route("login");
        }

        $userResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $details['access_token']
        ])
            ->get(config('services.mylogin.url') . '/api/user')
        ->json();

        if ($userResponse) {
            $user = User::where('email', $userResponse['data']['email'])->first();

            if (! $user) {
                $user = User::create([
                    'name' => $userResponse['data']['first_name'] . ' ' . $userResponse['data']['last_name'],
                    'email' => $userResponse['data']['email'],
                    'password' => Hash::make(Str::random(48))
                ]);
            }

            $user->update([
                'access_token' => $details['access_token'],
                'refresh_token' => $details['refresh_token'],
            ]);
            auth()->login($user);
        }

        return to_route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return to_route('login');
    }
}
