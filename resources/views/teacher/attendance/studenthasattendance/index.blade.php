@extends('teacher.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<?php $page = request()->segment(count(request()->segments())) ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
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
    <div class="card-header pb-0">
      <div class="row">
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
          <select class="form-control" name="class_id" id="class_data">
            <option value="">Select Your Class</option>

          </select>
        </div>
        <div class="col">
          <select class="form-control" name="section_id" id="section_data">
            <option value="">Select Your Section</option>
          </select>
        </div>
        <div class="col">
          {{-- <input type="date" name="date" class="form-control" id="date_data" value="<?php echo date("Y-m-d"); ?>" placeholder="YYYY-MM-DD" max="{{date("Y-m-d")}}"> --}}
          <input type="text" class="form-control ndp-nepali-calendar" id="date_data" name="date_data" autocomplete="off" value="{{ old('date') }}" placeholder="YYYY-MM-DD">
        </div>
        <div class="col-1">
          <div class="form-group" align="center">
            <button type="button" name="search" id="searchAttendance" class="btn btn-info">Search</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <form role="form" @if($check_attendence == '0') method="POST" action="{{ route('teacher.student-attendance.store')}}" id="myform" @endif>
        @csrf
        <div id="replaceTable">
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(event){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    // for show
    $('#date_data').val(currentDate);
    $('#date_data').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
      onChange: function(event) {
        var date = $('#date_data').val();
      }
    });
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
        alert("Sorry");
      }
    });
        Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
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
        alert("Sorry");
      }
    });
        Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("click", "#searchAttendance", function(event){
      Pace.start();
      var token = $('meta[name="csrf-token"]').attr('content'),
      shift_data = $('#shift_data').val(),
      class_data = $('#class_data').val(),
      section_data = $('#section_data').val(),
      date_data = $('#date_data').val();
      $.ajax({
        type:"POST",
        dataType:"html",
        url: "{{URL::route('teacher.getStudentAttendance')}}",
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




