<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id(); // auto-increment primary key

            // each agency belongs to one user (the account that logs in)
            // if that user is deleted, delete the agency too (cascade)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('name');           // agency name, ex: "Atlas Rent Car"
            $table->string('address');        // physical address
            $table->string('city');           // city, ex: Casablanca
            $table->text('description')->nullable(); // short presentation text
            $table->string('logo')->nullable();      // path to logo image

            // status controls if the agency can publish cars or not
            // pending = just registered, waiting for admin
            // approved = admin said yes
            // rejected = admin said no
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
