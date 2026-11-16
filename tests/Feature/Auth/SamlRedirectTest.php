<?php

use App\Models\AuthTarget;

it('sends saml requests to the tenant login route', function () {
    $target = AuthTarget::factory()->withSaml()->create();

    $this->get("/{$target->slug}/redirect?protocol=saml")
        ->assertRedirect(route('saml.login', [
            'uuid'     => $target->saml2Tenant->uuid,
            'returnTo' => route('dashboard'),
        ]));
});
