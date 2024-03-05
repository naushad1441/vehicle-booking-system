<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vehicle;
use Validator;

class VehiclesControllers extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) // Optional: Allow filtering based on request parameters
    {
        $vehicles = Vehicle::query();

        // Implement filtering logic based on $request parameters (optional)

        $vehicles = $vehicles->paginate(10); // Paginate results (optional)

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'daily_rate' => 'required|numeric|min:0.01',
            'hourly_rate' => 'nullable|numeric|min:0.01',
        ]);

        if($request->available == 'on'){
            $validatedData['available'] = 1;
        }
        else
        {
            $validatedData['available'] = 0;
        }
        $vehicle = Vehicle::create($validatedData);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vehicle = Vehicle::where('id',$id)->first();
        // dd($vehicle);
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = Validator::make($request->all(),[
            'id'=> 'required',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'daily_rate' => 'required|numeric|min:0.01',
            'hourly_rate' => 'nullable|numeric|min:0.01'
        ]);
        
        if($validator->fails()) 
        {
            return redirect()->back();
        } 
        else 
        {
            if($request->available == 'on')
            {
                $available = 1;
            }
            else
            {
                $available = 0;
            }
            $update = Vehicle::where('id',$request->id)->first();
            $update->name = $request->name;
            $update->description = $request->description;
            $update->daily_rate = $request->daily_rate;
            $update->hourly_rate = $request->hourly_rate;
            $update->available = $available;
            $update->save();

            return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Vehicle::where('id',$id)->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle deleted successfully!');
    }
}
