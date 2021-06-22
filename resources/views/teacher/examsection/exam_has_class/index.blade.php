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
      <div class="col-sm-6">
         @if($exams)<h1 class="text-capitalize">You are managing {{ $exams }}</h1>@endif 
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}  Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.examhasclass.store')}}"  class="validate" id="validate">
     @csrf
     <input type="hidden" name="exam_id" value="{{$exam_id}}">
     <div class="modal-body" >
      <div class="row">
        <div class="form-group col-sm-4">
          <label for="multiple" class="control-label"> Select Shift</label>
          <select class="form-control" name="shift_id" id="filter_shift">
            <option value=" ">Select Your Shift</option>
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
          <label for="multiple" class="control-label"> Select Class</label>
          <select class="form-control" name="class_id" id="filter_class">
            <option value=" ">Select Your Class</option>
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
              <input type="text" class="form-control datetimepicker-input" data-target="#start_time" name="start_time" value="{{ old('start_time') }}">
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
              <input type="text" class="form-control datetimepicker-input" data-target="#end_time" name="end_time" value="{{ old('end_time') }}">
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
          <input type="text" class="form-control" id="result_date" name="result_date" autocomplete="off" value="{{ old('result_date') }}">
          @error('result_date')
          <span class="text-danger font-italic" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>
    <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Save">Save</button>
  </div>
</form>
  </div>
   <div class="table-responsive">
     <table class="table table-striped table-bordered table-hover">
       <thead class="bg-dark">
         <tr class="text-center">
           <th width="10">SN</th>
           <th class="text-left">Class</th>
           <th width="150">Shift</th>
           <th width="150">Section</th>
           <th width="150">Exam</th>
           <th width="150">Start</th>
           <th width="150">End</th>
           <th width="150">Result</th>
           <th width="150">Created By</th>
           <th width="150">Status</th>
           <th width="150">Action</th>
         </tr>
       </thead>
       @foreach($examhasclasses as $key=>$examhasclass)
       <tr data-toggle="tooltip" data-placement="top" title="{{ $examhasclass->is_active == '1' ? 'This data is published':' This data is not published'}}"   style="background-color: {{$examhasclass->is_active == '1' ? '#cde0ba' : '#eccdcd'}}">
         <td>{{$key+1}}</td>
         <td>{{$examhasclass->getClass->name}}</td>
         <td>{{$examhasclass->getShift->name}}</td>
         <td>{{$examhasclass->getSection->name}}</td>
         <td>{{$examhasclass->getExam->name}}</td>
         <td><span class="badge badge-success">{{ $examhasclass->start_time }}</span></td>
         <td><span class="badge badge-danger">{{ $examhasclass->end_time }}</span></td>
         <td>{{$examhasclass->result_date}}</td>
         <td>{{$examhasclass->getUser->name}}</td>
         <td class="text-center">
           <a href="{{ route('admin.examhasclass.active',$examhasclass->id) }}" data-toggle="tooltip" data-placement="top" title="{{ $examhasclass->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
             <i class="fa {{ $examhasclass->is_active == '1' ? 'fa-check check-css':'fa-times cross-css'}}"></i>
           </a>
         </td>
         <td class="text-center">
          <a href="{{ route('admin.examhasclass.export',$examhasclass->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Prinnt Admit Card"><i class="fas fa-print"></i></a>
          <a href="{{ route('admin.classhasmark',[$slug,$examhasclass->getClass->slug]) }}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="Show Detail"><i class="fas fa-plus"></i></a>
           <a href="{{ route('admin.examhasclass.edit',$examhasclass->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a>
           <form action="{{ route('admin.examhasclass.destroy',$examhasclass->id) }}" method="post" class="d-inline-block" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
             @csrf
             <input name="_method" type="hidden" value="DELETE">
             <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
           </form>
         </td>
      </tr>
      @endforeach              
    </table>
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
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script>
  $('#result_date').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
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