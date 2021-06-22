@extends('main.school.include.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/tab.main.css" />
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css">
@endpush
@section('school-content')
{{-- <section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <form action="{{route('admin.report.export.allteacherreport')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="excMonth" name="excMonth">
          <input type="hidden" id="excTeacher" name="excTeacherCode">
          <input type="hidden" id="excType" name="excType" value="profile">
          <input type="submit" name="submit" value="Export Pdf" id="submit" class="btn btn-danger btn-sm rounded-0">
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
</section> --}}
<section class="content">
  <div class="card">
    <div class="">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md p-0">
            @include('main.school.info.report.teacher.nav')
            <div class="tab-content p-3" id="nav-tabContent">
              <div class="col-sm-6 pb-1 btn-group btn-block">
                
                
              </div>
              <form role="form" class="row mb-3" method="GET" action="{{ route('main.school.teacher.report.attendance.search',[$school_info->slug])}}">
                <div class="col-md">
                  <input type="text" class="form-control ndp-nepali-calendar" id="filter_date" name="filter_date" autocomplete="off" value="{{ old('date') }}" placeholder="YYYY-MM-DD">
                </div>
                <div class="col-md">
                  <select class="form-control" name="shift_data" id="shift_data">
                    {{-- <option value="">Select Your Shift</option> --}}
                    @foreach ($shifts as $key => $shift)
                    <option value="{{ $shift->id }}" {{ old('shift_data') == $shift->id ? 'selected' : ''}}> 
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
              {{-- <form action="{{route('admin.report.teacher.attendancelist.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excShift" name="excShift">
                <input type="hidden" id="excTeacher" name="excTeacherCode">
                <input type="hidden" id="excType" name="excType" value="profile">
                <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-xs rounded-0">
              </form>
              <form action="{{route('admin.report.teacher.attendancelist.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excShift" name="excShift">
                <input type="hidden" id="excDate" name="excDate">
                <input type="hidden" id="excType" name="excType" value="profile">
                <input type="submit" name="submit" value="Export Excel" id="submit" class="btn btn-success btn-xs rounded-0">
              </form> --}}
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
                    <tr class="text-center" keys="{{$key}}">
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
                        <form action="{{ route('main.school.teacher.report.attendance.detail',[$school_info->slug,$attendance->user_id]) }}" method="GET" class="d-inline-block">
                          <input type="hidden" id="shift_data" name="shift_data" value="{{$attendance->getShiftTeacherCountList()->take(1)->value('shift_id')}}">
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
    $('#filter_date').val(currentDate);
    $('#excDate').val(currentDate);
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
  $('#reset').click(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#shift_data').val('');
    // $('#filter_month').val(/currentMonth);
    $('#filter_date').val(currentDate);
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });

  $('#reset').click(function(){
    $('#shift_data').val('');
  });
</script>
@endpush