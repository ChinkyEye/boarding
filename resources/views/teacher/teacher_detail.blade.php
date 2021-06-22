@extends('teacher.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<section class="content-header">
</section>

@foreach($teachers as $main_datas)
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle"
              src="{{URL::to('/')}}/images/teacher/{{$main_datas->slug}}/{{$main_datas->image}}"
              alt="User profile picture">
            </div>

            <h3 class="profile-username text-center">{{$main_datas->getTeacherUser->name." ".$main_datas->getTeacherUser->middle_name." ".$main_datas->getTeacherUser->last_name}}</h3>
            <div class="d-block text-center text-muted">
              <span>{{$main_datas->getTeacherUser->email}}</span>
              <br>
              <span>{{$main_datas->phone}}</span>
            </div>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Teacher Code</b> <a class="float-right">{{$main_datas->teacher_code}}</a>
              </li>
              <li class="list-group-item">
                <b>Designation</b> <a class="float-right">{{$main_datas->designation}}</a>
              </li>
              <li class="list-group-item">
                <b>Joining Date</b> <a class="float-right">{{$main_datas->j_date}}</a>
              </li>
              <li class="list-group-item">
                <b>Marital Status</b> <a class="float-right">{{$main_datas->marital_status}}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="card card-info card-outline">
          <div class="card-header">
            <div class="row">
              <div class="col-md">
                <select class="form-control" name="shift_id" id="shift_data">
                  @foreach ($shifts as $key => $shift)
                  <option value="{{ $shift->shift_id }}" {{ old('shift_id') == $shift->shift_id ? 'selected' : ''}}> 
                    {{$shift->shift->name}}
                  </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md">
                <input type="date" name="date" class="form-control" id="attendance_date" value="{{date("Y-m-d")}}" placeholder="YYYY-MM-DD" max="{{date("Y-m-d")}}">
                {{-- <input type="text" name="date" class="form-control" id="attendance_date" placeholder="YYYY-MM-DD" > --}}
              </div>
              <div class="col-md-2" id="replaceSection">
                <h3 class="text-{{ is_null($attended) ? 'indigo' : ($attended == 1 ? 'success' : 'danger')}}">
                  {{ is_null($attended) ? 'Not-Filled' : ($attended == 1 ? 'Present' : 'Absent')}}
                </h3>
              </div>
            </div>
          </div>
          <div class="card-body">
            <strong><i class="fas fa-book mr-1"></i> Education</strong>
            <p class="text-muted">
              {{$main_datas->qualification}}
            </p>
            <hr>
            <strong><i class="fas fa-book mr-1"></i> Teacher Designation</strong>
            <p class="text-muted">
              {{$main_datas->t_designation}}
            </p>
            <hr>
            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
            <p class="text-muted">{{$main_datas->address}}</p>
            <hr>
            <strong><i class="fas fa-pencil-alt mr-1"></i> Training</strong>
            <p class="text-muted">
              <span class="tag tag-danger">{{$main_datas->training}}</span>
            </p>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endforeach
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
{{-- <script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  // console.log(currentDate);
  $('#attendance_date').val(currentDate);
  $('#attendance_date').nepaliDatePicker({
    ndpMonth: true,
    disableAfter: currentDate,
    });
  });
</script> --}}
<script type="text/javascript">
  $("body").on("change", "#attendance_date, #shift_data", function(event){
      Pace.start();
      var token = $('meta[name="csrf-token"]').attr('content'),
          date_data = $('#attendance_date').val(),
          shift_data = $('#shift_data').val();
      $.ajax({
        type:"POST",
        dataType:"html",
        url: "{{URL::route('teacher.getAttendanceList')}}",
        data: {
          _token: token,
          date_data: date_data,
          shift_data: shift_data,
        },
        success: function(response){
          $('#replaceSection').html('');
          if (response == 1){
            this.data = "Present";
            this.color = "success";
          }else if (response == 0){
            this.data = "Absent";
            this.color = "danger";
          }else{
            this.data = "Not Filled";
            this.color = "indigo h4";
          }
          $('#replaceSection').append('<h3 class="text-'+this.color+'">'+this.data+'</h3>');
        },
        error: function (e) {
          alert('Sorry! we cannot load data this time');
          return false;
        }
      });
      Pace.stop();
  });
</script>
@endpush