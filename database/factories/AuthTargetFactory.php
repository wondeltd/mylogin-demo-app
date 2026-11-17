<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Slides\Saml2\Models\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuthTarget>
 */
class AuthTargetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug'                => fake()->unique()->slug(2),
            'name'                => fake()->company(),
            'oauth_client_id'     => (string) Str::uuid(),
            'oauth_client_secret' => Str::random(40),
            'oauth_redirect_uri'  => 'https://demo-app.test/callback',
            'oauth_mylogin_url'   => 'https://mylogin.test',
        ];
    }

    public function withSaml(): static
    {
        return $this->state(fn () => [
            'saml2_tenant_id' => Tenant::create([
                'uuid'           => (string) Str::uuid(),
                'key'            => Str::random(8),
                'idp_entity_id'  => 'https://mylogin.test/saml/metadata',
                'idp_login_url'  => 'https://mylogin.test/saml/login',
                'idp_logout_url' => 'https://mylogin.test/saml/logout',
                'idp_x509_cert'  => 'cert',
                'metadata'       => [],
            ])->getKey(),
        ]);
    }
}
