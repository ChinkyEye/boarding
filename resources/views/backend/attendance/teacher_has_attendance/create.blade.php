@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize"></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{URL::to('/')}}/home">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{ route('admin.teacher-attendance.store')}}" class="signup" id="signup">
      <div class="card-body">
        {{ csrf_field() }}

        <div class="row">
          <div class="form-group col-md-12">
            <label class="control-label">Select Teacher <span class="text-danger">*</span></label>
            <select class="form-control" name="teacher_id" id="teacher_id">
              <option value="">Select Your Teacher</option>
              @foreach ($teachers as $key => $teacher)
              <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : ''}}> 
                {{$teacher->f_name." ". $teacher->m_name. " ". $teacher->l_name}}
              </option>
              @endforeach    
            </select>
            @error('teacher_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-12">
            <label for="date">Date<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="date" name="date" autocomplete="off" value="{{ date('Y-m-d') }}">
            @error('date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-12">
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
        <button type="submit" class="btn btn-info text-capitalize">Save</button>
        <a href="" class="btn btn-xs btn-outline-info float-sm-right" data-toggle="tooltip" data-placement="top" title="Show Detail"><i class="fas fa-eye"></i></a>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
  $().ready(function() {
    $("#signup").validate({
      rules: {
        date : 'required',
      },
      messages: {
        date : 'date  is required',
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