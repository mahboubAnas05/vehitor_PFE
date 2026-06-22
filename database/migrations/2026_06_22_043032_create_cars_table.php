<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            // each car belongs to one agency
            $table->foreignId('agency_id')->constrained()->onDelete('cascade');

            $table->string('brand');          // ex: Dacia
            $table->string('model');          // ex: Logan
            $table->year('year');             // manufacturing year, ex: 2022
            $table->string('transmission');   // manual or automatic
            $table->string('fuel_type');      // essence, diesel, electric...
            $table->integer('seats');         // number of seats
            $table->decimal('price_per_day', 8, 2); // price in MAD per day
            $table->string('image')->nullable();    // path to car photo
            $table->text('description')->nullable();

            // is_available lets the agency hide a car temporarily
            // without deleting it (ex: car is being repaired)
            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
