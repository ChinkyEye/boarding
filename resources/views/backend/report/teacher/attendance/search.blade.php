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
        <form action="{{route('admin.report.export.allteacherreport')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="excMonth" name="excMonth">
          <input type="hidden" id="excTeacher" name="excTeacherCode">
          <input type="hidden" id="excType" name="excType" value="profile">
          <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-sm rounded-0">
        </form>
        <form action="{{route('admin.report.export.allteacherreport')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="excMonth" name="excMonth">
          <input type="hidden" id="excTeacher" name="excTeacherCode">
          <input type="hidden" id="excType" name="excType" value="profile">
          <input type="submit" name="submit" value="Export Excel" id="submit" class="btn btn-success btn-sm rounded-0">
        </form>
    
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('admin.teacher.index')}}"> Teacher</a></li>
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
            @include('backend.report.teacher.nav')
            
            <div class="tab-content p-3" id="nav-tabContent">
              <div class="col-sm-6 pb-1 btn-group btn-block">
               
              </div>
              <form role="form" class="row mb-3" method="GET" action="{{ route('admin.report.teacher.attendance.search')}}">
                <div class="col-md">
                  <input type="text" class="form-control ndp-nepali-calendar" id="filter_date" name="filter_date" autocomplete="off" value="{{ $request->filter_date}}" placeholder="YYYY-MM-DD">
                  <input type="hidden" id="selected_year" name="year" value="{{$request->year}}">
                  <input type="hidden" id="selected_month" name="month" value="{{$request->month}}">
                  <input type="hidden" id="selected_days" name="days" value="{{$request->days}}">
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
                <div class="col-md-2">
                  <div class="btn-group btn-block">
                    <button type="submit" name="search" value="profile" id="search" class="btn btn-primary">Search</button>
                    <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
                  </div>
                </div>
                @csrf
              </form>
              <form action="{{route('admin.report.teacher.attendancelist.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excShift" name="excShift" value="{{$request->shift_data}}">
                <input type="hidden" id="excDate" name="excDate" value="{{$request->filter_date}}">
                <input type="hidden" id="excType" name="excType" value="profile">
                <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-xs rounded-0">
              </form>
              <form action="{{route('admin.report.teacher.attendancelist.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excShift" name="excShift" value="{{$request->shift_data}}">
                <input type="hidden" id="excDate" name="excDate" value="{{$request->filter_date}}">
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
                        <th width="100">Teacher</th>
                        <th width="150">Status</th>
                        <th width="150">Created By</th>
                        <th width="100">Action</th>
                      </tr>
                    </thead>  

                    @foreach($attendances as $key=>$attendance)             
                    <tr class="text-center">
                      <td>{{$key+1}}</td>
                      <td>{{$attendance->getTeacherUser->name}} {{$attendance->getTeacherUser->middle_name}} {{$attendance->getTeacherUser->last_name}}</td>
                       @if ($attendance->getTeacherAttendance)
                       <td>
                           {{$attendance->getTeacherAttendance->status == '1' ? 'P' : 'A'}}
                       </td>
                       <td>{{$attendance->getUser->name}}</td>
                       @else
                       <td>-</td>
                       <td>-</td>
                       @endif
                       <td>
                        <form action="{{ route('admin.report.teacher.attendance.detail',$attendance->user_id) }}" method="GET" class="d-inline-block">
                          <input type="hidden" id="shift_data" name="shift_data" value="{{$request->shift_data}}">
                          @csrf
                          <button type="submit" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="View Attendance"><i class="fas fa-eye"></i></button>
                        </form>
                        {{--  <a href="{{ route('admin.report.teacher.attendance.detail',['id'=>$attendance->user_id]) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="View Attendance"><i class="fas fa-eye"></i></a> --}}
                       </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
              </div>
              {!! $attendances->links("pagination::bootstrap-4") !!}  
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
  $(document).ready(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD"),
        currentYear = NepaliFunctions.GetCurrentBsYear(),
        currentMonth = NepaliFunctions.GetCurrentBsMonth(),
        number_of_days = NepaliFunctions.GetDaysInBsMonth(currentYear, currentMonth);
    // for show
    $('#current_year').val(currentYear);
    $('#current_month').val(currentMonth);
    $('#current_days').val(number_of_days);
    // date input
    $('#filter_date').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
      onChange: function(event) {
        var date = $('#filter_date').val();
        var year = event.object.year;
        var month = event.object.month;
        var number_of_days = NepaliFunctions.GetDaysInBsMonth(year, month);
        $('#selected_year').val(year);
        $('#selected_month').val(month);
        $('#selected_days').val(number_of_days);
        // $('#filter_date').val(date);
        $('#search').prop('disabled', false);
      }
    });
  });
  $('#reset').click(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#shift_data').val('');
    $('#filter_date').val(currentDate);
    $('#filter_month').val(currentMonth);
    // $('#search').prop('disabled', true);
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });

  $('#reset').click(function(){
    $('#filter_month').val('');
  });
</script>
@endpush