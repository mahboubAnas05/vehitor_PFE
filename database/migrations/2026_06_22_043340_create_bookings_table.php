<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // who is booking (a client)
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');

            // which car is being booked
            $table->foreignId('car_id')->constrained()->onDelete('cascade');

            $table->date('start_date'); // when rental starts
            $table->date('end_date');   // when rental ends

            // total_price is calculated automatically:
            // number_of_days * price_per_day (we compute this in the controller)
            $table->decimal('total_price', 10, 2);

            // status of the booking
            // pending = client just booked, waiting confirmation
            // confirmed = agency/admin confirmed it
            // cancelled = client or agency cancelled it
            // completed = the rental period is over
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
