<?php

namespace App\Http\Controllers;

use App\Enums\SSOProtocol;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function dashboard(): View
    {
        $userData = match (session()->get('last_login_protocol')) {
            SSOProtocol::SAML->value => $this->getSAMLUserDetails(),
            SSOProtocol::OAuth->value => $this->getOAuthUserDetails(),
            default => abort(404),
        };

        return view('dashboard', [
            'userData' => $userData,
        ]);
    }

    public function getOAuthUserDetails(): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.auth()->user()->access_token,
        ])
            ->get(config('services.mylogin.url').'/api/user')
            ->json();

        return [
            'name' => "{$response['data']['first_name']} {$response['data']['last_name']}",
            'method' => SSOProtocol::OAuth,
            'content' => $response,
        ];
    }

    private function getSAMLUserDetails(): array
    {
        $user = auth()->user();

        return [
            'name' => $user->name,
            'method' => SSOProtocol::SAML,
            'content' => $user->last_saml_assertion,
        ];
    }
}
