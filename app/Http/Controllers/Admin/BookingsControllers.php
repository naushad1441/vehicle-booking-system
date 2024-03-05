<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon; // For date/time handling

class BookingsControllers extends Controller
{
    /**
     * Display a listing of bookings for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showBookings(Request $request)
    {
        $user = auth()->user(); // Get authenticated user

        $bookings = Booking::where('user_id', $user->id)->paginate(10); // Paginate results (optional)

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()); // Default to current date

        $availableVehicles = Vehicle::where('available', true)->get(); // Get available vehicles

        return view('admin.bookings.create', compact('startDate', 'availableVehicles'));
    }

    /**
     * Store a newly created booking in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'start_date' => 'required|date', // Ensure start date is after yesterday
            'end_date' => 'required|date', // Ensure end date is after start date
            'duration' => 'required|in:half_day_morning,half_day_evening,full_day', // Validate duration options
            // 'duration' => 'required|in:half_day,full_day', // Validate duration options
        ]);

        $user = auth()->user(); // Get authenticated user

        $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']); // Find the selected vehicle

        $validatedData['user_id'] = $user->id; // Set user ID

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);

        $validatedData['total_cost'] = $this->calculateCost($vehicle, $startDate, $endDate, $validatedData['duration']);
        // dump($validatedData['total_cost']);
        // die();
        $booking = Booking::create($validatedData);

        $vehicle->update(['available' => false]); // Mark vehicle as booked

        return redirect()->route('admin.bookings.index')->with('success', 'Booking created successfully!');
    }

    /**
     * Calculate the booking cost based on vehicle rates and duration.
     *
     * @param  Vehicle  $vehicle
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     * @param  string  $duration
     * @return float
     */
    private function calculateCost(Vehicle $vehicle, Carbon $startDate, Carbon $endDate, string $duration): float
    {
        if($endDate == $startDate){
          $totalDays = 1;  
        }
        else
        {
            $totalDays = $endDate->diffInDays($startDate);
        }

        // dump($totalDays);
        // dump($vehicle->daily_rate);

        if ($duration === 'half_day_morning' || $duration === 'half_day_evening') {
            // Implement logic to calculate half-day cost based on vehicle hourly rate (e.g., hourly rate * number of hours)
            $cost = $vehicle->hourly_rate * 6;
        } else {
            $cost = $totalDays * $vehicle->daily_rate;
        }
        // dump($cost);
        return number_format($cost, 2, '.', ''); // Format cost with two decimal places
    }

    /**
     * Display the specified booking.
     *
     * @param  Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {        
        $bookings = Booking::all(); // Paginate results (optional)
        return view('admin.bookings.booking_list', compact('bookings'));
    }

    /**
     * Show the form for editing the specified booking (optional).
     *
     * @param  Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking); // Implement authorization check to allow editing
        // Implement logic to retrieve data for 
    }
}
