@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/tab.main.css" />
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css">
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <form action="{{route('admin.report.export.allstudentreport')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="excMonth" name="excMonth">
          <input type="hidden" id="excTeacher" name="excTeacherCode">
          <input type="hidden" id="excType" name="excType" value="profile">
          <input type="submit" name="submit" value="Export Pdf" id="submit" class="btn btn-danger btn-sm rounded-0">
        </form>
        <form action="{{route('admin.report.export.allstudentreport')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="excMonth" name="excMonth">
          <input type="hidden" id="excTeacher" name="excTeacherCode">
          <input type="hidden" id="excType" name="excType" value="profile">
          <input type="submit" name="submit" value="Export Excel" id="submit" class="btn btn-success btn-sm rounded-0">
        </form>
        
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('admin.student.index')}}"> Student</a></li>
          <li class="breadcrumb-item active">Report</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md p-0">
            @include('backend.report.student.nav')
            <div class="tab-content p-3" id="nav-tabContent">
              <div class="col-sm-6 pb-1 btn-group btn-block">
                
                
              </div>
              <form role="form" class="row mb-3" method="GET" action="{{ route('admin.report.student.attendance.search')}}">
                <div class="col-md">
                  <input type="text" class="form-control ndp-nepali-calendar" id="filter_date" name="filter_date" autocomplete="off" value="{{$request->filter_date }}" placeholder="YYYY-MM-DD">
                </div>
                <div class="col-md">
                  <select class="form-control" name="shift_data" id="shift_data">
                    <option value="">Select Your Shift</option>
                    @foreach ($shifts as $key => $shift)
                    <option value="{{ $shift->id }}" {{ $request->shift_data == $shift->id ? 'selected' : ''}}> 
                      {{$shift->name}}
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md">
                  <select class="form-control" name="class_data" id="class_data">
                    <option value="">Select Your Class</option>
                  </select>
                </div>
                <div class="col-md">
                  <select class="form-control" name="section_data" id="section_data">
                    <option value="">Select Your Section</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <div class="btn-group btn-block">
                    <button type="submit" name="search" value="profile" id="search" class="btn btn-primary">Search</button>
                    <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
                  </div>
                </div>
                @csrf
              </form>
              <form action="{{route('admin.report.student.attendancelist.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="exportShift" name="shift_data" value="{{$request->shift_data}}">
                <input type="hidden" id="exportClass" name="class_data" value="{{$request->class_data}}">
                <input type="hidden" id="exportSection" name="section_data" value="{{$request->section_data}}">
                <input type="hidden" id="excDate" name="excDate" value="{{$request->filter_date }}">
                <input type="hidden" id="excType" name="excType" value="profile">
                <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-xs rounded-0">
              </form>
              <form action="{{route('admin.report.student.attendancelist.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="exportShift" name="shift_data" value="{{$request->shift_data}}">
                <input type="hidden" id="exportClass" name="class_data" value="{{$request->class_data}}">
                <input type="hidden" id="exportSection" name="section_data" value="{{$request->section_data}}">
                <input type="hidden" id="excDate" name="excDate" value="{{$request->filter_date }}">
                <input type="hidden" id="excType" name="excType" value="profile">
                <input type="submit" name="submit" value="Export Excel" id="submit" class="btn btn-success btn-xs rounded-0">
              </form>
              <div class="float-right">
                <button type="button" class="btn btn-xs btn-info rounded-0" onclick="PrintDiv('printTable')">Print me</button>
              </div>
              <div id="printTable">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead class="bg-dark">
                      <tr class="text-center">
                        <th width="10">SN</th>
                        <th width="100">Student</th>
                        <th width="150">Status</th>
                        <th width="150">Created By</th>
                        <th width="100">Action</th>
                      </tr>
                    </thead>              
                    @foreach($attendances as $key=>$attendance)             
                    <tr class="text-center" keys="{{$key}}">
                      <td>{{$key+1}}</td>
                      <td>{{$attendance->getStudentUser->name}} {{$attendance->getStudentUser->middle_name}} {{$attendance->getStudentUser->last_name}} ({{$attendance->student_code}})</td>
                       @if ($attendance->getStudentAttendance)
                       <td>
                           {{$attendance->getStudentAttendance->status == '1' ? 'P' : 'A'}}
                       </td>
                       <td>{{$attendance->getStudentAttendance->getUser->name}} {{$attendance->getStudentAttendance->getUser->middle_name}} {{$attendance->getStudentAttendance->getUser->last_name}}</td>
                       @else
                       <td>-</td>
                       <td>-</td>
                       @endif
                       <td>
                        <form action="{{ route('admin.report.student.attendance.detail',$attendance->user_id) }}" method="GET" class="d-inline-block">
                          {{-- <input type="hidden" id="shift_data" name="shift_data" value="{{$attendance->getShiftTeacherCountList()->take(1)->value('shift_id')}}"> --}}
                          @csrf
                          <button type="submit" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="View Attendance"><i class="fas fa-eye"></i></button>
                        </form>
                       </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(event){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD"),
        currentYear = NepaliFunctions.GetCurrentBsYear(),
        currentMonth = NepaliFunctions.GetCurrentBsMonth(),
        number_of_days = NepaliFunctions.GetDaysInBsMonth(currentYear, currentMonth);
    // for show
    // $('#filter_date').val(currentDate);
    // $('#excDate').val(currentDate);
    // date input
    $('#filter_date').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
      onChange: function(event) {
        var date = $('#filter_date').val();
        var year = event.object.year;
        var month = event.object.month;
        var number_of_days = NepaliFunctions.GetDaysInBsMonth(year, month);
        // debugger;
      }
    });
  });

</script>
<script type="text/javascript">
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });

  $('#reset').click(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#shift_data').val('');
    $('#class_data').val('');
    $('#section_data').val('');
    $('#filter_date').val(currentDate);
  });
</script>
<script type="text/javascript">
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
        $('#exportShift').val(shift_id);
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
        alert("Sorry");
      }
    });
        Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    Pace.start();
    var class_id = $('#class_data').val(),
        shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
          $('#exportClass').val(class_id);
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
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#section_data", function(event){
    var section_id = $('#section_data').val();
      $('#exportSection').val(section_id);
  });
</script>
<script type="text/javascript">
  $(document).ready(function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
       token = $('meta[name="csrf-token"]').attr('content');
       $('#excShift').val(shift_id);
       
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
        $('#class_data').append('<option value="">--Choose Shift--</option>');
        $.each( response, function( i, val ) {
          if({{ $request->class_data == null ? '0' : $request->class_data }} == val.id){
            $('#class_data').append('<option value='+val.get_class.id+' selected>'+val.get_class.name+'</option>');
          }else{
            $('#class_data').append('<option value='+val.get_class.id+'>'+val.get_class.name+'</option>');
          }
        });

        var class_id = $("#class_data").val();
        console.log($("#class_data").val());
       
        $.ajax({
          type:"POST",
          dataType:"JSON",
          url:"{{route('admin.getSectionList')}}",
          data:{
            _token: token,
            shift_id: shift_id,
            class_id: class_id
          },
          success: function(response){
            $('#section_data').html('');
            $('#section_data').append('<option value="">--Choose Section--</option>');
            $.each( response, function( i, val ) {
              if({{ $request->section_data == null ? '0' : $request->section_data }} == val.id){
                $('#section_data').append('<option value='+val.get_section.id+' selected>'+val.get_section.name+'</option>');
              }else{
                $('#section_data').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');

              }
            });

          },
          error: function(event){
            alert("Sorry");
            Pace.stop();
          }
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