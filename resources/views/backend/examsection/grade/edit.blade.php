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
  <div class="card">
    @foreach($grades as $grade)
    <form role="form" method="POST" action="{{ route('admin.grade.update',$grade->id)}}">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md">
            <label for="name">Grade Name<span class="text-danger">*</span></label>
            <input type="text"  class="form-control @error('name') is-invalid @enderror max" id="name" name="name" value="{{$grade->name}}" placeholder="Enter grade name">
            @error('name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="min">Min<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="min" name="min" value="{{$grade->min}}" placeholder="Min %">
            @error('min')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="max">Max<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="max" name="max" value="{{$grade->max}}" placeholder="Max %">
            @error('max')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="grade_point">Grade Point<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="grade_point" name="grade_point" autocomplete="off" value="{{ $grade->grade_point}}" placeholder="4.0">
            @error('grade_point')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md">
            <label for="remark">Remarks<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="remark" name="remark" autocomplete="off" value="{{ $grade->remark }}" placeholder="Enter Remark (Excellent)">
            @error('remark')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
    @endforeach
  </div>
</section>
@endsection
@push('javascript')
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
@endpush