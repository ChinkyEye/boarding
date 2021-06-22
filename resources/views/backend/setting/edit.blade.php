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
  <div class="card">
    @foreach($settings as $setting)
    <form role="form" method="POST" action="{{ route('admin.setting.update',$setting->id)}}">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md-12">
            <label for="school_name">School Name<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="school_name" name="school_name" autocomplete="off"  value="{{$setting->school_name}}">
            @error('school_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="address">Address<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="address" name="address" autocomplete="off" value="{{$setting->address}}">
            @error('address')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="phone_no">Phone Number<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="phone_no" name="phone_no" autocomplete="off" value="{{$setting->phone_no}}">
            @error('phone_no')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="email" name="email" autocomplete="off" value="{{$setting->email}}">
            @error('email')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="principal_name">Name of Principal <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="principal_name" name="principal_name" autocomplete="off" value="{{$setting->principal_name}}">
            @error('principal_name')
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
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
  $().ready(function() {
    $("#validate").validate({
      rules: {
        school_name : "required",
        phone_no : "required",
        address : "required",
        email : "required",
        principal_name : "required"
      },
      messages: {
        school_name: "School Name is required **",
        phone_no: "Phone No is required **",
        address: " Address is required **",
        email: " Email is required **",
        principal_name: " Principal Name is required **"
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