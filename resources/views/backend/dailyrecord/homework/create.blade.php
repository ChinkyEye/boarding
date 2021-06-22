@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize"></h1>
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
    <form role="form" method="POST" action="{{route('admin.homework.store')}}" class="signup" id="signup">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-sm-4">
            <label for="shift_id" class="control-label">Shift</label>
            <select class="form-control" name="shift_id" id="shift_id">
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
          <div class="form-group col-sm-4">
            <label for="teacher_id" class="control-label">Teacher</label>
            <select class="form-control" name="teacher_id" id="teacher_id">
              <option value="" >Select Your Teacher</option>
            </select>
            @error('teacher_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror  
          </div>
          <div class="form-group col-sm-4">
            <label for="class_id" class="control-label">Class</label>
            <select class="form-control" name="class_id" id="class_id">
              <option value="">Select Your Class</option>
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror 
          </div>
          <div class="form-group col-sm-4">
            <label for="section_id" class="control-label">Section</label>
            <select class="form-control" name="section_id" id="section_id">
              <option value="">Select Your Section</option>
            </select>
            @error('section_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror 
          </div>
          <div class="form-group col-sm-4">
            <label for="subject_id" class="control-label">Subject</label>
            <select class="form-control" name="subject_id" id="subject_id">
              <option value="">Select Your Subject</option>
            </select>
            @error('subject_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror  
          </div>
          <div class="form-group col-sm-4">
            <label for="date">Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="date" name="date" autocomplete="off" value="{{ old('date') }}">
            @error('date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-12">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
            @error('description')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
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
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  $('#date').val(currentDate);
  $('#date').nepaliDatePicker({
    disableAfter: currentDate,
    });
  });
</script>
<script>
  $(function () {
    CKEDITOR.replace('description');
    // CKEDITOR.config.autoParagraph = false;
    CKEDITOR.config.removeButtons = 'Anchor';
    CKEDITOR.config.removePlugins = 'stylescombo,link,sourcearea,maximize,image,about,tabletools,scayt';
  });
</script>
{{-- <script>
  $("#signup").validate({
    rules: {
      class_id : "required",
      section_id : "required",
      shift_id : "required",
      subject_id : "required",
      description: "required",
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
</script> --}}
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
        Pace.stop();
      }
    });
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
            this.th_pr = 'Th';
          }else if (val.get_subject.theory_practical == 2){
            this.th_pr = 'Pr';
          }else{
            this.th_pr = 'Both';
          }
          $('#subject_id').append('<option value='+val.get_subject.id+'>'+val.get_subject.name+'('+this.th_pr+')</option>');
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