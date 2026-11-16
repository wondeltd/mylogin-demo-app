<?php

namespace App\Listeners;

use App\Enums\ClaimTypes;
use App\Enums\SSOProtocol;
use App\Models\AuthTarget;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Slides\Saml2\Events\SignedIn;

class LogInSamlUser
{
    public function handle(SignedIn $event): void
    {
        $samlUser = $event->getSaml2User();
        $samlTenant = $samlUser->getTenant();
        $authTarget = AuthTarget::where('saml2_tenant_id', $samlTenant->getKey())->first();

        $user = User::updateOrCreate([
            'mylogin_id'     => $samlUser->getAttribute(ClaimTypes::MyLoginID->value)[0],
            'auth_target_id' => $authTarget->getKey(),
        ], [
            'name'                => "{$samlUser->getAttribute(ClaimTypes::FirstName->value)[0]} {$samlUser->getAttribute(ClaimTypes::LastName->value)[0]}",
            'email'               => $samlUser->getAttribute(ClaimTypes::EmailAddress->value)[0],
            'password'            => Hash::make(Str::random()),
            'last_saml_assertion' => $samlUser->getAttributes(),
        ]);

        auth()->login($user);

        session()->replace(['last_login_protocol' => SSOProtocol::SAML->value]);
    }
}
