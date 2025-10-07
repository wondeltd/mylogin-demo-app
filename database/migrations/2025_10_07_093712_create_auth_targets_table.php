<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auth_targets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');

            // OAuth fields
            $table->string('oauth_client_id')->nullable();
            $table->string('oauth_client_secret')->nullable();
            $table->string('oauth_redirect_uri')->nullable();
            $table->string('oauth_mylogin_url')->nullable();

            // SAML relationship
            $table->unsignedInteger('saml2_tenant_id')->nullable();
            $table->foreign('saml2_tenant_id')->references('id')->on('saml2_tenants')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_targets');
    }
};
