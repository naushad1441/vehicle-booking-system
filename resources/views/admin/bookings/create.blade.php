@extends('admin.main-layout')

@section('content-header')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Vehicle</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Vehicle</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
@endsection
@section('body')
    <!-- Main row -->
    <div class="row">
    	<div class="container-fluid">
            <h1>Create Booking</h1>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                use Carbon\Carbon;
            @endphp

            <form action="{{ route('admin.bookings.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="vehicle_id">Vehicle</label>
                    <select class="form-control" id="vehicle_id" name="vehicle_id">
                        @foreach ($availableVehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" min="{{ Carbon::now()->format('Y-m-d') }}">
                </div>

                {{-- <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" min="{{ Carbon::now()->format('Y-m-d') }}">
                </div> --}}

                {{-- <div class="form-group">
                    <label for="duration">Duration</label>
                    <select class="form-control" id="duration" name="duration">
                        <option value="half_day">Half Day</option>
                        <option value="full_day">Full Day</option>
                    </select>
                </div> --}}

                <div class="form-group">
                    <label for="duration">Duration</label>
                    <select class="form-control" id="duration" name="duration">
                        <option value="half_day_morning">Half Day (Morning Session)</option>
                        <option value="half_day_evening">Half Day (Evening Session)</option>
                        <option value="full_day">Full Day</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Book Now</button>
            </form>
    	</div>
    </div>
    
@endsection