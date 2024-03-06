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
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Bookings</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a class="btn btn-block btn-primary" href="{{ route('admin.bookings.create')}}">
                                Book now
                            </a>
                        </ol>
                    </div>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        <ul>
                            {{ session('success') }}
                        </ul>
                    </div>
                @endif

                @if (count($bookings) > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Start Date</th>
                                {{-- <th>End Date</th> --}}
                                <th>Duration</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->vehicle->name }}</td>
                                    <td>{{ $booking->start_date }}</td>
                                    {{-- <td>{{ $booking->end_date }}</td> --}}
                                    <td>{{ $booking->duration }}</td>
                                    <td>{{ $booking->total_cost }}</td>
                                    <td>
                                        @if ($booking->start_date > $current_date)
                                            Ongoing
                                        @else
                                            Completed
                                        @endif
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