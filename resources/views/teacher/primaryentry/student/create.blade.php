@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
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
          <li class="breadcrumb-item"><a href="{{URL::to('/')}}/home">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{ route('admin.student.store')}}" class="signup" id="signup" enctype="multipart/form-data">
      <div class="card-body">
        {{ csrf_field() }}
        <h3 class="text-info">Student details</h3>
        <div class="row">
          <div class="form-group col-md-4">
            <label for="address">First Name <span class="text-danger">*</span></label>
            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter Your Name" autocomplete="off" value="{{ old('first_name') }}" autofocus>
            @error('first_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="address">Middle name</label>
            <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="Enter Your Name" maxlength="15" autocomplete="off" value="{{ old('middle_name') }}">
          </div>
          <div class="form-group col-md-4">
            <label for="address">Last name <span class="text-danger">*</span></label>
            <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter Your Name" maxlength="15" autocomplete="off" value="{{ old('last_name') }}">
            @error('last_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="roll_no">Roll No <span class="text-danger">*</span></label>
            <input type="text" name="roll_no" class="form-control" id="roll_no" autocomplete="off" value="{{ old('roll_no') }}">
            @error('roll_no')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" autocomplete="off" value="{{ old('email') }}">
            @error('email')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="dob">Date of Birth <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="dob" name="dob" autocomplete="off" value="{{ old('dob') }}">
            @error('dob')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label class="d-flex">Gender <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : ''}}>
              <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : ''}}>
              <label class="form-check-label" for="female">Female</label>
            </div>
            @error('gender')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="register_id">Register Id <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="register_id" name="register_id" autocomplete="off" value="{{ old('register_id') }}">
            @error('register_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="register_date">Register Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="register_date" name="register_date" autocomplete="off" value="{{ old('register_date') }}">
            @error('register_date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="multiple" class="control-label col-md-3">Shift <span class="text-danger">*</span></label>
            <select class="form-control" name="shift_id" id="shift_data">
              <option value="">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
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
          <div class="form-group col-md-3">
            <label for="multiple" class="control-label col-md-3">Class <span class="text-danger">*</span></label>
            <select class="form-control" name="class_id" id="class_data">
              <option value="">Select Your Class</option>
              @foreach ($classes as $key => $class)
              <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : ''}}> 
                {{$class->name}}
              </option>
              @endforeach    
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="multiple" class="control-label">Section <span class="text-danger">*</span></label>
            <select class="form-control" name="section_id" id="section_data">
              <option value="">Select Your Section</option>
              @foreach ($sections as $key => $section)
              <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : ''}}> 
                {{$section->name}}
              </option>
              @endforeach    
            </select>
            @error('section_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="multiple" class="control-label">Batch <span class="text-danger">*</span></label>
            <select class="form-control" name="batch_id">
              <option value=" ">Select Your Batch</option>
              @foreach ($batchs as $key => $batch)
              <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : ''}}> 
                {{$batch->name}}
              </option>
              @endforeach    
            </select>
            @error('batch_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-12">
            <label for="document_name">Document type <span class="text-danger">*</span></label>
            <input type="text" class="form-control max" id="document_name" name="document_name" placeholder="Enter document type" autocomplete="off" value="{{ old('document_name') }}">
            @error('document_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="imageDoc">Document <span class="text-danger">* required .jpg</span></label>
            <img id="blahDoc" src="{{URL::to('/')}}/backend/images/80x80.png" onclick="document.getElementById('imgInpDoc').click();" alt="your image" class="img-thumbnail" style="width: 175px;height: 140px"/>
            <input type='file' class="d-none" id="imgInpDoc" name="doc_file" />
            @error('doc_file')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="image">Student Image <span class="text-danger">* requried .jpg</span></label>
            <img id="blah" src="{{URL::to('/')}}/backend/images/80x80.png" onclick="document.getElementById('imgInp').click();" alt="your image" class="img-thumbnail" style="width: 175px;height: 140px"/>
            <div class="input-group my-3">
              <input type='file' class="d-none" id="imgInp" name="image" />
            </div>
            @error('image')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
        <hr/>
        <h3 class="text-info">Parent Information</h3>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="father_name">Father Name</label>
            <input type="text" class="form-control" id="father_name" name="father_name" autocomplete="off" value="{{ old('father_name') }}">
            @error('father_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="mother_name">Mother Name</label>
            <input type="text" class="form-control" id="mother_name" name="mother_name" autocomplete="off" value="{{ old('mother_name') }}">
            @error('mother_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" autocomplete="off" value="{{ old('address') }}">
            @error('address')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group  col-md-6">
            <label for="multiple" class="control-label col-md-3">Nationality</label>
            <select class="form-control" name="nationality_id">
              <option value=" ">Select Your Nationality</option>
              @foreach ($nationalities as $key => $nationality)
              <option value="{{ $nationality->id }}" {{ old('nationality_id') == $nationality->id ? 'selected' : ''}}> 
                {{$nationality->n_name}}
              </option>
              @endforeach    
            </select>
            @error('nationality_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-info text-capitalize">Save</button>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script>
  $('#dob').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script>
  $('#register_date').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script type="text/javascript">
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getClassList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
        $('#class_data').html('');
        $('#class_data').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#class_data').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    var data_id = $(event.target).val(),
    shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getSectionList')}}",
      data:{
        _token: token,
        data_id: data_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#section_data').html('');
        $('#section_data').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#section_data').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alertify.alert("Sorry");
      }
    });
  });
</script>
<script type="text/javascript">
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#blah').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }
  function readURLDoc(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#blahDoc').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#imgInp").change(function() {
    readURL(this);
  });
  $("#imgInpDoc").change(function() {
    readURLDoc(this);
  });
</script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
  $().ready(function() {
    $("#signup").validate({
      rules: {
        first_name: "required",
        last_name: "required",
        roll_no : "required",
        email : "required",
        class_id : "required",
        section_id : "required",
        shift_id : 'required',
        batch_id : 'required',
        batch_id : 'required',
        email : 'required',
        dob : 'required',
        address : 'required',
        register_id : 'required',
        register_date : 'required',
        nationality_id : 'required',
      },
      messages: {
        first_name: " first name field is required **",
        last_name: " last name field is required **",
        roll_no: " roll no field is required **",
        email: " email field is required **",
        class_id : " class is required",
        section_id : "section is required",
        shift_id : 'shift is required',
        batch_id : 'batch is required',
        email : 'email is required',
        dob : 'date of birth is required',
        address : 'address is required',
        register_id : 'register id is required',
        register_date : 'register date is required',
        nationality_id : 'nationality  is required',
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