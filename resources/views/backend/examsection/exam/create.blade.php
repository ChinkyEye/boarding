@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
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
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.exam.store')}}">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-md-4">
            <label for="name">Name<span class="text-danger">*</span></label>
            <input type="text"  class="form-control @error('name') is-invalid @enderror max" id="name" placeholder="Enter name for exam" name="name" autocomplete="off" autofocus value="{{ old('name') }}">
            @error('name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
           <div class="form-group col-md-4">
            <label for="start_date">Start Date<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="{{ old('start_date') }}">
            @error('start_date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="end_date">End Date<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="{{ old('end_date') }}">
            @error('end_date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          
        </div>

      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Save Shift">Save</button>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script>
  $('#start_date').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script>
  $('#end_date').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script>
$().ready(function() {
  $("#signup").validate({
    rules: {
      name: "required"
      start_date: "required"
      end_date: "required"
    },
    messages: {
      name: " name field is required **"
      start_date: " date field is required **"
      end_date: " date field is required **"
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