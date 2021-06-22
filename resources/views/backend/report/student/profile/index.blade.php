@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/tab.main.css" />
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <form action="{{route('admin.report.export.allstudentreport')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="excShift" name="excShift">
          <input type="hidden" id="excTeacher" name="excTeacherCode">
          <input type="hidden" id="excType" name="excType" value="profile">
          <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-sm rounded-0">
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
              <form role="form" class="row mb-3" method="GET" action="{{ route('admin.report.student.profile.search')}}">
                {{-- <div class="col-md">
                  <input type="text" name="search_data" id="search_data" class="form-control" placeholder="Search By Code">
                </div> --}}
                <div class="col-md">
                  <select class="form-control" name="shift_data" id="shift_data">
                    <option value="">Select Your Shift</option>
                    @foreach ($shifts as $key => $shift)
                    <option value="{{ $shift->id }}" {{ old('shift_data') == $shift->id ? 'selected' : ''}}> 
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
              <form action="{{route('admin.report.student.profile.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excShift" name="excShift">
                <input type="hidden" id="excTeacher" name="excTeacherCode">
                <input type="hidden" id="excType" name="excType" value="profile">
                <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-xs rounded-0">
              </form>
              <form action="{{route('admin.report.student.profile.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excShift" name="excShift">
                <input type="hidden" id="excTeacher" name="excTeacherCode">
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
                      <tr class="text-center v-align-middle">
                        <th>SN</th>
                        <th class="text-left">Student</th>
                        <th class="text-left">Info</th>
                        <th class="text-left">Address</th>
                        <th class="text-left">Gender</th>
                        <th class="text-left">Register Id</th>
                        <th class="text-left">Register Date</th>
                        <th class="text-left">Father Name</th>
                        <th class="text-left">Mother Name</th>
                        <th class="text-left">Phone Number</th>
                        <th class="text-left">Email</th>
                        <th>Created By</th>
                        <th>Action</th>
                      </tr>
                    </thead>              
                    @foreach($students as $key=>$student)             
                    <tr class="text-center">
                      <td>{{$key+1}}</td>
                      <td class="text-left"><span class="badge badge-info">{{$student->getStudentUser->name}} {{$student->getStudentUser->middle_name}} {{$student->getStudentUser->last_name}} ({{$student->student_code}})</span></td>
                      <td class="text-left">{{$student->getShift->name}}/{{$student->getClass->name}}/{{$student->getSection->name}}</td>
                      <td class="text-left">{{$student->address}}</td>
                      <td class="text-left">{{$student->gender}}</td>
                      <td class="text-left">{{$student->register_id}}</td>
                      <td class="text-left">{{$student->register_date}}</td>
                      <td class="text-left">{{$student->student_has_parent_count?$student->Student_has_parent->father_name:''}}</td>
                      <td class="text-left">{{$student->student_has_parent_count?$student->Student_has_parent->mother_name:''}}</td>
                      <td class="text-left">{{$student->phone_no}}</td>
                      <td class="text-left">{{$student->getStudentUser->email}}</td>
                      <td class="text-left">{{$student->getUser->name}} {{$student->getUser->middle_name}} {{$student->getUser->last_name}}</td>
                      <td>
                        <a href="{{ route('admin.student.show',$student->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="View Detail" target="_blank"><i class="fas fa-eye"></i></a>
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
              </div>
              {!! $students->links("pagination::bootstrap-4") !!} 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript">
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });

  $('#reset').click(function(){
    $('#shift_data').val('');
    $('#class_data').val('');
    $('#section_data').val('');
  });
</script>
<script type="text/javascript">
  $("body").on("change","#shift_data", function(event){
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
          $('#excClass').val(class_id);
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
@endpush