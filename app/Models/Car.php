<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'agency_id',
        'brand',
        'model',
        'year',
        'transmission',
        'fuel_type',
        'seats',
        'price_per_day',
        'image',
        'description',
        'is_available',
    ];

    // ---------- RELATIONSHIPS ----------

    // a car belongs to one agency
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    // a car has many bookings (history of all reservations made for it)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // a car has many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ---------- HELPER METHOD ----------

    // calculates the average rating of this car from all its reviews
    // returns 0 if there are no reviews yet, otherwise the average rounded to 1 decimal
    public function averageRating()
    {
        return round($this->reviews()->avg('rating'), 1) ?: 0;
    }
}
