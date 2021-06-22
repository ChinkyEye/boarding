@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/tempusdominus-bootstrap-4.min.css">
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.period.store')}}" class="validate" id="validate">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-sm-4">
            <label for="name">Name</label>
            <input type="text"  class="form-control max" id="name" placeholder="Enter name for period" name="name" autofocus autocomplete="off" value="{{ old('name') }}">
            @error('slug')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="bootstrap-timepicker col-sm-4">
            <div class="form-group">
              <label for="start_time">Start Time:</label>
              <div class="input-group date" id="start_time" data-target-input="nearest">
                <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
                <input type="text" class="form-control datetimepicker-input" data-target="#start_time" name="start_time" value="{{ old('start_time') }}">
              </div>
              @error('start_time')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
          <div class="bootstrap-timepicker col-sm-4">
            <div class="form-group">
              <label for="end_time">End Time:</label>
              <div class="input-group date" id="end_time" data-target-input="nearest">
                <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
                <input type="text" class="form-control datetimepicker-input" data-target="#end_time" name="end_time" value="{{ old('end_time') }}">
              </div>
              @error('end_time')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Save Period">Save</button>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/moment.min.js"></script>
<script src="{{URL::to('/')}}/backend/js/tempusdominus-bootstrap-4.js"></script>
<script>
    $('#start_time').datetimepicker({
      format: 'LT'
    })
</script>
<script>
    $('#end_time').datetimepicker({
      format: 'LT'
    })
</script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
{{-- <script>
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
</script> --}}
@endpush