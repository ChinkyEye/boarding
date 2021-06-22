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
    <form role="form" method="POST" action="{{route('admin.class.store')}}" class="signup" id="signup">
      <div class="card-body">
        @csrf
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text"  class="form-control max" id="name" placeholder="Enter name for class" name="name" autocomplete="off" autofocus value="{{ old('name') }}">
          {{-- <input pattern="[a-z0-9]+" title="Only lowercase / numbers allowed" /> --}}
          @error('slug')
          <span class="text-danger font-italic" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        
      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize">Save</button>
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
{{-- <script>
  $( "#name" ).keypress(function(inputtxt) {
    // var value = this.inputtxt;
    var letters = /^[A-Za-z]+$/;
    if(inputtxt.value.match(letters))
    {
    alert('Your name have accepted : you can try another');
    return true;
    }
    else
    {
    alert('small alphabet characters only');
    return false;
    }
    alert("You pressed a key inside the input field");
  });
</script> --}}
<script>
function myFunction(inputtxt) {
  var letters = /^[a-z]+$/;
  if(inputtxt.value.match(letters))
  {
  alert('Your name have accepted : you can try another');
  return true;
  }
  else
  {
  alert('small alphabet characters only');
  return false;
  }
  alert("You pressed a key inside the input field");
}
</script>

@endpush