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
    @foreach($shifts as $shift)
    <form role="form" method="POST" action="{{ route('admin.shift.update',$shift->id)}}" class="validate" id="validate">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text"  class="form-control @error('name') is-invalid @enderror max" id="name" placeholder="Enter name" name="name" maxlength="30" autocomplete="off" value="{{ $shift->name }}" autofocus>
          @error('name')
          <span class="text-danger font-italic" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
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
{{-- <script>
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
</script> --}}
@endpush