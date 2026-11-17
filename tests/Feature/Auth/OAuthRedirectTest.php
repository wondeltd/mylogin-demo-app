<?php

use App\Models\AuthTarget;

it('redirects to MyLogin authorize with a stored state', function () {
    $target = AuthTarget::factory()->create();

    $response = $this->get("/{$target->slug}/redirect");

    $location = $response->headers->get('Location');
    expect($location)->toStartWith('https://mylogin.test/oauth/authorize?');

    parse_str(parse_url($location, PHP_URL_QUERY), $query);
    expect($query)->toHaveKey('state')
        ->and($query['state'])->toHaveLength(40)
        ->and($query['client_id'])->toBe($target->oauth_client_id)
        ->and($query['redirect_uri'])->toBe($target->oauth_redirect_uri);

    $response->assertSessionHas('oauth_state', $query['state']);
});
