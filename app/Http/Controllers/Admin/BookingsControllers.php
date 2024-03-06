<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon; // For date/time handling
use Validator;

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
        $current_date = Carbon::now()->toDateString();
        return view('admin.bookings.index', compact('bookings','current_date'));
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
        // $availableVehicles = Vehicle::where('available', true)->get(); // Get available vehicles
        $availableVehicles = Vehicle::all(); // Get available vehicles
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
            'start_date' => 'required|date|after:yesterday', // Ensure start date is after yesterday
            'duration' => 'required|in:half_day_morning,half_day_evening,full_day', // Validate duration options with session types
        ]);

        $user = auth()->user(); // Get authenticated user
        $vehicle = Vehicle::findOrFail($validatedData['vehicle_id']); // Find the selected vehicle
        
        $this->validateBookingAvailability($vehicle, $validatedData['start_date'],$validatedData['start_date'], $validatedData['duration']);

        $startDate = Carbon::parse($request->get('start_date'));
        $validatedData['user_id'] = $user->id; // Set user ID

        $validatedData['total_cost'] = $this->calculateCost($vehicle, $startDate, $startDate, $validatedData['duration']);
        
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

    private function validateBookingAvailability(Vehicle $vehicle, $startDate, $endDate, string $duration)
    { 
        // $existingBookings = Booking::where('vehicle_id', $vehicle->id)
        //     ->where(function ($query) use ($startDate, $endDate) {
        //         $query->where(function ($query) use ($startDate, $endDate) {
        //             $query->where('start_date', '<=', $startDate)
        //                 ->where('start_date', '>=', $endDate);
        //         })->orWhere(function ($query) use ($startDate, $endDate) {
        //             $query->where('start_date', '>=', $startDate)
        //                 ->where('start_date', '<=', $endDate);
        //         });
        //     })
        $existingBookings = Booking::where('vehicle_id', $vehicle->id)->where("start_date",'=',$startDate)->get();
        // ->get();
            
            if ($existingBookings->count() > 0) {                
                $exist_booking_duration_list = array();
                foreach($existingBookings as $existingBooking){
                    $exist_booking_duration_list[$existingBooking->duration] = $existingBooking->duration;
                }

                $errorMessage = '';
                if ($duration === 'full_day') {
                    if (array_key_exists($duration,$exist_booking_duration_list)) {
                        $errorMessage = 'This vehicle is already booked for a full day on the selected date : '.$startDate; 
                    }
                    elseif(array_key_exists('half_day_morning',$exist_booking_duration_list))
                    {
                        $errorMessage = 'This vehicle is already booked for the morning session on the selected date : '.$startDate;
                    }
                    elseif(array_key_exists('half_day_evening',$exist_booking_duration_list)){
                        $errorMessage = 'This vehicle is already booked for the evining session on the selected date : '.$startDate;
                    }
                }
                elseif($duration === 'half_day_morning')
                {
                    if (array_key_exists($duration,$exist_booking_duration_list)) {
                        $errorMessage = 'This vehicle is already booked for the morning session on the selected date : '.$startDate;
                    }
                    elseif(array_key_exists("full_day",$exist_booking_duration_list))
                    {
                        $errorMessage = 'This vehicle is already booked for a full day on the selected date : '.$startDate; 
                    }
                }
                elseif($duration === 'half_day_evening'){
                    if (array_key_exists($duration,$exist_booking_duration_list)) {
                        $errorMessage = 'This vehicle is already booked for the evining session on the selected date : '.$startDate;
                    }
                    elseif(array_key_exists("full_day",$exist_booking_duration_list))
                    {
                        $errorMessage = 'This vehicle is already booked for a full day on the selected date : '.$startDate; 
                    }
                }
                // dump($errorMessage);

                if($errorMessage != ''){
                    $error = \Illuminate\Validation\ValidationException::withMessages([
                        'vehicle_id' => [$errorMessage],
                     ]);
                     throw $error;
                }
            }
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
        $current_date = Carbon::now()->toDateString();
        return view('admin.bookings.booking_list', compact('bookings','current_date'));
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
