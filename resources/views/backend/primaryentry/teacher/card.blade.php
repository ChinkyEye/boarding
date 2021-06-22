@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
@endpush
@section('content')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-md">
				<button class="btn btn-xs btn-info rounded-0" onclick="PrintDiv('printTable')">Print me</button>
			</div>
		</div>
	</div>
</section>
<section class="content">
  <div class="container-fluid">
    <div class="row" id="printTable">
      <div class="col-md-4">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile border">
            <h3 class="profile-username text-center font-weight-bold">ID Card</h3>
            <h3 class="profile-username text-center font-weight-bold">{{$teacher_info->getSchool->school_name}}</h3>
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle"
              src="{{URL::to('/')}}/images/teacher/{{$teacher_info->slug}}/{{$teacher_info->image}}"
              alt="Teacher profile picture">
            </div>
            <div class="d-block text-center text-muted">
              {{$teacher_info->getTeacherUser->email}}
              <br>
            </div>
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Teacher Name</b> <a class="float-right">{{$teacher_info->getTeacherUser->name." ".$teacher_info->getTeacherUser->middle_name." ".$teacher_info->getTeacherUser->last_name}}</a>
              </li>
              <li class="list-group-item">
                <b>Designation</b> <a class="float-right">{{$teacher_info->designation}}</a>
              </li>
              <li class="list-group-item">
                <b>DOB</b> <a class="float-right">{{$teacher_info->dob}}</a>
              </li>
              <li class="list-group-item">
                <b>Phone</b> <a class="float-right">{{$teacher_info->phone}}</a>
              </li>
              <li class="list-group-item">
                <b>Join Date</b> <a class="float-right">{{$teacher_info->j_date}}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')

@endpush
