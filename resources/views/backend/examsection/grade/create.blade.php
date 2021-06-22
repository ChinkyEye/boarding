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
        <p class="text-danger m-0">The Grade Max and Min must be from 100% to 0%.</p>
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
    <form role="form" method="POST" action="{{route('admin.grade.store')}}">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-md">
            <label for="name">Grade Name<span class="text-danger">*</span></label>
            <input type="text"  class="form-control @error('name') is-invalid @enderror max" id="name" placeholder="Enter grade name" name="name" autocomplete="off" autofocus value="{{ old('name') }}">
            @error('slug')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="min">Min<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="min" name="min" autocomplete="off" value="{{ old('min') }}" placeholder="Min %">
            @error('min')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="max">Max<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="max" name="max" autocomplete="off" value="{{ old('max') }}" placeholder="Max %">
            @error('max')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="grade_point">Grade Point<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="grade_point" name="grade_point" autocomplete="off" value="{{ old('grade_point') }}" placeholder="4.0">
            @error('grade_point')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="remark">Remarks<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="remark" name="remark" autocomplete="off" value="{{ old('remark') }}" placeholder="Enter Remark (Excellent)">
            @error('remark')
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
      name: "required",
      start_date: "required",
      end_date: "required",
    },
    messages: {
      name: " name field is required **",
      start_date: " date field is required **",
      end_date: " date field is required **",
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