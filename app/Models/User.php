<?php

namespace App\Models;

// Illuminate\Foundation\Auth\User gives us all the login/password features
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // These are the fields we are allowed to fill using User::create([...])
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    // These fields will NEVER show up when we convert the user to JSON/array
    // (for security, we hide the password)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Laravel will automatically turn this column into a real PHP datetime object
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ---------- RELATIONSHIPS ----------

    // A user (if role = agency) has ONE agency profile
    public function agency()
    {
        return $this->hasOne(Agency::class);
    }

    // A user (if role = client) has MANY bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    // A user (if role = client) has MANY reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'client_id');
    }

    // ---------- HELPER METHODS ----------
    // These small methods make our code easier to read in controllers and views
    // Instead of writing: if ($user->role === 'admin')
    // We can write: if ($user->isAdmin())

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAgency()
    {
        return $this->role === 'agency';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }
}
