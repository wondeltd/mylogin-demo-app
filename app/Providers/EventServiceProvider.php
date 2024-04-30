<?php

namespace App\Providers;

use App\Enums\ClaimTypes;
use App\Enums\SSOProtocol;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Slides\Saml2\Events\SignedIn;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(SignedIn::class, function (SignedIn $event) {
            $samlUser = $event->getSaml2User();

            $user = User::updateOrCreate([
                'mylogin_id' => $samlUser->getAttribute(ClaimTypes::MyLoginID->value)[0],
            ], [
                'name' => "{$samlUser->getAttribute(ClaimTypes::FirstName->value)[0]} {$samlUser->getAttribute(ClaimTypes::LastName->value)[0]}",
                'email' => $samlUser->getAttribute(ClaimTypes::EmailAddress->value)[0],
                'password' => Hash::make(Str::random()),
                'last_saml_assertion' => $samlUser->getAttributes(),
            ]);

            auth()->login($user);

            session()->replace(['last_login_protocol' => SSOProtocol::SAML->value]);
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
