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
        Schema::table('users', function (Blueprint $table) {
            // Drop existing unique constraint on email
            $table->dropUnique(['email']);

            // Add composite unique constraint on email and auth_target_id
            $table->unique(['email', 'auth_target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop composite unique constraint
            $table->dropUnique(['email', 'auth_target_id']);

            // Restore original unique constraint on email only
            $table->unique('email');
        });
    }
};
