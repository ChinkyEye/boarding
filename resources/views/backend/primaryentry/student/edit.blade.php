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
    @foreach($students as $student)
    <form role="form" method="POST" action="{{ route('admin.student.update',$student->id)}}" enctype="multipart/form-data">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md-3">
            <label for="address">First Name</label>
            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter Your Name" autocomplete="off" value="{{$student->getStudentUser->name}}">
            @error('first_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="address">Middle name</label>
            <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="Enter Your Name" maxlength="15" autocomplete="off" value="{{$student->getStudentUser->middle_name}}">
          </div>
          <div class="form-group col-md-3">
            <label for="address">Last name</label>
            <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter Your Name" maxlength="15" autocomplete="off" value="{{$student->getStudentUser->last_name}}">
            @error('last_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="phone_no">Phone number <span class="text-danger">*</span></label>
            <input type="text" name="phone_no" class="form-control" id="phone_no" placeholder="Enter Your Phone number" maxlength="15" autocomplete="off" value="{{ $student->phone_no}}">
          </div>
          <div class="form-group col-md-3">
            <label for="multiple" class="control-label">Shift <span class="text-danger">*</span></label>
            <select class="form-control" name="shift_id" id="shift_data">
              <option value=" ">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}"  @foreach($students as $student) {{$student->shift_id == $shift->id ? 'selected' : ''}} @endforeach> 
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
            <label for="multiple" class="control-label">Class <span class="text-danger">*</span></label>
            <select class="form-control" name="class_id" id="class_data">
              <option value=" ">Select Your Class</option>

             {{--  @foreach ($classes as $key => $class)
              <option value="{{ $class->id }}"  @foreach($students as $student) {{$student->class_id == $class->id ? 'selected' : ''}} @endforeach> 
                {{$class->name}}
              </option>
              @endforeach    --}} 
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
              <option value=" ">Select Your Section</option>
             {{--  @foreach ($sections as $key => $section)
              <option value="{{ $section->id }}"  @foreach($students as $student) {{$student->section_id == $section->id ? 'selected' : ''}} @endforeach> 
                {{$section->name}}
              </option>
              @endforeach   --}}  
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
              {{-- <option value=" ">Select Your Batch</option> --}}
              @foreach ($batchs as $key => $batch)
              <option value="{{ $batch->id }}"  @foreach($students as $student) {{$student->batch_id == $batch->id ? 'selected' : ''}} @endforeach> 
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
          <fieldset class="border border-info container-fluid col-md-12 p-2 mb-2">
            <legend  class="w-auto"><small class="mx-2 text-info">This field generate login system so, be confirm.</small></legend>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="email">Email <span class="text-danger">* Email Cannot be changed</span></label>
                <input type="email" name="email" class="form-control" id="email" autocomplete="off" value="{{ $student->getStudentUser->email }}" disabled>
              </div>
              <div class="form-group col-md-6">
                <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dob" name="dob" autocomplete="off" value="{{ $student->dob }}">
                @error('dob')
                <span class="text-danger font-italic" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
          </fieldset>
          <div class="form-group col-md-4">
            <label for="roll_no">Roll No <span class="text-danger">*</span></label>
            <input type="text" name="roll_no" class="form-control" id="roll_no" autocomplete="off" value="{{$student->roll_no}}">
            @error('roll_no')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror 
          </div>
          <div class="form-group col-md-4">
            <label for="student_address">Address<span class="text-danger">*</span></label>
            <input type="text" name="student_address" class="form-control" id="student_address" autocomplete="off" value="{{$student->address}}">
            @error('student_address')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror 
          </div>
           <div class="form-group col-md-4">
            <label>Gender <span class="text-danger">*</span></label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" value="male" {{$student->gender == 'male' ? 'checked' : ' '}}>
              <label class="form-check-label">Male</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" value="female" {{$student->gender == 'female' ? 'checked' : ' '}}>
              <label class="form-check-label">Female</label>
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="register_id">Register Id <span class="text-danger">*</span></label>
            <input type="text"  class="form-control" id="register_id" name="register_id"  autocomplete="off" value="{{$student->register_id}}" disabled>
          
          </div>
          <div class="form-group col-md-6">
            <label for="register_date">Register Date <span class="text-danger">*</span></label>
            <input type="text"  class="form-control" id="register_date"  name="register_date"  autocomplete="off" value="{{$student->register_date}}">
            @error('register_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-12">
            <label for="document_name">Document type<span class="text-danger">*</span></label>
            <input type="text"  class="form-control max" id="document_name" name="document_name"   autocomplete="off" value="{{$student->document_name}}">
            @error('document_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="imageDoc">Document <span class="text-danger">* required .jpg</span></label>
            <img id="blahDoc" src="{{URL::to('/')}}/images/student/{{$student->slug}}/document/{{$student->document_photo}}" onclick="document.getElementById('imgInpDoc').click();" alt="your image" class="img-thumbnail" style="width: 175px;height: 140px"/>
            <input type='file' class="d-none" id="imgInpDoc" name="doc_file" />
            @error('doc_file')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="image">Student Image <span class="text-danger">* required .jpg</span></label>
            <img id="blah" src="{{URL::to('/')}}/images/student/{{$student->slug}}/{{$student->image}}" onclick="document.getElementById('imgInp').click();" alt="your image" class="img-thumbnail" style="width: 175px;height: 140px"/>
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
          
        <h3>Parent Information</h3>
        <div class="row">
         <div class="form-group col-md-4">
           <label for="father_name">Father Name</label>
           <input type="text"  class="form-control" id="father_name" name="father_name"  autocomplete="off" value="{{$student->student_has_parent_count?$student->Student_has_parent->father_name:''}}">
         </div>
         <div class="form-group col-md-4">
           <label for="mother_name">Mother Name</label>
           <input type="text"  class="form-control" id="mother_name"  name="mother_name"  autocomplete="off" value="{{$student->student_has_parent_count?$student->Student_has_parent->mother_name:''}}">
         </div>
         <div class="form-group col-md-4">
           <label for="contact_no">Contact Number</label>
           <input type="text" class="form-control" id="contact_no" name="contact_no" autocomplete="off" value="{{$student->student_has_parent_count?$student->Student_has_parent->contact_no:''}}">
         </div>
         <div class="form-group col-md-6">
           <label for="address">Address</label>
           <input type="text"  class="form-control" id="address" name="address"   autocomplete="off" value="{{$student->student_has_parent_count?$student->Student_has_parent->address:''}}">
           @error('address')
           <span class="text-danger font-italic" role="alert">
             <strong>{{ $message }}</strong>
           </span>
           @enderror
         </div>
         <div class="form-group col-md-6">
           <label for="multiple" class="control-label col-md-3">Nationality</label>
           <select class="form-control" name="nationality_id">
             <option value="">Select Your Nationality</option>
             @foreach ($nationalities as $key => $nationality)
             <option value="{{ $nationality->id }}"  
               @foreach($students as $student) {{$student->student_has_parent_count?($student->Student_has_parent->nationality_id == $nationality->id ? 'selected' : ''):''}}@endforeach>
               {{$nationality->n_name}}
             </option>
             @endforeach    
           </select>
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
<script type="text/javascript">
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
      $('#idcardShift').val(shift_id);
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getClassList')}}",
      data:{
        _token: token,
        shift_id: shift_id
      },
      success: function(response){
        $('#class_data').html('');
        $('#class_data').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#class_data').append('<option value='+val.get_class.id+'>'+val.get_class.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
        Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    Pace.start();
    var class_id = $('#class_data').val(),
        shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
        $('#idcardClass').val(class_id);
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getSectionList')}}",
      data:{
        _token: token,
        class_id: class_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#section_data').html('');
        $('#section_data').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#section_data').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $(document).ready(function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
       token = $('meta[name="csrf-token"]').attr('content');
       $('#excShift').val(shift_id);
       
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getClassList')}}",
      data:{
        _token: token,
        shift_id: shift_id
      },
      success: function(response){
        $('#class_data').html('');
        // $('#class_data').append('<option value="">--Choose Shift--</option>');
        $.each( response, function( i, val ) {
          if({{ $student_info->class_id == null ? '0' : $student_info->class_id }} == val.id){
            $('#class_data').append('<option value='+val.get_class.id+' selected>'+val.get_class.name+'</option>');
          }else{
            $('#class_data').append('<option value='+val.get_class.id+'>'+val.get_class.name+'</option>');
          }
        });

        var class_id = $("#class_data").val();
       
        $.ajax({
          type:"POST",
          dataType:"JSON",
          url:"{{route('admin.getSectionList')}}",
          data:{
            _token: token,
            shift_id: shift_id,
            class_id: class_id
          },
          success: function(response){
            $('#section_data').html('');
            // $('#section_data').append('<option value="">--Choose Section--</option>');
            // debugger;
            $.each( response, function( i, val ) {
              if({{ $student_info->section_id == null ? '0' : $student_info->section_id }} == val.id){
                $('#section_data').append('<option value='+val.get_section.id+' selected>'+val.get_section.name+'</option>');
              }else{
                $('#section_data').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');

              }
            });

          },
          error: function(event){
            alert("Sorry");
            Pace.stop();
          }
        });
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
@endpush