<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // This runs when we do "php artisan migrate"
    // We are ADDING new columns to the default "users" table that Laravel already gives us
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // role tells us if this user is admin, agency or client
            // we use enum so only these 3 values are allowed
            $table->enum('role', ['admin', 'agency', 'client'])->default('client')->after('email');

            // phone number, useful for both agency and client
            $table->string('phone')->nullable()->after('role');
        });
    }

    // This runs when we do "php artisan migrate:rollback"
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone']);
        });
    }
};
