@extends('teacher.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">Homework Edit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('teacher.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">Homework</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('teacher.homework.update',$homeworks->id)}}" class="signup" id="signup">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md">
            <label for="shift_data" class="control-label">Shift</label>
            <span> : {{$homeworks->getShift->name}}</span>
          </div>
          <div class="form-group col-md">
            <label for="class_data" class="control-label">Class</label>
            <span> : {{$homeworks->getClass->name}}</span>
          </div>
          <div class="form-group col-md">
            <label for="section_data" class="control-label">Section</label>
            <span> : {{$homeworks->getSection->name}}</span>
          </div>
          
          <div class="form-group col-md-12">
            <label for="subject_data" class="control-label">Subject</label>
            <select class="form-control" name="subject_id" id="subject_data">
              <option value=" ">Select Your Subject</option>
              @foreach ($subjects as $key => $subject)
              <option value="{{ $subject->subject_id }}" {{ $subject->subject_id == $homeworks->subject_id ? 'selected' : ''}}> 
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
          <div class="form-group col-md-12">
            <label for="date">Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="date" name="date" autocomplete="off" value="{{ $homeworks->date }}">
            {{-- <input type="date" class="form-control" name="date" autocomplete="off" value="{{ old('date') }}"> --}}
            @error('date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-12">
            <label for="name">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $homeworks->description }}</textarea>
            @error('description')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize">Update</button>
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
  // $('#date').val(currentDate);
  $('#date').nepaliDatePicker({
    disableAfter: currentDate,
    });
  });
</script>

<script>
  $(function () {
    CKEDITOR.replace('description');
    CKEDITOR.config.autoParagraph = true;
    CKEDITOR.config.removeButtons = 'Anchor';
    CKEDITOR.config.removePlugins = 'format,stylescombo,link,sourcearea,maximize,image,about,tabletools,scayt';
  });
</script>
<script>
  $("#signup").validate({
    rules: {
      class_id : "required",
      section_id : "required",
      shift_id : "required",
      subject_id : "required",
      description: "required",
    },
    messages: {
      class_id: " class field is required **",
      section_id: " Section field is required **",
      shift_id: " shift field is required **",
      subject_id: " subject field is required **",
      description: " description field is required **",
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
</script>
<script type="text/javascript">
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var data_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.tgetClassList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
        $('#class_data').html('');
        $('#class_data').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#class_data').append('<option value='+val.sclass.id+'>'+val.sclass.name+'</option>');
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
    Pace.start();
    var data_id = $('#class_data').val(),
        shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.tgetSectionList')}}",
      data:{
        _token: token,
        data_id: data_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#section_data').html('');
        $('#section_data').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#section_data').append('<option value='+val.section.id+'>'+val.section.name+'</option>');
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
    Pace.start();
    var data_id = $('#class_data').val(),
        token = $('meta[name="csrf-token"]').attr('content'),
        th_pr = "";
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.tgetSubjectList')}}",
      data:{
        _token: token,
        data_id: data_id,
      },
      success: function(response){
        $('#subject_data').html('');
        $('#subject_data').append('<option value="">--Choose Subject--</option>');
        $.each(response, function(name) {
          $.each(this, function(i, vals) {
            $.each(vals.get_teacher_subject, function(j, val) {
              if(val.get_subject.theory_practical == 1){
                this.th_pr = "Th";
              } else if (val.get_subject.theory_practical == 2){
                this.th_pr = "Pr";
              } else {
                this.th_pr = "Both";
              }
              $('#'+name+'_data').append('<option value='+val.get_subject.id+'>'+val.get_subject.name+'('+this.th_pr+')</option>');
              
            });
          });
        });
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script>
@endpush