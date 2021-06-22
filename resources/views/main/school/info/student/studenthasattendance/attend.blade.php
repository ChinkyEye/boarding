@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
@endpush
@section('content')
<?php $page = 'Attendance'; ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }} </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('main.school.info.index',$school_info->slug) }}">{{$school_info->school_name}}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('main.student.index',$school_info->slug) }}">Student</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body table-responsive">
      <form role="form" method="POST" action="{{ route('admin.teacher-student-attendance.store')}}">
        @csrf
        <input type="hidden" name="teacher_id" value="{{$teacher_id}}">
        <table class="table table-bordered table-hover m-0" id="data_ajax">
          <div class="row pb-2">
            <div class="col">
              <select class="form-control filter_shift" name="shift_id" id="filter_shift" onchange="showCustomer(this.value)">
                <option value="">Select Your Shift</option>
                @foreach ($shifts as $key => $shift)
                <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
                  {{$shift->name}}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col">
              <select class="form-control filter_class" name="date" id="filter_date">
                <option value="">Select Your Date</option>

              </select>
            </div>
          </div>
          <div id="txtHint">Customer info will be listed here...</div>
          <thead class="bg-dark text-center">
            <th width="100">Roll no</th>
            <th width="10">Attendance</th>
            <th class="text-left">Student Name</th>
            <th class="text-left">Remark</th>
          </thead>
          @foreach($student_data as $key=>$data)             
          <tr class="text-center">
            <td>{{$data->roll_no}}</td>
            <td class="text-left">
              <!-- <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="present{{$key}}" name="status[{{$key}}]" {{$data->getStudentAttendance ? ($data->getStudentAttendance->status == 1 ? 'checked' : '') : ''}} >
              </div> -->
              <!-- <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="absent{{$key}}" value="0" name="status[{{$key}}]">
                <label class="form-check-label" for="absent{{$key}}">Absent</label>
              </div> -->
              <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                <input type="checkbox" class="custom-control-input" id="present{{$key}}" name="status[{{$key}}]" {{$data->getStudentAttendance ? ($data->getStudentAttendance->status == 1 ? 'checked' : '') : ''}} >
                <label class="custom-control-label" for="present{{$key}}"></label>
              </div>
              <input type="hidden" name="student_id[{{$key}}]" value="{{$data->id}}">
            </td>
            <td class="text-left">{{$data->first_name}} {{$data->middle_name}} {{$data->last_name}}</td>
            <td class="text-left">
              <input type="text">
            </td>
          </tr>
          @endforeach
        </table>
      </div>
    </form>
      <button class="btn btn-primary btn-block rounded-0" type="submit">Save</button>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{url('/')}}/backend/js/jquery.dataTables.js"></script>
<script src="{{url('/')}}/backend/js/dataTables.bootstrap4.js"></script>
<script type="text/javascript">
  $("body").on("change","#filter_date", function(event){
    Pace.start();
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('main.getClassList',$school_info->slug)}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
        $('#filter_class').html('');
        $('#filter_class').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#filter_class').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
<script>
function showCustomer(str) {
  var xhttp;    
  if (str == "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("txtHint").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "{{route('main.getClassList',$school_info->slug)}}"+str, true);
  xhttp.send();
}
</script>

@endpush




