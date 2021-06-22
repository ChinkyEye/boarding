@extends('backend.main.app')
@section('content')
<section class="content-header">
  
</section>
<section class="content">
  <div class="card">
    @foreach($students as $student)
    <form role="form" >
      <div class="card-body">
        <div class="row">
          <div class="form-group col-md-4">
            <label for="address">First Name</label>
            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter Your Name" autocomplete="off" value="{{$student->getStudentUser->name}}">
            @error('first_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="address">Middle name</label>
            <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="Enter Your Name" maxlength="15" autocomplete="off" value="{{$student->getStudentUser->middle_name}}">
          </div>
          <div class="form-group col-md-4">
            <label for="address">Last name</label>
            <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter Your Name" maxlength="15" autocomplete="off" value="{{$student->getStudentUser->last_name}}">
            @error('last_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="roll_no">Roll No</label>
            <input type="text" name="roll_no" class="form-control" id="roll_no" autocomplete="off" value="{{$student->roll_no}}">
            @error('roll_no')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror 
          </div>
          <div class="form-group col-md-3">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" autocomplete="off" value="{{$student->getStudentUser->email}}">
            @error('email')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="dob">Date of Birth</label>
            <input type="text"  class="form-control" id="dob" name="dob"   autocomplete="off" value="{{$student->dob}}">
            @error('dob')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
           <div class="form-group col-md-3">
            <label>Gender</label>
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
            <label for="register_id">Register Id</label>
            <input type="text"  class="form-control" id="register_id" name="register_id"  autocomplete="off" value="{{$student->register_id}}">
            @error('register_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="register_date">Register Date</label>
            <input type="text"  class="form-control" id="register_date"  name="register_date"  autocomplete="off" value="{{$student->register_date}}">
            @error('register_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="multiple" class="control-label">Shift</label>
            <select class="form-control" name="shift_id">
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
            <label for="multiple" class="control-label">Class</label>
            <select class="form-control" name="class_id">
              <option value=" ">Select Your Class</option>
              @foreach ($classes as $key => $class)
              <option value="{{ $class->id }}"  @foreach($students as $student) {{$student->class_id == $class->id ? 'selected' : ''}} @endforeach> 
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
            <label for="multiple" class="control-label">Section</label>
            <select class="form-control" name="section_id">
              <option value=" ">Select Your Section</option>
              @foreach ($sections as $key => $section)
              <option value="{{ $section->id }}"  @foreach($students as $student) {{$student->section_id == $section->id ? 'selected' : ''}} @endforeach> 
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
            <label for="multiple" class="control-label">Batch</label>
            <select class="form-control" name="batch_id">
              <option value=" ">Select Your Batch</option>
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
          <div class="form-group col-md-12">
            <label for="document_name">Document Name</label>
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
            <label for="image">Student Image <span class="text-danger">* requried .jpg</span></label>
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
          <div class="form-group col-md-6">
            <label for="father_name">Father Name</label>
            <input type="text"  class="form-control" id="father_name" name="father_name"  autocomplete="off" value="{{$student->student_has_parent_count?$student->Student_has_parent->father_name:''}}">
            @error('father_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="mother_name">Mother Name</label>
            <input type="text"  class="form-control" id="mother_name"  name="mother_name"  autocomplete="off" value="{{$student->student_has_parent_count?$student->Student_has_parent->mother_name:''}}">
            @error('mother_name')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
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
              <option value="{{ $nationality->id }}"  @foreach($students as $student) {{$student->student_has_parent_count?($student->Student_has_parent->nationality_id == $nationality->id ? 'selected' : ''):''}}  @endforeach> 
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
    </form>
    @endforeach
  </div>
</section>
@endsection
@push('javascript')
@endpush