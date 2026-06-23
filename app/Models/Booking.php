<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id',
        'car_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
    ];

    // tells Laravel to convert these columns into real date objects automatically
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // ---------- RELATIONSHIPS ----------

    // a booking belongs to one client (a user)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // a booking belongs to one car
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // ---------- HELPER METHOD ----------

    // counts how many days the booking covers
    public function numberOfDays()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
