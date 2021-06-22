@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/tab.main.css" />
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <form action="{{route('admin.fee-report.export')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="excShift" name="excShift">
          <input type="hidden" id="excTeacher" name="excTeacher">
          <input type="hidden" id="excMonth" name="excMonth">
          <input type="hidden" id="excType" name="excType">
          <input type="submit" name="submit" value="Export Excel" id="submit" class="btn btn-success btn-sm rounded-0">
          <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-sm rounded-0">
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
            <nav>
              <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{$page == 'profile' ? 'active' : ''}}" id="nav-profile-tab" href="{{route('admin.report.teacher.show',['profile',$url_request])}}" role="tab" aria-controls="nav-profile" aria-selected="true">Profile</a>
                <a class="nav-item nav-link {{$page == 'attendance' ? 'active' : ''}}" id="nav-attendance-tab" href="{{route('admin.report.teacher.show',['attendance',$url_request])}}" role="tab" aria-controls="nav-attendance" aria-selected="false">Attendance</a>
                <a class="nav-item nav-link {{$page == 'salary' ? 'active' : ''}}" id="nav-salary-tab" href="{{route('admin.report.teacher.show',['salary',$url_request])}}" role="tab" aria-controls="nav-salary" aria-selected="false">Salary</a>
              </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead class="bg-dark">
                      <tr class="text-center">
                        <th width="10">SN</th>
                        <th width="10">Code</th>
                        <th width="100" class="text-left">Teacher</th>
                        <th width="150">Created By</th>
                        <th width="10">Action</th>
                      </tr>
                    </thead>              
                    @foreach($teachers as $key=>$teacher)             
                    <tr class="text-center">
                      <td>{{$key+1}}</td>
                      <td>{{$teacher->teacher_code}}</td>
                      <td class="text-left">{{$teacher->getTeacherUser->name}} {{$teacher->getTeacherUser->middle_name}} {{$teacher->getTeacherUser->last_name}}</td>
                      <td>{{$teacher->getUser->name}}</td>
                      <td>
                        <a href="{{ route('admin.income.show',$teacher->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-eye"></i></a>
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
<script type="text/javascript">
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });
</script>
@endpush