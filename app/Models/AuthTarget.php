<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Slides\Saml2\Models\Tenant as Saml2Tenant;

class AuthTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'oauth_client_id',
        'oauth_client_secret',
        'oauth_redirect_uri',
        'oauth_mylogin_url',
        'saml2_tenant_id',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function saml2Tenant(): BelongsTo
    {
        return $this->belongsTo(Saml2Tenant::class);
    }

    public function hasOauth(): bool
    {
        return ! empty($this->oauth_client_id)
            && ! empty($this->oauth_client_secret)
            && ! empty($this->oauth_redirect_uri)
            && ! empty($this->oauth_mylogin_url);
    }

    public function hasSaml(): bool
    {
        return ! is_null($this->saml2_tenant_id);
    }
}
