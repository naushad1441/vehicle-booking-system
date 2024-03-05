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
            <h1>Edit Vehicle</h1>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            <form action="{{route('admin.vehicles.update',['id'=>$vehicle->id])}}" method="POST">
                @csrf
        
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="hidden" class="form-control" id="id" name="id" value="{{ $vehicle->id }}">

                    <input type="text" class="form-control" id="name" name="name" value="{{ $vehicle->name }}">
                </div>  
        
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ $vehicle->description }}</textarea>
                </div>
        
                <div class="form-group">
                    <label for="daily_rate">Daily Rate</label>
                    <input type="number" class="form-control" id="daily_rate" name="daily_rate" min="0.01" step="0.01" value="{{ $vehicle->daily_rate }}">
                </div>
        
                <div class="form-group">
                    <label for="hourly_rate">Hourly Rate (Optional)</label>
                    <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" min="0.01" step="0.01" value="{{ $vehicle->hourly_rate }}">
                    <small class="text-muted">Leave blank if not applicable.</small>
                </div>
        
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="available" name="available" {{ old('available') ? 'checked' : '' }}>
                  
                  <label class="form-check-label" for="available">Available</label>
                </div>
        
                <button type="submit" class="btn btn-primary">update Vehicle</button>
            </form>
    	</div>
    </div>
    
@endsection