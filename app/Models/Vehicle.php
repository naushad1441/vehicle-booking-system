<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = ['name', 'description', 'daily_rate', 'hourly_rate', 'available'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getImageUrlAttribute($value)
    {
        // Implement logic to return image URL based on $value (e.g., storage path)
    }
}
