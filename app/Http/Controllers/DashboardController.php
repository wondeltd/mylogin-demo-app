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
            ->get(auth()->user()->authTarget->oauth_mylogin_url.'/api/user');

        if ($response->failed()) {
            logger()->error('MyLogin OAuth API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'userId' => auth()->id(),
            ]);

            abort(401, 'Failed to fetch user details from MyLogin API');
        }

        $data = $response->json();

        if (! isset($data['data'])) {
            logger()->error('MyLogin OAuth API returned invalid response structure', [
                'response' => $data,
                'userId' => auth()->id(),
            ]);

            abort(500, 'Invalid response structure from MyLogin API');
        }

        return [
            'name' => "{$data['data']['first_name']} {$data['data']['last_name']}",
            'method' => SSOProtocol::OAuth,
            'content' => $data,
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
