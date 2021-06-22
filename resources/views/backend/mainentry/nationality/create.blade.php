@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{url('/')}}/backend/css/nepali.datepicker.v2.2.min.css">
<link href="{{url('/')}}/backend/css/nepali.datepicker.min.css" rel="stylesheet" type="text/css"/>
@endpush
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
    <form role="form" method="POST" action="{{route('admin.nationality.store')}}" class="validate" id="validate">
      <div class="card-body">
        @csrf
        <div class="form-group">
          <label for="name">Nationality</label>
          <input type="text"  class="form-control max" id="n_name" placeholder="Enter Nationality" name="n_name" autocomplete="off" autofocus value="{{old('n_name')}}">
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
  $("#validate").validate({
    rules: {
      n_name: {
        required: true,
        minlength: 2
      }
    },
    messages: {
      n_name: {
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
<script type="text/javascript" src="{{url('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD HH-mm-ss");
    $('#nepali-datepicker-1').val(currentDate);
  });
</script>
<script src="{{url('/')}}/backend/js/nepali.datepicker.min.js" type="text/javascript"></script>
@endpush