@extends('library.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 8, strpos(str_replace('library.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize"></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('library.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('library.issuebook.store')}}" class="signup" id="signup">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Shift</label>
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
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Class</label>
            <select class="form-control" name="class_id" id="class_data">
              <option value="">Select Your Class</option>
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror    
          </div>
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Section</label>
              <select class="form-control" name="section_id" id="section_data">
              <option value="">Select Your Section</option>
            </select>
            @error('section_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror    
          </div>
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label col-md-3">Book</label>
            <select class="form-control" name="book_id" id="book_data">
              <option value=" ">Select Your Book</option>
              
            </select>
            @error('book_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror    
          </div>
          <div class="form-group col-md-4">
            <label class="control-label">Student Name</label>
            <select class="form-control" name="student_id" id="student_data">
              <option value="">Select Your Name</option>
            </select>
            @error('student_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror    
          </div>
          <div class="form-group col-md-4">
            <label for="issue_date">Issue Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="issue_date" name="issue_date" autocomplete="off" value="{{ old('issue_date') }}">
            @error('issue_date')
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
  <div class="table-responsive" id="replaceTable"></div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#issue_date').val(currentDate);
    $('#issue_date').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
    });
  });
</script>
<script type="text/javascript">
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('library.getClassList')}}",
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
        alert("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    var class_id = $('#class_data').val(),
        shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('library.getSectionList')}}",
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
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    var class_id = $('#class_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('library.getBookList')}}",
      data:{
        _token: token,
        class_id: class_id,
      },
      success: function(response){
        $('#book_data').html('');
        $('#book_data').append('<option value="">--Choose Book--</option>');
        $.each( response, function( i, val ) {
          $('#book_data').append('<option value='+val.id+'>'+val.name+ ' ('+val.get_subject.name +')'+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
  });
</script>
<script type="text/javascript">
  $("body").on("change","#section_data", function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
        class_id = $('#class_data').val(),
        section_id = $('#section_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('library.getStudentList')}}",
      data:{
        _token: token,
        shift_id: shift_id,
        class_id: class_id,
        section_id: section_id
      },
      success: function(response){
      
        $('#student_data').html('');
        $('#student_data').append('<option value="">--Choose Student--</option>');
        $.each( response, function( i, val ) {
          if (val.get_student_user.middle_name == null) { 
            val.get_student_user.middle_name = '';
          } 

          $('#student_data').append('<option value='+val.id+'>'+val.get_student_user.name +' '+val.get_student_user.middle_name +' '+val.get_student_user.last_name+' ('+val.student_code+')'+'</option>');
        });
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#student_data", function(event){
    Pace.start();
    var student_id = $('#student_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"html",
      url:"{{route('library.getStudentIssueList')}}",
      data:{
        _token: token,
        student_id: student_id,
      },
      success: function(response){
        $('#replaceTable').html("");
        $('#replaceTable').html(response);
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script>
@endpush