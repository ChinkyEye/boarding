@extends('main.school.include.app')
@section('school-content')
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
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle inverted"
              src="{{URL::to('/')}}/images/student/{{$students_info->slug}}/{{$students_info->image}}"
              alt="{{$students->name}}">
            </div>

            <h3 class="profile-username text-center">{{$students->name}} {{$students->middle_name}} {{$students->last_name}}</h3>
            <div class="d-block text-center text-muted">
              <span>{{$students->email}}</span>
              <br>
              <span>{{$students_info->student_code}}</span>
            </div>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Class</b> <a class="float-right">{{$students_info->getClass->name}}</a>
              </li>
              <li class="list-group-item">
                <b>Shift</b> <a class="float-right">{{$students_info->getShift->name}}</a>
              </li>
              <li class="list-group-item">
                <b>Section</b> <a class="float-right">{{$students_info->getSection->name}}</a>
              </li>
              <li class="list-group-item">
                <b>Roll No</b> <a class="float-right">{{$students_info->roll_no}}</a>
              </li>
              <li class="list-group-item">
                <b>Register ID</b> <a class="float-right">{{$students_info->register_id}}</a>
              </li>
              <li class="list-group-item">
                <b>Register Date</b> <a class="float-right">{{$students_info->register_date}}</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">About Parent</h3>
          </div>

          <div class="card-body">
            <strong><i class="fas fa-user mr-1"></i> Father Name</strong>
            <p class="text-muted">
              {{$students_info->student_has_parent_count?$students_info->Student_has_parent->father_name:''}}
            </p>
            <hr> 
            <strong><i class="fas fa-user mr-1"></i> Mother Name</strong>
            <p class="text-muted">
              {{$students_info->student_has_parent_count?$students_info->Student_has_parent->mother_name:''}}
            </p>
            <hr>
            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
            <p class="text-muted"> {{$students_info->student_has_parent_count?$students_info->Student_has_parent->address:''}}</p>
            <hr>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="card">
          <div class="card-body">

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection