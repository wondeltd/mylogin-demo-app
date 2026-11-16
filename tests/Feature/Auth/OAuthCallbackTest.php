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
    $target = AuthTarget::factory()->create(['oauth_client_id' => 'client-1', 'oauth_client_secret' => 'secret-1']);
    Http::fake([
        'https://mylogin.test/oauth/token' => Http::response(['access_token' => 'access-123', 'refresh_token' => 'refresh-456']),
        ...fakeMyLoginUser(),
    ]);
    $state = startOAuthFlow($target);

    $this->get("/{$target->slug}/callback?state={$state}&code=the-code")
        ->assertRedirect(route('dashboard'));

    Http::assertSent(fn ($request) => $request->url() === 'https://mylogin.test/oauth/token'
        && $request->hasHeader('Authorization', 'Basic Y2xpZW50LTE6c2VjcmV0LTE=')
        && $request['grant_type'] === 'authorization_code'
        && $request['code'] === 'the-code'
        && $request['redirect_uri'] === 'https://demo-app.test/callback');
    Http::assertSent(fn ($request) => $request->url() === 'https://mylogin.test/api/user'
        && $request->hasHeader('Authorization', 'Bearer access-123'));

    $user = User::where('mylogin_id', 'ML-42')->sole();
    expect($user->auth_target_id)->toBe($target->id)
        ->and($user->name)->toBe('Ada Lovelace')
        ->and($user->email)->toBe('ada@example.com')
        ->and($user->access_token)->toBe('access-123')
        ->and($user->refresh_token)->toBe('refresh-456');
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
