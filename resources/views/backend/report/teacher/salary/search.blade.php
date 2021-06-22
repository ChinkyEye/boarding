@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css">
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/tab.main.css" />
@endpush
@section('content')
<section class="content-header">
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
</section>
<section class="content">
  <div class="card">
    <div class="">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md p-0">
            @include('backend.report.teacher.nav')
            
            <div class="tab-content p-3" id="nav-tabContent">
              <form role="form" class="row mb-3" method="GET" action="{{ route('admin.report.teacher.salary.search')}}">
                <div class="col-md input-group mb-3">
                  <select class="form-control" id="filter_month" name="filter_month">
                    <option value="1" {{ $request->filter_month == '1' ? 'selected' : ''}}>Baisakh</option>
                    <option value="2" {{ $request->filter_month == '2' ? 'selected' : ''}}>Jestha</option>
                    <option value="3" {{ $request->filter_month == '3' ? 'selected' : ''}}>Asar</option>
                    <option value="4" {{ $request->filter_month == '4' ? 'selected' : ''}}>Shrawan</option>
                    <option value="5" {{ $request->filter_month == '5' ? 'selected' : ''}}>Bhadra</option>
                    <option value="6" {{ $request->filter_month == '6' ? 'selected' : ''}}>Ashoj</option>
                    <option value="7" {{ $request->filter_month == '7' ? 'selected' : ''}}>Kartik</option>
                    <option value="8" {{ $request->filter_month == '8' ? 'selected' : ''}}>Mangsir</option>
                    <option value="9" {{ $request->filter_month == '9' ? 'selected' : ''}}>Poush</option>
                    <option value="10" {{ $request->filter_month == '10' ? 'selected' : ''}}>Magh</option>
                    <option value="11" {{ $request->filter_month == '11' ? 'selected' : ''}}>Falgun</option>
                    <option value="12" {{ $request->filter_month == '12' ? 'selected' : ''}}>Chaitra</option>
                  </select>
                  <div class="input-group-append">
                    <label class="input-group-text" for="filter_month">Month</label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="btn-group btn-block">
                    <button type="submit" name="search" value="profile" id="search" class="btn btn-primary">Search</button>
                    <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
                  </div>
                </div>
                @csrf
              </form>
              <form action="{{route('admin.report.teacher.salarylist.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excMonth" name="excMonth" value="{{$request->filter_month}}">
                <input type="hidden" id="excType" name="excType" value="profile">
                <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-xs rounded-0">
              </form>
              <form action="{{route('admin.report.teacher.salarylist.export')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="excMonth" name="excMonth" value="{{$request->filter_month}}">
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
                        <th class="text-left">Teacher</th>
                        <th width="100">Income</th>
                        <th width="100">Grade</th>
                        <th width="100">Mahangi Vatta</th>
                        <th width="100">Durgam Vatta</th>
                        <th width="100">Citizen Investment Deduction</th>
                        <th width="100">Loan Deduction</th>
                        <th width="100">Cloth Amount</th>
                        <th width="100">Remarks</th>
                        <th width="100">Month</th>
                        <th width="150">Created By</th>
                      </tr>
                    </thead>              
                    @foreach($teachers as $key=>$teacher)             
                    <tr class="text-center">
                      <td>{{$key+1}}</td>
                      <td class="text-left">{{$teacher->getTeacherUser->name}} {{$teacher->getTeacherUser->middle_name}} {{$teacher->getTeacherUser->last_name}}</td>
                      @if ($teacher->getTeacherIncome)
                        <td>{{$teacher->getTeacherIncome->amount}}</td>
                        <td>{{$teacher->getTeacherIncome->grade}}</td>
                        <td>{{$teacher->getTeacherIncome->mahangi_vatta}}</td>
                        <td>{{$teacher->getTeacherIncome->durgam_vatta}}</td>
                        <td>{{$teacher->getTeacherIncome->citizen_investment_deduction}}</td>
                        <td>{{$teacher->getTeacherIncome->loan_deduction}}</td>
                        <td>{{$teacher->getTeacherIncome->cloth_amount}}</td>
                        <td>{{$teacher->getTeacherIncome->remark}}</td>
                        @if($teacher->getTeacherIncome->month == 1)
                        <td>Baisakh</td>
                        @elseif($teacher->getTeacherIncome->month == 2)
                        <td>Jestha</td>
                        @elseif($teacher->getTeacherIncome->month == 3)
                        <td>Asar</td>
                        @elseif($teacher->getTeacherIncome->month == 4)
                        <td>Shrawan</td>
                        @elseif($teacher->getTeacherIncome->month == 5)
                        <td>Bhandra</td>
                        @elseif($teacher->getTeacherIncome->month == 6)
                        <td>Ashoj</td>
                        @elseif($teacher->getTeacherIncome->month == 7)
                        <td>Kartik</td>
                        @elseif($teacher->getTeacherIncome->month == 8)
                        <td>Mangsir</td>
                        @elseif($teacher->getTeacherIncome->month == 9)
                        <td>Poush</td>
                        @elseif($teacher->getTeacherIncome->month == 10)
                        <td>Magh</td>
                        @elseif($teacher->getTeacherIncome->month == 11)
                        <td>Falgun</td>
                        @elseif($teacher->getTeacherIncome->month == 12)
                        <td>Chaitra</td>
                        @endif
                        <td>{{$teacher->getTeacherIncome->getUser->name}}</td>
                      @else
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      @endif
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
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });

  $('#reset').click(function(){
    var currentMonth = NepaliFunctions.GetCurrentBsMonth();
      $('#filter_month').val(currentMonth);
  });
</script>
@endpush