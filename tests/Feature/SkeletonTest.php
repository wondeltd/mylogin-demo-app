<?php

use App\Models\User;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

test('saml2 routes run with a session', function () {
    $route = Route::getRoutes()->getByName('saml.login');

    $middleware = Route::gatherRouteMiddleware($route);

    expect(array_values(array_intersect($middleware, [EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class])))
        ->toBe([EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class]);
});

test('authenticated users visiting login go to the dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get('/login')
        ->assertRedirect('/dashboard');
});

test('guests visiting the dashboard go to /', function () {
    $this->get('/dashboard')->assertRedirect('/');
});
