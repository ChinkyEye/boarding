@extends('backend.main.app')
@push('style')
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
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
  <div class="card">
    <div class="card-header">
      <a href="{{ route('admin.homework.create')}}" class="btn btn-sm btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Add Homework">Add {{ $page }} </a>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-header">
      <div class="row">
        <div class="col-md">
          <select class="form-control" name="shift_id" id="shift_data">
            <option value="">Select Your Shift</option>
            @foreach ($shifts as $key => $shift)
            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
              {{$shift->name}}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-md">
          <select class="form-control" name="class_id" id="class_data">
            <option value="">Select Your Class</option>
          </select>
        </div>
        <div class="col-md">
          <select class="form-control" name="section_id" id="section_data">
            <option value="">Select Your Section</option>
          </select>
        </div>
        {{-- <div class="col-md">
          <input type="date" name="date" class="form-control" id="date_data" value="{{date("Y-m-d")}}" placeholder="YYYY-MM-DD" max="{{date("Y-m-d")}}">
        </div> --}}
        <div class="col-md">
          {{-- <label for="date">Date of Birth <span class="text-danger">*</span></label> --}}
          <input type="text" class="form-control" id="date_data" name="date" autocomplete="off" value="{{ old('date') }}" placeholder="YYYY-MM-DD">
        </div>
        <div class="col-md-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-info" id="homeworkSearch">Search</button>
            <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body p-0" id="replaceTable">
    @if($homeworks->isNotEmpty())
      <div class="card-body table-responsive">
        <table class="table table-bordered table-hover position-relative w-100">
          <thead class="bg-dark">
            <tr class="text-center">
              <th width="10">SN</th>
              <th class="text-left">Info</th>
              <th class="text-left">Subject</th>
              <th class="text-left">Teacher</th>
              <th width="150">Created By</th>
              <th width="10">Status</th>
              <th width="150">Action</th>
            </tr>
          </thead>              
          <tbody>
            @foreach ($homeworks as $index => $element)
              <tr class="text-center {{$element->is_active == 1 ? '' : 'bg-light-danger'}}">
                <td>{{$index+1}}</td>
                <td class="text-left">{{$element->getShift->name}} | {{$element->getClass->name}} | {{$element->getSection->name}}<span class="badge badge-info float-sm-right">{{$element->date}}</span></td>
                <td class="text-left">
                  {{$element->getSubject->name}}
                  ({{$element->getSubject->theory_practical == 1 ? 'Th' : 'Pr'}})
                </td>
                <td>{{$element->getTeacherName->name}} {{$element->getTeacherName->middle_name}} {{$element->getTeacherName->last_name}}</td>
                <td class="text-left">{{$element->getUser->name}}</td>
                <td>
                  <a class="d-block text-center" href="{{ route('admin.homework.active',$element->id) }}" data-toggle="tooltip" data-placement="top" title="{{$element->is_active == 1 ? 'Click to deactivate' : 'Click to activate'}}">
                  <i class="fa {{$element->is_active == 1 ? 'fa-check check-css' : 'fa-times cross-css'}}"></i>
                  </a>
                </td>
                <td>
                 {{--  <a href="{{route('admin.homework.show',$element->id)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail" target="_blank"><i class="fa fa-eye"></i></a> --}}
                  <a href="{{route('admin.homework.edit',$element->id)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a> 
                  <form action="{{route('admin.homework.destroy',$element->id)}}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-xs btn-outline-danger" type="submit" id="submit"><i class="fa fa-trash"></i></button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  $('#date_data').val(currentDate);
  $('#date_data').nepaliDatePicker({
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
  });
</script>
<script type="text/javascript">
  $("body").on("click", "#homeworkSearch", function(event){
      Pace.start();
      var token = $('meta[name="csrf-token"]').attr('content'),
      shift_data = $('#shift_data').val(),
      class_data = $('#class_data').val(),
      section_data = $('#section_data').val(),
      date_data = $('#date_data').val();
      $.ajax({
        type:"POST",
        dataType:"html",
        url: "{{URL::route('admin.getHomeworkRecord')}}",
        data: {
          _token: token,
          shift_data: shift_data,
          class_data: class_data,
          section_data: section_data,
          date_data: date_data,
        },
        success:function(response){
          $('#replaceTable').html(response);
        },
        error: function (e) {
          alert('Sorry! we cannot load data this time');
          return false;
        }
      });
      Pace.stop();
  });
  $('#reset').click(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#shift_data').val('');
    $('#class_data').val('');
    $('#section_data').val('');
    $('#date_data').val(currentDate);
  });
</script>
@endpush