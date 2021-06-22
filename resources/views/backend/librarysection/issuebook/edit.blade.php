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
    <form role="form" method="POST" action="{{ route('admin.issuebook.update',$issuebooks->id)}}" class="signup" id="signup">
      <div class="card-body">
        @method('PATCH')
        @csrf
         <div class="row">
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Shift</label>
             <span> : {{$issuebooks->getShift->name}}</span>
          </div>
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Class</label>
            <span> : {{$issuebooks->getClass->name}}</span>
          </div>
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Section</label>
            <span> : {{$issuebooks->getSection->name}}</span>
          </div>
          <div class="form-group col-md-6">
            <label for="multiple" class="control-label col-md-3">Book</label>
            <select class="form-control" name="book_id" id="book_data">
              <option value=" ">Select Your Book</option>
              @foreach ($books as $key => $book)
              <option  value="{{ $book->id }}" {{$issuebooks->book_id == $book->id ? 'selected' : ''}}> 
                {{$book->name}} 
              </option>
              @endforeach
            </select>
            @error('book_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror    
          </div>
          <div class="form-group col-md-6">
            <label class="control-label">Student Name</label>
            <select class="form-control" name="student_id" id="student_data">
              <option value=" ">Select Your Name</option>
              @foreach ($students as $key => $student)
              <option  value="{{ $student->id }}" {{$issuebooks->student_id == $student->id ? 'selected' : ''}} > 
                {{$student->getStudentUser->name.' '.$student->getStudentUser->middle_name.''.$student->getStudentUser->last_name}} 
              </option>
              @endforeach
            </select>
            @error('student_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror    
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Update Shift">Update</button>
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
      class_id: "required"
      section_id: "required"
      shift_id: "required"
      subject_id: "required"
      description: "required"
    },
    messages: {
      class_id: " class field is required **"
      section_id: " Section field is required **"
      shift_id: " shift field is required **"
      subject_id: " subject field is required **"
      description: " description field is required **"
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