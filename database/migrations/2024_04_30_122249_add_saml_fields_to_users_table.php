<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mylogin_id')->after('id');
            $table->string('email')->nullable()->change();
            $table->text('last_saml_assertion')->nullable();
            $table->dropColumn('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mylogin_id');
            $table->string('email')->nullable(false)->change();
            $table->dropColumn('last_saml_assertion');
            $table->string('email_verified_at')->after('email');
        });
    }
};
