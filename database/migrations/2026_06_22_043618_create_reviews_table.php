<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // who wrote the review (a client)
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');

            // which car the review is about
            $table->foreignId('car_id')->constrained()->onDelete('cascade');

            $table->tinyInteger('rating'); // a number from 1 to 5
            $table->text('comment')->nullable(); // optional written comment

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
