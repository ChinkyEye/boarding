@extends('backend.main.app')
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
    <form role="form" method="POST" action="{{route('admin.topic.store')}}" class="validate" id="validate">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-md-4">
            <label for="topic" class="control-label col-md-3">Class</label>
            <select class="form-control" name="class_id" id="class_id">
              <option value="">Select Your Class</option>
              @foreach ($classes as $key => $data)
              <option value="{{ $data->id }}" {{ old('class_id') == $data->id ? 'selected' : ''}}> 
                {{$data->name}}
              </option>
              @endforeach    
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="topic" class="control-label col-md-3">Topic</label>
            <input type="text"  class="form-control @error('topic') is-invalid @enderror max" id="topic" placeholder="Enter topic" name="topic" autocomplete="off" autofocus value="{{ old('topic') }}">
            @error('topic')
            <span class="text-danger font-italic" role="alert">
             <strong>{{ $message }}</strong>
           </span>
           @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="fee" class="control-label col-md-3">Fee</label>
            <input type="text"  class="form-control @error('fee') is-invalid @enderror max" id="fee" placeholder="Enter fee" name="fee" autocomplete="off" autofocus value="{{ old('fee') }}">
            @error('fee')
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
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      name: {
        required: true,
        minlength: 2
      }
    },
    messages: {
      name: {
        required: "Please enter a nationality",
        minlength: "Nationality must consist of at least 2 characters"
      }
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