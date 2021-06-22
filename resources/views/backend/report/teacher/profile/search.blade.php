@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/tab.main.css" />
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <form action="{{route('admin.report.export.allteacherreport')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="excShift" name="excShift" >
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
              <form role="form" class="row mb-3" method="GET" action="{{ route('admin.report.teacher.profile.search')}}">
                <div class="col-md">
                  <input type="text" name="search_data" id="search_data" class="form-control" placeholder="Search By Code" value="{{$request->search_data}}">
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
              <form action="{{route('admin.report.teacher.profile.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excShift" name="excShift" value="{{$request->shift_data}}">
                <input type="hidden" id="excTeacher" name="excTeacherCode" value="{{$request->search_data}}">
                <input type="hidden" id="excType" name="excType" value="profile">
                <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-xs rounded-0">
              </form>
              <form action="{{route('admin.report.teacher.profile.export.excel')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excTeacherCode" name="excTeacherCode" value="{{$request->search_data}}">
                <input type="hidden" id="excShiftData" name="excShiftData" value="{{$request->shift_data}}">
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
                        <th width="10">SN</th>
                        <th width="10">Code</th>
                        <th class="text-left">Teacher</th>
                        <th width="100" class="text-left">Phone Number</th>
                        <th width="100" class="text-left">Email</th>
                        <th width="100" class="text-left">Address</th>
                        <th width="100" class="text-left">Type</th>
                        <th width="100" class="text-left">Gender</th>
                        <th width="100" class="text-left">Marital Status</th>
                        <th width="100" class="text-left">Education</th>
                        <th width="100" class="text-left">Teacher Designation</th>
                        <th width="100" class="text-left">Designation</th>
                        <th width="100" class="text-left">Training</th>
                        <th width="100" class="text-left">Religion</th>
                        <th width="100" class="text-left">Joining Date</th>
                        <th width="150">Created By</th>
                        <th width="10">Action</th>
                      </tr>
                    </thead>              
                    @foreach($teachers as $key=>$teacher)             
                    <tr class="text-center">
                      <td>{{$key+1}}</td>
                      <td>{{$teacher->teacher_code}}</td>
                      <td class="text-left">{{$teacher->getTeacherUser->name}} {{$teacher->getTeacherUser->middle_name}} {{$teacher->getTeacherUser->last_name}}</td>
                      <td class="text-left">{{$teacher->phone}}</td>
                      <td class="text-left">{{$teacher->getTeacherUser->email}}</td>
                      <td class="text-left">{{$teacher->address}}</td>
                      <td class="text-left">{{$teacher->uppertype}}</td>
                      <td class="text-left">{{$teacher->gender}}</td>
                      <td class="text-left">{{$teacher->marital_status}}</td>
                      <td class="text-left">{{$teacher->qualification}}</td>
                      <td class="text-left">{{$teacher->t_designation}}</td>
                      <td class="text-left">{{$teacher->designation}}</td>
                      <td class="text-left">{{$teacher->training}}</td>
                      <td class="text-left">{{$teacher->religion}}</td>
                      <td class="text-left">{{$teacher->j_date}}</td>
                      <td class="text-left">{{$teacher->getUser->name}} {{$teacher->getUser->middle_name}} {{$teacher->getUser->last_name}}</td>
                      <td>
                        <a href="{{ route('admin.teacher.show',$teacher->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="View Detail" target="_blank"><i class="fas fa-eye"></i></a>
                      </td>
                    </tr>
                    @endforeach
                  </table>
                </div>
              </div>
              {!! $teachers->links("pagination::bootstrap-4") !!}  
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
  });
</script>
@endpush