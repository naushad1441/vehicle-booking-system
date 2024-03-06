@extends('admin.main-layout')

@section('content-header')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">My Booking</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Booking</a></li>
          <li class="breadcrumb-item active">My Booking</li>
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
                @if(auth()->user()->is_admin)
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>My Bookings</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a class="btn btn-block btn-primary" href="{{ route('admin.bookings.create')}}">
                                New Booking
                            </a>
                        </ol>
                    </div>
                </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (count($bookings) > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Start Date</th>
                                <th>Duration</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th colspan="2">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->vehicle->name }}</td>
                                    <td>{{ $booking->start_date }}</td>
                                    <td>{{ $booking->duration }}</td>
                                    <td>{{ $booking->total_cost }}</td>
                                    <td>
                                        @if ($booking->start_date > $current_date)
                                            Ongoing
                                        @else
                                            Completed
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-danger">Delete</a>

                                        {{-- <form action="#" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this vehicle?')">Delete</button>
                                        </form> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>You don't have any bookings yet.</p>
                @endif
    	</div>
    </div>
    
@endsection