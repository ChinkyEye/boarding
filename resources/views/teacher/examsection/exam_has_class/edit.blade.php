@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
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
    @foreach($examhasclasses as $exam)
    <form role="form" method="POST" action="{{ route('admin.examhasclass.update',$exam->id)}}">
      <div class="card-body">
        @method('PATCH')
        @csrf
         <div class="row">
          <div class="form-group col-sm-4">
            <label for="multiple" class="control-label"> Select Shift</label>
            <select class="form-control" name="shift_id" id="filter_shift">
              <option value=" ">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" {{ $shift->id == $exam->shift_id ? 'selected' : ''}}> 
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
            <label for="multiple" class="control-label"> Select Class</label>
            <select class="form-control" name="class_id" id="filter_class">
              <option value=" ">Select Your Class</option>
              @foreach($shifts as $shift_data)
              @foreach($shift_data->getShiftList()->get() as $class_shift)
              <option value="{{$class_shift->id}}" {{ $class_shift->class_id == $exam->class_id ? 'selected' : ''}}>{{$class_shift->getClass->name}}</option>
              @endforeach
              @endforeach
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="multiple" class="control-label"> Select Section</label>
            <select class="form-control" name="section_id" id="filter_section">
              <option value=" ">Select Your Section</option>
              @foreach($shifts as $shift_sec)
                @foreach($shift_sec->getShiftList()->where('class_id',$exam->class_id )->get() as $class_shift_sec)
                  @foreach($class_shift_sec->getClassSection()->get() as $class_sec)
                  <option value="{{$class_sec->section_id}}" {{ $class_sec->section_id == $exam->section_id ? 'selected' : ''}}>{{$class_sec->getSection->name}}</option>
                  @endforeach
                @endforeach
              @endforeach
            </select>
            @error('section_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="bootstrap-timepicker col-sm-4">
            <div class="form-group">
              <label for="start_time">Start Time:</label>
              <div class="input-group date" id="start_time" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#start_time" name="start_time"  value="{{ $exam->start_time }}">
                <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
              @error('start_time')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
          <div class="bootstrap-timepicker col-sm-4">
            <div class="form-group">
              <label for="end_time">End Time:</label>
              <div class="input-group date" id="end_time" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#end_time" name="end_time" value="{{ $exam->end_time }}">
                <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
              @error('end_time')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
          <div class="form-group col-md-4">
            <label for="result_date">Result Date<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="result_date" name="result_date" autocomplete="off" value="{{ $exam->result_date }}">
            @error('result_date')
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
    @endforeach
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/moment.min.js"></script>
<script src="{{URL::to('/')}}/backend/js/tempusdominus-bootstrap-4.js"></script>
<script>
    $('#start_time').datetimepicker({
      format: 'LT'
    })
</script>
<script>
    $('#end_time').datetimepicker({
      format: 'LT'
    })
</script>
<script>
  $('#result_date').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      shift_id: "required",
      class_id: "required",
      section_id: "required",
      start_time: "required",
      end_time: "required",
      result_date: "required",
    },
    messages: {
      shift_id: " shift_id field is required **",
      class_id: " class_id field is required **",
      section_id: " section_id field is required **",
      start_time: " start_time field is required **",
      end_time: " end_time field is required **",
      result_date: " result_date field is required **",
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
  $("body").on("change","#filter_shift", function(event){
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
        $('#filter_class').html('');
        $('#filter_class').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#filter_class').append('<option value='+val.id+'>'+val.name+'</option>');
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
  $("body").on("change","#filter_class", function(event){
    var data_id = $(event.target).val(),
        shift_id = $('#filter_shift').val(),
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
        $('#filter_section').html('');
        $('#filter_section').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#filter_section').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
  });
</script>
@endpush