<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    // fields we can mass-assign with Agency::create([...])
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'city',
        'description',
        'logo',
        'status',
    ];

    // ---------- RELATIONSHIPS ----------

    // an agency belongs to one user (the login account)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // an agency has many cars
    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    // ---------- HELPER METHODS ----------

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}
