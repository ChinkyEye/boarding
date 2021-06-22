@extends('main.main.app')
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }}</h1>
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
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('main.school.store')}}" enctype="multipart/form-data">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-md-2">
            <div class="input-group">
              <input type="file" class="form-control d-none" id="image" name="image">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQl1xtOkMGh312RKiJXUPbwyODQ7hdHgHFqYR5RwBGHiKaKz9eO&s" id="profile-img-tag" width="200px" onclick="document.getElementById('image').click();" alt="your image" class="img-thumbnail img-fluid editback-gallery-img center-block"  />
            </div>
            <label for="image">Choose Logo</label>
            <small class="text-danger mr-4">*</small>
              @error('image')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
          </div>
          <div class="col-md-10 row">
            <div class="form-group col-md-6">
              <label for="school_name">School Name</label>
              <small class="text-danger mr-4">*</small>
              <input type="school_name" class="form-control max" name="school_name" id="school_name" placeholder="Enter school name" autocomplete="off" value="{{ old('school_name') }}">
              @error('school_name')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
              @error('slug')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label for="principal_name">Principal Name</label>
              <small class="text-danger mr-4">*</small>
              <input type="text" class="form-control max" name="principal_name" id="principal_name" placeholder="Enter name of principal" autocomplete="off" value="{{ old('principal_name') }}">
              @error('principal_name')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label for="address">Address</label>
              <small class="text-danger mr-4">*</small>
              <input type="text" class="form-control max" name="address" id="address" placeholder="Enter address" autocomplete="off" value="{{ old('address') }}">
              @error('address')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label for="phone_no">Phone No</label>
              <small class="text-danger mr-4">*</small>
              <input type="text" class="form-control max" name="phone_no" id="phone_no" placeholder="Enter phone number" autocomplete="off" value="{{ old('phone_no') }}">
              @error('phone_no')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label for="email">Email</label>
              <small class="text-danger mr-4">*</small>
              <input type="text" class="form-control max" name="email" id="email" placeholder="Enter email" autocomplete="off" value="{{ old('email') }}">
              @error('email')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label for="url">URL</label>
              <small class="text-danger mr-4">*</small>
              <input type="text" class="form-control max" name="url" id="url" placeholder="Enter the url" autocomplete="off" value="{{ old('url') }}">
              @error('url')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label for="school_code">School Code</label>
              <small class="text-danger mr-4">*</small>
              <input type="text" class="form-control max" name="school_code" id="school_code" placeholder="Enter the code for school" autocomplete="off" value="{{ old('school_code') }}">
              @error('school_code')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label for="type_of_school">Type of School</label>
              <small class="text-danger mr-4">*</small>
              <input type="text" class="form-control max" name="type_of_school" id="type_of_school" placeholder="Enter the Type of school" autocomplete="off" value="{{ old('type_of_school') }}">
              @error('type_of_school')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label for="level">Level</label>
              <small class="text-danger mr-4">*</small>
              <input type="text" class="form-control max" name="level" id="level" placeholder="Enter the level" autocomplete="off" value="{{ old('level') }}">
              @error('level')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label for="running_class">Running Class</label>
              <small class="text-danger mr-4">*</small>
              <select class="form-control" name="running_class" id="running_class">
                <option value="">Select Your Running Class</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
                <option value="4">Four</option>
                <option value="5">Five</option>
                <option value="6">Six</option>
                <option value="7">Seven</option>
                <option value="8">Eight</option>
                <option value="9">Nine</option>
                <option value="10">Ten</option>
                <option value="11">Eleven</option>
                <option value="12">Twelve</option>
              </select>
              @error('running_class')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
          
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
<script type="text/javascript">
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#profile-img-tag').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
  $("#image").change(function(){
    readURL(this);
  });
</script>
@endpush