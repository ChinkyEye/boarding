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
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.routine.store')}}" class="validate" id="validate">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-sm-4">
            <label for="shift_id" class="control-label">Shift</label>
            <select class="form-control" name="shift" id="shift_id">
              <option value="">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}"> 
                {{$shift->name}}
              </option>
              @endforeach
            </select>
            @error('shift')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror 
          </div>
          <div class="form-group col-sm-4">
            <label for="teacher_id" class="control-label">Teacher</label>
            <select class="form-control" name="teacher" id="teacher_id">
              <option value="">Select Your Teacher</option>
            </select>
            @error('teacher')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror  
          </div>
          <div class="form-group col-sm-4">
            <label for="class_id" class="control-label">Class</label>
            <select class="form-control" name="class" id="class_id">
              <option value="">Select Your Class</option>
            </select>
            @error('class')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror 
          </div>
          <div class="form-group col-sm-3">
            <label for="section_id" class="control-label">Section</label>
            <select class="form-control" name="section" id="section_id">
              <option value="">Select Your Section</option>
            </select>
            @error('section')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror 
          </div>
          <div class="form-group col-sm-3">
            <label for="subject_id" class="control-label">Subject</label>
            <select class="form-control" name="subject" id="subject_id">
              <option value="">Select Your Subject</option>
            </select>
            @error('subject')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror  
          </div>
          <div class="form-group col-sm-3">
            <label for="period_id" class="control-label">Period</label>
            <select class="form-control" name="period" id="period_id">
              <option value="">Select Your Period</option>
              @foreach ($periods as $key => $period)
              <option value="{{ $period->id }}"> 
                {{$period->name}} ({{$period->start_time}} - {{$period->end_time}})
              </option>
              @endforeach    
            </select>
            @error('period')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror  
          </div>
          <div class="form-group col-sm-3">
            <label for="day_id" class="control-label">Day</label>
            <select class="form-control" name="day" id="day_id">
              <option value="0">Sunday</option>
              <option value="1">Monday</option>
              <option value="2">Tuesday</option>
              <option value="3">Wednesday</option>
              <option value="4">Thursday</option>
              <option value="5">Friday</option>
              <option value="6">Saturday</option>
            </select>
            @error('period')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror  
          </div>
        </div>
      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Save Shift">Save</button>
      </div>
    </form>
  </div>
  <div class="table-responsive" id="replaceTable">
    <table class="table table-bordered table-hover">
      
    </table>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript">
  $("body").on("change","#shift_id", function(event){
    Pace.start();
    var shift_id = $('#shift_id').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getShiftRTeacherList')}}",
      data:{
        _token: token,
        shift_id: shift_id
      },
      success: function(response){
        $('#teacher_id').html('');
        $('#teacher_id').append('<option value="">--Choose Teacher--</option>');
        $.each( response, function( i, val ) {
          if (val.teacher.middle_name == null) { 
            val.teacher.middle_name = '';
          } 
          $('#teacher_id').append('<option value='+val.teacher.id+'>'+val.teacher.name+' '+val.teacher.middle_name+' '+val.teacher.last_name+'</option>');
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
  $("body").on("change","#teacher_id", function(event){
    Pace.start();
    var teacher_id = $("#teacher_id").val(),
        shift_id = $("#shift_id").val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getShiftRClassList')}}",
      data:{
        _token: token,
        teacher_id: teacher_id,
        shift_id: shift_id
      },
      success: function(response){
        $('#class_id').html('');
        $('#class_id').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#class_id').append('<option value='+val.get_class.id+'>'+val.get_class.name+'</option>');
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
  $("body").on("change","#teacher_id", function(event){
    Pace.start();
    var teacher_id = $("#teacher_id").val(),
        shift_id = $("#shift_id").val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"html",
      url:"{{route('admin.getTeacherRoutineList')}}",
      data:{
        _token: token,
        teacher_id: teacher_id,
        shift_id: shift_id
      },
      success: function(response){
        $('#replaceTable').html(response);
      },
      error: function(event){
        console.log(this);
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_id", function(event){
    Pace.start();
    var teacher_id = $("#teacher_id").val(),
        shift_id = $("#shift_id").val(),
        class_id = $("#class_id").val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getShiftRSectionList')}}",
      data:{
        _token: token,
        teacher_id: teacher_id,
        shift_id: shift_id,
        class_id: class_id
      },
      success: function(response){
        $('#section_id').html('');
        $('#section_id').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#section_id').append('<option value='+val.section.id+'>'+val.section.name+'</option>');
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
  $("body").on("change","#section_id", function(event){
    Pace.start();
    var teacher_id = $("#teacher_id").val(),
        shift_id = $("#shift_id").val(),
        class_id = $("#class_id").val(),
        section_id = $("#section_id").val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getShiftRSubjectList')}}",
      data:{
        _token: token,
        teacher_id: teacher_id,
        shift_id: shift_id,
        class_id: class_id,
        section_id: section_id
      },
      success: function(response){
        $('#subject_id').html('');
        $('#subject_id').append('<option value="">--Choose Subject--</option>');
        $.each( response, function( i, val ) {
          if (val.get_subject.theory_practical == 1){
            this.th_pr = '(Th)';
          }else if (val.get_subject.theory_practical == 2){
            this.th_pr = '(Pr)';
          }else{
            this.th_pr = '(Both)';
          }
          $('#subject_id').append('<option value='+val.get_subject.id+'>'+val.get_subject.name+this.th_pr+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
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
</script>
@endpush