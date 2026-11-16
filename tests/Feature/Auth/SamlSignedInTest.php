<?php

use App\Enums\ClaimTypes;
use App\Models\AuthTarget;
use App\Models\User;
use Slides\Saml2\Auth;
use Slides\Saml2\Events\SignedIn;
use Slides\Saml2\Saml2User;

it('logs in the asserted user', function () {
    AuthTarget::factory()->withSaml()->create();
    $target = AuthTarget::factory()->withSaml()->create();
    expect($target->saml2Tenant)->not->toBeNull();

    $claims = [
        ClaimTypes::MyLoginID->value    => ['ML-7'],
        ClaimTypes::FirstName->value    => ['Grace'],
        ClaimTypes::LastName->value     => ['Hopper'],
        ClaimTypes::EmailAddress->value => ['grace@example.com'],
    ];
    $samlUser = Mockery::mock(Saml2User::class);
    $samlUser->allows('getTenant')->andReturn($target->saml2Tenant);
    $samlUser->allows('getAttribute')->andReturnUsing(fn (string $name) => $claims[$name] ?? null);
    $samlUser->allows('getAttributes')->andReturn($claims);

    event(new SignedIn($samlUser, Mockery::mock(Auth::class)));

    $user = User::where('mylogin_id', 'ML-7')->sole();
    expect($user->auth_target_id)->toBe($target->id);
    $this->assertAuthenticatedAs($user);
    expect(session('last_login_protocol'))->toBe('saml');
});
