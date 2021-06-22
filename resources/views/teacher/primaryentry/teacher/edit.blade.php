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
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    @foreach($teachers as $teacher)
    <form role="form" method="POST" action="{{ route('admin.teacher.update',$teacher->id)}}" enctype="multipart/form-data">
       @method('PATCH')
       @csrf
      <div class="modal-body" >
        <p class="font-weight-bold">Teacher Form</p>
        <div class="row">
          <div class="form-group col-sm-4">
            <label for="government_id">Government Id:</label>
            <input type="text"  class="form-control max" id="government_id" placeholder="Enter Government Id" name="government_id" value="{{ $teacher->government_id }}"  autocomplete="off">
          </div>
          <div class="form-group col-sm-4">
            <label for="insurance_id">Insurance Id(31):</label>
            <input type="text"  class="form-control max" id="insurance_id" placeholder="Enter Insurance Id" name="insurance_id"  autocomplete="off" value="{{ $teacher->insurance_id }}">
          </div>
          <div class="form-group col-sm-4">
            <label for="pan_id">PAN Id:</label>
            <input type="text"  class="form-control max" id="pan_id" placeholder="Enter PAN Id" name="pan_id"  autocomplete="off" value="{{ $teacher->pan_id }}">
          </div>
          <div class="form-group col-sm-6">
            <label for="cinvestment_id">Citizenship Number:</label>
            <input type="text"  class="form-control max" id="cinvestment_id" placeholder="Enter Citizenship Number" name="cinvestment_id"  autocomplete="off" value="{{ $teacher->cinvestment_id }}">
          </div>
          <div class="form-group col-sm-6">
            <label for="pfund_id">Providence Fund Id:</label>
            <input type="text"  class="form-control max" id="pfund_id" placeholder="Enter Providence Fund Id" name="pfund_id"  autocomplete="off" value="{{ $teacher->pfund_id }}">
          </div>
          
          <div class="form-group col-sm-6">
            <label for="designation">Designation(310):</label>
            <input type="text"  class="form-control max" id="designation" placeholder="Enter designation" name="designation"  autocomplete="off" value="{{ $teacher->designation }}">
            @error('designation')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-6">
            <label for="teacher_code">Teacher Code:</label>
            <input type="text"  class="form-control max" id="teacher_code" placeholder="Enter teacher_code" name="teacher_code"  autocomplete="off" value="{{ $teacher->teacher_code }}">
            @error('teacher_code')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          
          <div class="form-group col-sm-4">
            <label for="f_name">First Name</label>
            <input type="text"  class="form-control max" id="f_name" placeholder="Enter First Name" name="f_name"  autocomplete="off" value="{{ $teacher->f_name }}">
            @error('f_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="m_name">Middle Name</label>
            <input type="text"  class="form-control max" id="m_name" placeholder="Enter Middle Name" name="m_name"  autocomplete="off" value="{{ $teacher->m_name }}">
            @error('m_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="l_name">Last Name</label>
            <input type="text"  class="form-control max" id="l_name" placeholder="Enter Last Name" name="l_name" autocomplete="off" value="{{ $teacher->l_name }}">
            @error('l_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="qualification">Qualification:</label>
            <small class="text-danger mr-4">*required</small>
            <input type="text"  class="form-control max" id="qualification" placeholder="Enter qualification" name="qualification"  autocomplete="off" value="{{ $teacher->qualification }}">
            @error('qualification')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="dob">DOB:</label>
            <small class="text-danger mr-4">*required</small>
            <input type="text"  class="form-control max" id="dob" placeholder="Enter Date of birth" name="dob"  autocomplete="off" value="{{ $teacher->dob }}">
            @error('dob')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="phone">Phone:</label>
            <small class="text-danger mr-4">*required</small>
            <input type="text"  class="form-control max" id="phone" placeholder="Enter phone number" name="phone"  autocomplete="off" value="{{ $teacher->phone }}">
            @error('phone')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="email">Email</label>
            <small class="text-danger mr-4">*required</small>
            <input type="text"  class="form-control max" id="email" placeholder="Enter email" name="email"  autocomplete="off" value="{{ $teacher->email }}">
            @error('email')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="password">Password</label>
            <input type="text"  class="form-control max" id="password" placeholder="Enter Password" name="password"  autocomplete="off" value="{{ $teacher->password }}">
            @error('password')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="address">Address:</label>
            <input type="text"  class="form-control max" id="address" placeholder="Enter Address" name="address"  autocomplete="off" value="{{ $teacher->address }}">
            @error('address')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-2">
            <label for="gender">Gender:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" id="male" value="male" {{ $teacher->gender == 'male' ? 'checked' : ''}}>
              <label class="form-check-label" for="male">
                Male
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" id="female" value="female" {{ $teacher->gender == 'female' ? 'checked' : ''}}>
              <label class="form-check-label" for="female">
                Female
              </label>
            </div>
            @error('gender')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-2">
            <label for="religion">Religion:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="religion" id="hindu" value="hindu" {{ $teacher->religion == 'hindu' ? 'checked' : ''}}>
              <label class="form-check-label" for="hindu">
                Hindu
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="religion" id="muslim" value="muslim" {{ $teacher->religion == 'muslim' ? 'checked' : ''}}>
              <label class="form-check-label" for="muslim">
                Muslim
              </label>
            </div>
            @error('religion')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-2">
            <label for="nationality_id">Select Nationality:</label>
            @foreach ($nationalities as $key => $nation)
            <div class="form-check">
              <input class="form-check-input" type="radio" name="nationality_id" id="{{ $nation->id }}" value="{{ $nation->id }}" {{$nation->id == $teacher->nationality_id ? 'checked' : ''}} >
              <label class="form-check-label" for="{{ $nation->id }}">
                 {{$nation->n_name}}
              </label>
            </div>
            @endforeach    
            @error('nationality_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-2">
            <label for="marital_status">Marital Status:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="marital_status" id="married" value="married" {{ $teacher->marital_status == 'married' ? 'checked' : ''}}>
              <label class="form-check-label" for="married">
                Married
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="marital_status" id="unmarried" value="unmarried" {{ $teacher->marital_status == 'unmarried' ? 'checked' : ''}}>
              <label class="form-check-label" for="unmarried">
                Unmarried
              </label>
            </div>
            @error('marital_status')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-2">
            <label for="uppertype">Select Uppertype:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="uppertype" id="temporary" value="temporary" {{ $teacher->uppertype == 'temporary' ? 'checked' : ''}} >
              <label class="form-check-label" for="temporary">
                Temporary
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="uppertype" id="permanent" value="permanent" {{ $teacher->uppertype == 'permanent' ? 'checked' : ''}} >
              <label class="form-check-label" for="permanent">
                Permanent
              </label>
            </div>
            @error('uppertype')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-2">
            <label for="training">Training:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="training" id="certified" value="certified" {{ $teacher->training == 'certified' ? 'checked' : ''}}>
              <label class="form-check-label" for="certified">
                Certified
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="training" id="uncertified" value="uncertified" {{ $teacher->training == 'uncertified' ? 'checked' : ''}}>
              <label class="form-check-label" for="uncertified">
                Uncertified
              </label>
            </div>
            @error('training')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-6">
            <label for="t_designation">Select Teacher Designation:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="t_designation" id="primary" value="primary" {{ $teacher->t_designation == 'primary' ? 'checked' : ''}} >
              <label class="form-check-label" for="primary">
                Primary
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="t_designation" id="secondary" value="secondary" {{ $teacher->t_designation == 'secondary' ? 'checked' : ''}} >
              <label class="form-check-label" for="secondary">
                Secondary
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="t_designation" id="highersecondary" value="highersecondary" {{ $teacher->t_designation == 'highersecondary' ? 'checked' : ''}} >
              <label class="form-check-label" for="highersecondary">
                Higher Secondary
              </label>
            </div>
            @error('t_designation')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="multiple" class="control-label col-md-3">Shift</label>
            <select class="form-control js-multiple" name="shift_id[]" multiple="multiple">
              <option value=" ">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" @foreach($teacherhasshifts as $teach_shift) {{$teach_shift->shift_id == $shift->id ? 'selected' : ''}} @endforeach> 
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
          <div class="form-group col-sm-6">
            <label for="j_date">Joining Date:</label>
            <input type="text"  class="form-control max" id="j_date" placeholder="Enter Joining Date" name="j_date"  autocomplete="off" value="{{ $teacher->j_date }}">
            @error('j_date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-6">
            <label for="p_date">Promotion Date:</label>
            <input type="text"  class="form-control max" id="p_date" placeholder="Enter broad subject heading" name="p_date"  autocomplete="off" value="{{ $teacher->p_date }}">
          </div>
          <div class="form-group">
            <label for="image">Choose Image</label>
            <input type="hidden" value="{{$teacher->image}}">
            <div class="input-group">
              <input type="file" class="form-control d-none" id="image" name="image"  value="{{$teacher->image}}" >
              <img src="{{URL::to('/')}}/images/teacher/{{$teacher->image}}" id="profile-img-tag" width="200px" onclick="document.getElementById('image').click();" alt="your image" class="img-thumbnail img-fluid editback-gallery-img center-block"  />
            </div>
            @error('image')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize">Save {{ substr((Route::currentRouteName()), 0, strpos((Route::currentRouteName()), "."))}} Page</button>
      </div>
    </form>
    @endforeach
  </div>
</section>
@endsection
@push('javascript')
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
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#signup").validate({
    rules: {
      designation: "required",
      f_name: "required",
      m_name: "required",
      l_name: "required",
      uppertype: "required",
      t_designation: "required",
      training: "required",
      qualification: "required",
      dob: "required",
      phone: {
        required: true,
        number:true,
        minlength: 6
      },
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 6
      },
      address: "required",
      gender: "required",
      religion: "required",
      nationality_id: "required",
      marital_status: "required",
      j_date: "required",
      government_id: {
        number:true,
        minlength: 6
      },
      insurance_id: {
        number:true,
        minlength: 6
      },
      pan_id: {
        number:true,
        minlength: 6
      },
      cinvestment_id: {
        number:true,
        minlength: 6
      },
      pfund_id: {
        number:true,
        minlength: 6
      },
      teacher_code: {
        required: true,
        number:true,
        minlength: 6
      },
      image: "required"
    },
    messages: {
      designation: " Designation field is required **",
      f_name: " First Name field is required **",
      m_name: " Middle Name  is required **",
      l_name: " Last Name field is required **",
      uppertype: " Uppertype field is required **",
      t_designation: " Teacher Designation field is required **",
      training: " Training field is required **",
      qualification: "Qualification field is required **",
      dob: "DOB field is required **",
      phone: {
        required: "Please Enter Your Mobile Number",
        number:"Please enter numbers Only",
        minlength: "Your number must consist of at least 6 "
      },
      email: " Enter valid email address **",
      password: {
        required: "Please enter a password",
        minlength: "Your password must consist of at least 6 characters"
      },
      address: " Address field is required **",
      gender: " Gender  is required **",
      religion: " Religion is required **",
      nationality_id: "Nationality is required **",
      marital_status: " Marital status is required **",
      j_date: " Joining Date is required **",
      government_id: {
        number:"Please enter numbers Only",
        minlength: "Your number must consist of at least 6 "
      },
      insurance_id: {
        number:"Please enter numbers Only",
        minlength: "Your number must consist of at least 6 "
      },
      pan_id: {
        number:"Please enter numbers Only",
        minlength: "Your number must consist of at least 6 "
      },
      cinvestment_id: {
        number:"Please enter numbers Only",
        minlength: "Your number must consist of at least 6 "
      },
      pfund_id: {
        number:"Please enter numbers Only",
        minlength: "Your number must consist of at least 6 "
      },
      teacher_code: {
        required: "Please Enter The Teacher Code",
        number:"Please enter numbers Only",
        minlength: "Your number must consist of at least 6 "
      },
      image: " Image is required **"
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
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script>
  $('#j_date').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script>
  $('#p_date').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script>
  $('#dob').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script>
  $(document).ready(function() {
      $('.js-multiple').select2();
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush