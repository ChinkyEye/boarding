@extends('main.school.include.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/tab.main.css" />
@endpush
@section('school-content')
{{-- <section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <form action="{{route('admin.report.export.allteacherreport')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="excShift" name="excShift">
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
</section> --}}
<section class="content">
  <div class="card">
    <div class="">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md p-0">
            @include('main.school.info.report.teacher.nav')
            <div class="tab-content p-3" id="nav-tabContent">
              {{-- <form action="{{route('admin.report.subjectclasslist.export.excel')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="pdf" name="export" value="exportPdf">
                <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-xs rounded-0">
              </form>
              <form action="{{route('admin.report.subjectclasslist.export.excel')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excel" name="export" value="exportExcel">
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
                        <th class="text-left">Name</th>
                        <th class="text-left">Class / Subject</th>
                        <th width="150">Created By</th>
                      </tr>
                    </thead>              
                    @foreach($teacherhasperiods as $key=>$teacherhasperiod) 
                    <!-- {{$teacherhasperiod}}             -->
                    <tr class="text-center">
                      <td>{{$key+1}}</td>
                      <td class="text-left">{{$teacherhasperiod->getTeacherUser->name}} {{$teacherhasperiod->getTeacherUser->middle_name}} {{$teacherhasperiod->getTeacherUser->last_name}}</td>
                      @if($teacherhasperiod->getTeacherPeriod()->count())
                        @foreach($teacherhasperiod->getTeacherPeriod()->get() as $index=>$teacherclass)
                          @if ($index != 0)
                    </tr>
                          <tr class="text-center">
                            <td colspan="2"></td>
                            <td class="text-left">
                              <span class="badge badge-info">
                                {{$teacherclass->getClass->name}} 
                                <span class="badge badge-warning">{{$teacherclass->getTeacherSubject()->count()}}</span>
                              </span>
                              @foreach ($teacherclass->getTeacherSubject()->get() as $element)
                              <span class="badge badge-success">
                                  {{$element->getSubject->compulsory_optional == '2' ? 'Opt. ' : ''}} 
                                  {{$element->getSubject->name}} 
                                  {{$element->getSubject->theory_practical == '1' ? ' (Th)' : ' (Pr)'}}
                              </span>
                              @endforeach
                            </td>
                            <td>{{$teacherclass->getUser->name}} {{$teacherclass->getUser->middle_name}} {{$teacherclass->getUser->last_name}}</td>
                          </tr>
                          @else
                            <td class="text-left">
                              <span class="badge badge-info">
                                {{$teacherclass->getClass->name}} 
                                <span class="badge badge-warning">{{$teacherclass->getTeacherSubject()->count()}}</span>
                              </span>
                              @foreach ($teacherclass->getTeacherSubject()->get() as $element)
                              <span class="badge badge-success">
                                  {{$element->getSubject->compulsory_optional == '2' ? 'Opt. ' : ''}} 
                                  {{$element->getSubject->name}} 
                                  {{$element->getSubject->theory_practical == '1' ? ' (Th)' : ' (Pr)'}}
                              </span>
                              @endforeach
                            </td>
                            <td>{{$teacherclass->getUser->name}} {{$teacherclass->getUser->middle_name}} {{$teacherclass->getUser->last_name}}</td>
                          </tr>
                          @endif
                        @endforeach
                      @else
                        <td>-</td>
                        <td>-</td>
                      </tr>
                      @endif
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