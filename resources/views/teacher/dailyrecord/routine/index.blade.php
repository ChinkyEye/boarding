@extends('teacher.main.app')
@section('content')
<?php $page = "Routine"; ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize"></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('teacher.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-body">
          <div class="row pb-3">
            <div class="col">
              <select class="form-control" name="shift_id" id="shift_data">
                <option value="">Select Your Shift</option>
                @foreach ($shifts as $key => $shift)
                <option value="{{ $shift->shift_id }}" {{ old('shift_id') == $shift->shift_id ? 'selected' : ''}}> 
                  {{$shift->getTeacherShift->name}}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col">
              <select class="form-control class_data" name="class_id" id="class_data">
                <option value="">Select Your Class</option>

              </select>
            </div>
            <div class="col">
              <select class="form-control section_data" name="section_id" id="section_data">
                <option value="">Select Your Section</option>

              </select>
            </div>
            <div class="col">
              <select class="form-control" name="day" id="day_data">
                <option value="0">Sunday</option>
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thursday</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>
              </select>
            </div>
            <div class="col-1">
              <div class="form-group" align="center">
                <button type="button" class="btn btn-info" id="routineSearch">Search</button>
              </div>
            </div>
          </div>
          <div class="table-responsive" id="replaceTable">
            <table class="table table-bordered table-hover">
              <thead class="bg-dark">
                <tr class="text-center">
                  <th width="10">SN</th>
                  <th class="text-left">Period</th>
                  <th class="text-left">Class</th>
                  <th class="text-left">Subject</th>
                  <th width="150">Created By</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($routines as $index => $element)
                  <tr class="text-center">
                    <td width="10">{{$index+1}}</td>
                    <td class="text-left">{{$element->getPeriod->name}} <span class="badge badge-info">{{$element->getPeriod->start_time}} to {{$element->getPeriod->end_time}}</span></td>
                    <td class="text-left">{{$element->getShift->name}} | {{$element->getClass->name}} | {{$element->getSection->name}}</td>
                    <td>
                      {{$element->getTeacherSubjectList->getSubject->name}}
                      ({{$element->getTeacherSubjectList->getSubject->theory_practical == 1 ? 'Th' : 'Pr'}})
                    </td>
                    <td width="150">{{$element->getUser->name}}</td>
                  </tr>
                @endforeach
              </tbody>              
            </table>
          </div>
      </div>
  </div>
</section>
@endsection
@push('javascript')
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
  $("body").on("click", "#routineSearch", function(event){
      Pace.start();
      var token = $('meta[name="csrf-token"]').attr('content'),
      shift_data = $('#shift_data').val(),
      class_data = $('#class_data').val(),
      section_data = $('#section_data').val(),
      date_data = parseInt($('#day_data').val());
      $.ajax({
        type:"POST",
        dataType:"html",
        url: "{{URL::route('teacher.getRoutineRecord')}}",
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
</script>
@endpush