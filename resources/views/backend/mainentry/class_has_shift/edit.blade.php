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
    @foreach($class_has_shifts as $class_has_shift)
    <form role="form" method="POST" action="{{ route('admin.c_has_shift.update',$class_has_shift->id)}}" class="signup" id="signup">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md-6">
            <label for="multiple" class="control-label col-md-3">Shift</label>
            <select class="form-control" name="shift_id" id="shift_id">
              <option value="">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" {{ $class_has_shift->shift_id == $shift->id ? 'selected' : ''}}> 
                {{$shift->name}}
              </option>
              @endforeach    
            </select>
            @error('shift_id')
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
        class_id: "required",
        shift_id: "required"
      },
      messages: {
        class_id: " class name field is required **",
        shift_id: " shift name field is required **"
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
<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>
@endpush