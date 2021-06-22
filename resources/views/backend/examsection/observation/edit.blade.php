@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
@foreach($observations as $observation)
<section class="content">
  <div class="card">
    <form role="form" method="POST" action="{{ route('admin.observation.update',$observation->id)}}" class="validate" id="validate">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md-4">
            <label for="title">Name<span class="text-danger">*</span></label>
            <input type="text"  class="form-control @error('title') is-invalid @enderror max" id="title" placeholder="Enter Observation Name (Disipline)" name="title" autocomplete="off" autofocus value="{{ $observation->title }}">
            @error('title')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="remark">Remark<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="remark" name="remark" placeholder="Enter Grade (A)" autocomplete="off" value="{{ $observation->remark }}">
            @error('remark')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="value">Value<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="value" name="value" placeholder="Enter Defines (Very Good)" autocomplete="off" value="{{ $observation->value }}">
            @error('value')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Update Period">Update</button>
      </div>
    </form>
  </div>
</section>
@endforeach
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      name: {
        required: true,
        minlength: 2
      },
      start_time: "required",
      end_time: "required"
    },
    messages: {
      name: {
        required: "Please enter the name for Period",
        minlength: "Period must consist of at least 2 characters"
      },
      start_time: " Start Time  is required",
      end_time: " End Time  is required"
    },
    highlight: function(element) {
      $(element).css('background', '#ffdddd');
      $(element).css('border-color', 'red');
    },
    unhighlight: function(element) {
      $(element).css('background', '#ffffff');
      $(element).css('border-color', 'green');
    }
  });
});
</script>
@endpush