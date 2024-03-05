<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'vehicle_id', 'start_date', 'end_date', 'duration', 'total_cost'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeAvailable($query, $date, $duration)
    {
        // Improved scopeAvailable logic to handle both daily and hourly rates
        $endDate = $this->calculateEndDate($date, $duration);
        return $query->whereDoes('vehicle', function ($q) use ($date, $endDate) {
            $q->where('available', true)
              ->whereDoesntHave('bookings', function ($q) use ($date, $endDate) {
                  $q->where(function ($q) use ($date, $endDate) {
                      $q->where('start_date', '<=', $date)
                        ->where('end_date', '>=', $endDate);
                  })
                  ->orWhere(function ($q) use ($date, $endDate) {
                      $q->where('start_date', '>=', $date)
                        ->where('end_date', '<=', $endDate);
                  });
              });
        });
    }

    private function calculateEndDate($date, $duration)
    {
        if ($duration === 'full_day') {
            return $date->addDay(1);
        } else {
            // Implement logic for hourly bookings (e.g., add specified hours)
        }
    }
}
