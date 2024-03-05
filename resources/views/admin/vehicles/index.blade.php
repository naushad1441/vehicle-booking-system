@extends('admin.main-layout')

@section('script')
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

@endsection
@section('content-header')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Vehicle</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
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
            @if(auth()->user()->is_admin)
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Vehicle Details</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a class="btn btn-block btn-primary" href="{{ route('admin.vehicles.create')}}">
                                Add Vehicle
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

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Daily Rate</th>
                        <th>Available</th>
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->name }}</td>
                            <td>{{ Str::limit($vehicle->description, 50) }}</td>
                            <td>{{ number_format($vehicle->daily_rate, 2) }}</td>
                            <td>
                                @if ($vehicle->available)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            
                            @if(auth()->user()->is_admin)
                                <td>
                                    <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-sm btn-primary">Edit</a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this vehicle?')">Delete</button>
                                    </form>
                                </td>
                            @else
                                @if($vehicle->available)
                                    <td>
                                        <a href="{{ route('admin.bookings.create') }}" class="btn btn-sm btn-primary">Booking</a>
                                    </td>
                                @else
                                    <td>
                                        <a  href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-sm btn-primary disabled" data-toggle="tooltip" title="all ready booked">Booking</a>
                                    </td>
                                    @endif
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No vehicles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $vehicles->links() }}
    	</div>
    </div>
    
@endsection