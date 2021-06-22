@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
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
  <div class="card">
    @foreach($teacherhasattendance as $data)
    <form role="form" method="POST" action="{{ route('admin.teacher-attendance.update', $data->id)}}" class="signup" id="signup">
      <div class="card-body">
        @method('PATCH')
        @csrf
         <div class="row">
          <div class="form-group col-md-12">
            <label for="date">Date<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="date" name="date" autocomplete="off" value="{{$data->date}}">
            @error('date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label class="d-flex">Status <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="is_present" value="1" {{ old('status') == '1' ? 'checked' : ''}}>
              <label class="form-check-label" for="male">Present</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="is_absent" value="0" {{ old('status') == '0' ? 'checked' : ''}}>
              <label class="form-check-label" for="female">Absent</label>
            </div>
            @error('status')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Update Shift">Update</button>
      </div>
    </form>
    @endforeach
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#signup").validate({
    rules: {
      name: "required"
    },
    messages: {
      name: " name field is required **"
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