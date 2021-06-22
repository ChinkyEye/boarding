@extends('backend.main.app')
@section('content')
<?php $page = "Edit Routine"; ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page}}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{URL::to('/')}}/home">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page}}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    {{-- {{dd($shifts , $routines)}} --}}
    <form role="form" method="POST" action="{{route('admin.routine.update',$routines->id)}}" class="validate" id="validate">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md">
            <label for="shift_id" class="control-label">Shift</label>
            <span> : {{$routines->getShift->name}}</span>
          </div>
          <div class="form-group col-md">
            <label for="teacher_id" class="control-label">Teacher</label>
            <span> : {{$routines->getTeacherName->name}} {{$routines->getTeacherName->middle_name}} {{$routines->getTeacherName->last_name}}</span>
          </div>
          <div class="form-group col-md">
            <label for="class_id" class="control-label">Class</label>
            <span>: {{$routines->getClass->name}}</span>
          </div>
          <div class="form-group col-md">
            <label for="section_id" class="control-label">Section</label>
            <span>: {{$routines->getSection->name}}</span>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md">
            <label for="subject_id" class="control-label">Subject</label>
            <select class="form-control" name="subject_id" id="subject_id">
              <option value="">Select Your Subject</option>
              @foreach ($subjects as $key => $subject)
              <option value="{{ $subject->id }}" {{ $subject->id == $routines->teacher_subject_id ? 'selected' : ''}}> 
                {{$subject->getSubject->name}} {{$subject->getSubject->theory_practical == 1 ? '(Th)' : '(Pr)'}}
              </option>
              @endforeach
            </select>
            @error('subject_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror  
          </div>
          <div class="form-group col-md">
            <label for="period_id" class="control-label">Period</label>
            <select class="form-control" name="period_id" id="period_id">
              <option value="">Select Your Period</option>
              @foreach ($periods as $key => $period)
              <option value="{{ $period->id }}" {{ $period->id == $routines->period_id ? 'selected' : ''}}> 
                {{$period->name}}
              </option>
              @endforeach
            </select>
            @error('period_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror  
          </div>
          <div class="form-group col-md">
            <label for="day_id" class="control-label">Day</label>
            <select class="form-control" name="day_id" id="day_id">
              <option value="0" {{ $routines->day_id  == 0 ? 'selected' : ''}}>Sunday</option>
              <option value="1" {{ $routines->day_id  == 1 ? 'selected' : ''}}>Monday</option>
              <option value="2" {{ $routines->day_id  == 2 ? 'selected' : ''}}>Tuesday</option>
              <option value="3" {{ $routines->day_id  == 3 ? 'selected' : ''}}>Wednesday</option>
              <option value="4" {{ $routines->day_id  == 4 ? 'selected' : ''}}>Thursday</option>
              <option value="5" {{ $routines->day_id  == 5 ? 'selected' : ''}}>Friday</option>
              <option value="6" {{ $routines->day_id  == 6 ? 'selected' : ''}}>Saturday</option>
            </select>
            @error('day_id')
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
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
{{-- <script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      class_id: "required",
      shift_id: "required",
      section_id: "required",
      teacher_id: "required",
      period_id: "required"
    },
    messages: {
      class_id: " class_id field is required **",
      shift_id: " shift_id field is required **",
      section_id: " section_id field is required **",
      teacher_id: " teacher_id field is required **",
      period_id: " period_id field is required **"
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
</script> --}}
@endpush