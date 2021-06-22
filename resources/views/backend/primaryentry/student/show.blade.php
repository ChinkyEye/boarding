@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
@foreach($students as $main_datas)
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle inverted"
              src="{{URL::to('/')}}/images/student/{{$main_datas->slug}}/{{$main_datas->image}}"
              alt="User profile picture">
            </div>

            <h3 class="profile-username text-center">{{$main_datas->getStudentUser->name. " ".$main_datas->getStudentUser->middle_name." ".$main_datas->getStudentUser->last_name}}</h3>
            <div class="d-block text-center text-muted">
              <span>{{$main_datas->getStudentUser->email}}</span>
              <br>
              <span>{{$main_datas->student_code}}</span>
            </div>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Class</b> <a class="float-right">{{$main_datas->getClass->name}}</a>
              </li>
              <li class="list-group-item">
                <b>Shift</b> <a class="float-right">{{$main_datas->getShift->name}}</a>
              </li>
              <li class="list-group-item">
                <b>Section</b> <a class="float-right">{{$main_datas->getSection->name}}</a>
              </li>
              <li class="list-group-item">
                <b>Roll No</b> <a class="float-right">{{$main_datas->roll_no}}</a>
              </li>
              <li class="list-group-item">
                <b>Register ID</b> <a class="float-right">{{$main_datas->register_id}}</a>
              </li>
              <li class="list-group-item">
                <b>Register Date</b> <a class="float-right">{{$main_datas->register_date}}</a>
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
              {{$main_datas->student_has_parent_count?$main_datas->Student_has_parent->father_name:''}}
            </p>
            <hr> 
            <strong><i class="fas fa-user mr-1"></i> Mother Name</strong>
            <p class="text-muted">
              {{-- {{$main_datas->Student_has_parent->mother_name}} --}}
               {{$main_datas->student_has_parent_count?$main_datas->Student_has_parent->mother_name:''}}
            </p>
            <hr>
            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
            <p class="text-muted">
             {{$main_datas->student_has_parent_count?$main_datas->Student_has_parent->address:''}}</p>
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
@endforeach
@endsection