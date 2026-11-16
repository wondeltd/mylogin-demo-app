<?php

use App\Models\AuthTarget;
use App\Models\User;
use Illuminate\Support\Facades\Http;

function startOAuthFlow(AuthTarget $target): string
{
    $location = test()->get("/{$target->slug}/redirect")->headers->get('Location');
    parse_str(parse_url($location, PHP_URL_QUERY), $query);

    return $query['state'];
}

function fakeMyLoginUser(): array
{
    return ['https://mylogin.test/api/user' => Http::response(['data' => [
        'id'         => 'ML-42',
        'first_name' => 'Ada',
        'last_name'  => 'Lovelace',
        'email'      => 'ada@example.com',
    ]])];
}

beforeEach(fn () => Http::preventStrayRequests());

it('logs in the MyLogin user and lands on the dashboard', function () {
    $target = AuthTarget::factory()->create();
    Http::fake([
        'https://mylogin.test/oauth/token' => Http::response(['access_token' => 'access-123', 'refresh_token' => 'refresh-456']),
        ...fakeMyLoginUser(),
    ]);
    $state = startOAuthFlow($target);

    $this->get("/{$target->slug}/callback?state={$state}&code=the-code")
        ->assertRedirect(route('dashboard'));

    $user = User::where('mylogin_id', 'ML-42')->sole();
    expect($user->auth_target_id)->toBe($target->id);
    $this->assertAuthenticatedAs($user);
    expect(session('last_login_protocol'))->toBe('o_auth');
});

it('restarts the flow when state does not match', function () {
    $target = AuthTarget::factory()->create();
    Http::fake();
    startOAuthFlow($target);

    $this->get("/{$target->slug}/callback?state=forged-state&code=the-code")
        ->assertRedirect(route('redirect', ['auth_target' => $target, 'protocol' => 'o_auth']));

    $this->assertGuest();
    Http::assertNothingSent();
});

it('returns to login when the token exchange fails', function () {
    $target = AuthTarget::factory()->create();
    Http::fake([
        'https://mylogin.test/oauth/token' => Http::response(['access_token' => 'access-123', 'refresh_token' => 'refresh-456'], 400),
        ...fakeMyLoginUser(),
    ]);
    $state = startOAuthFlow($target);

    $this->get("/{$target->slug}/callback?state={$state}&code=the-code")
        ->assertRedirect(route('login'));

    $this->assertGuest();
});
