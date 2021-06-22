@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/tab.main.css" />
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        
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
              <form role="form" class="row mb-3" method="GET" action="{{ route('admin.report.teacher.attendance.month.search')}}">
                <input type="hidden" id="user_id" name="user_id" value="{{$request->id}}">
                <div class="col-md input-group mb-3">
                  <select class="form-control" id="filter_month" name="filter_month">
                    <option value="1" {{ $current_month == '1' ? 'selected' : ''}}>Baisakh</option>
                    <option value="2" {{ $current_month == '2' ? 'selected' : ''}}>Jestha</option>
                    <option value="3" {{ $current_month == '3' ? 'selected' : ''}}>Asar</option>
                    <option value="4" {{ $current_month == '4' ? 'selected' : ''}}>Shrawan</option>
                    <option value="5" {{ $current_month == '5' ? 'selected' : ''}}>Bhadra</option>
                    <option value="6" {{ $current_month == '6' ? 'selected' : ''}}>Ashoj</option>
                    <option value="7" {{ $current_month == '7' ? 'selected' : ''}}>Kartik</option>
                    <option value="8" {{ $current_month == '8' ? 'selected' : ''}}>Mangsir</option>
                    <option value="9" {{ $current_month == '9' ? 'selected' : ''}}>Poush</option>
                    <option value="10" {{ $current_month == '10' ? 'selected' : ''}}>Magh</option>
                    <option value="11" {{ $current_month == '11' ? 'selected' : ''}}>Falgun</option>
                    <option value="12" {{ $current_month == '12' ? 'selected' : ''}}>Chaitra</option>
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
              <form action="{{route('admin.report.teacher.attendance.detail.export.excel')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="user_id" name="user_id" value="{{$request->id}}">
                <input type="hidden" id="current_year" name="current_year" value="{{$current_year}}">
                <input type="hidden" id="current_month" name="current_month" value="{{$current_month}}">
                <input type="hidden" id="current_days" name="current_days" value="{{$current_days}}">
                <input type="hidden" id="excShift" name="excShift">
                <input type="hidden" id="excTeacher" name="excTeacherCode">
                <input type="hidden" id="excType" name="excType" value="profile">
                <input type="submit" name="submit" value="Export PDF" id="submit" class="btn btn-danger btn-xs rounded-0">
              </form>
              <form action="{{route('admin.report.teacher.attendance.detail.export.excel')}}" method="GET" class="d-inline-block">
                <input type="hidden" id="user_id" name="user_id" value="{{$request->id}}">
                <input type="hidden" id="current_year" name="current_year" value="{{$current_year}}">
                <input type="hidden" id="current_month" name="current_month" value="{{$current_month}}">
                <input type="hidden" id="current_days" name="current_days" value="{{$current_days}}">
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
                  <div>
                    @if($current_month == 1)
                    <h1>Month : Baisakh </h1>
                    @elseif($current_month == 2)
                    <h1>Month : Jestha </h1>
                    @elseif($current_month == 3)
                    <h1>Month : Asar </h1>
                    @elseif($current_month == 4)
                    <h1>Month : Shrawan </h1>
                    @elseif($current_month == 5)
                    <h1>Month : Bhadra </h1>
                    @elseif($current_month == 6)
                    <h1>Month : Ashoj </h1>
                    @elseif($current_month == 7)
                    <h1>Month : Kartik </h1>
                    @elseif($current_month == 8)
                    <h1>Month : Mangsir </h1>
                    @elseif($current_month == 9)
                    <h1>Month : Poush </h1>
                    @elseif($current_month == 10)
                    <h1>Month : Magh </h1>
                    @elseif($current_month == 11)
                    <h1>Month : Falgun </h1>
                    @elseif($current_month == 12)
                    <h1>Month : Chaitra </h1>
                    @endif
                    <p>{{$teacher_info->getTeacherUser->name}} {{$teacher_info->getTeacherUser->middle_name}} {{$teacher_info->getTeacherUser->last_name}}/({{$teacher_info->teacher_code}})</p>
                  </div>
                    
                  <table class="table table-bordered table-hover">
                    <thead class="bg-dark">
                      <tr class="text-center">
                        <th width="10">Day</th>
                        <th width="150">Status</th>
                        <th width="150">Created By</th>
                      </tr>
                    </thead>  
                    @for ($i = 1; $i <= $current_days; $i++)
                    <tr>
                      @php
                        $loop_date = $current_year.'-'.sprintf("%02d", $current_month).'-'.sprintf("%02d", $i);
                        $query[$i] = $attendances;
                        $main[$i] = $query[$i]->where('date','=',$loop_date);
                      @endphp

                      <td>{{$loop_date}}</td>
                      @forelse ($main[$i] as $attendance)
                        <td>
                          {{$attendance->status == '1' ? 'P' : 'A'}}
                        </td>
                        <td>
                          {{$attendance->getUser->name}}
                        </td>
                      @empty
                        <td>-</td>
                        <td>-</td>
                      @endforelse
                    </tr>
                    @endfor            
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

  $('#reset').click(function(){
    $('#filter_month').val('');
  });
</script>
@endpush