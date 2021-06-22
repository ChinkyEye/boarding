@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 0, strpos((Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('parent.store')}}" class="parent" id="signup">
      <div class="card-body">
        @csrf
        <div class="form-group">
          <label for="name">Father Name</label>
          <input type="text"  class="form-control max" id="father_name" placeholder="Enter your Father Name" name="father_name" required="true"  autocomplete="off">
        </div>
        <div class="form-group">
          <label for="name">Mother Name</label>
          <input type="text"  class="form-control max" id="mother_name" placeholder="Enter your Mother Name" name="mother_name" required="true"  autocomplete="off">
        </div>
        <div class="form-group">
          <label for="name">Address</label>
          <input type="text"  class="form-control max" id="address" placeholder="Enter your Address" name="address" required="true"  autocomplete="off">
        </div>
        <div class="form-group">
          <label for="multiple" class="control-label col-md-3">Nationality</label>
          <select class="form-control" name="product_id">
            <option>Select Your Nationality</option>
            @foreach ($nationalities as $key => $nationality)
            <option value="{{ $nationality->id }}"> 
              {{$nationality->n_name}}
            </option>
            @endforeach    
          </select>
        </div>
      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize">Save {{ substr((Route::currentRouteName()), 0, strpos((Route::currentRouteName()), "."))}} Page</button>
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
      father_name: "required"
    },
    messages: {
      father_name: " name field is required **"
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