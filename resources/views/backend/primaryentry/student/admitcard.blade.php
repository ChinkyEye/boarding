@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">  Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container-fluid">
    <div class="row">
    @foreach($students as $key=>$student)
      <div class="col-md-6">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="row pb-4">
              <div class="col-md-3">
                <img class="profile-user-img img-fluid img-circle"
                src="{{URL::to('/')}}/backend/images/user.png"
                alt="Student profile picture">
              </div>
              <div class="col-md-9">
                <p class="text-center text-capitalize font-weight-bold">{{$student->getSchool->name}}</p>
                <p class="text-center text-capitalize font-weight-bold">{{ $exams }} Admit Card</p>
              </div>
            </div>
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Student Name</b> <a class="float-right">{{$student->getStudentUser->name." ".$student->getStudentUser->middle_name." ".$student->getStudentUser->last_name}}</a>
              </li>
              <li class="list-group-item">
                <b>Roll No</b> <a class="float-right">{{$student->roll_no}}</a>
              </li>
              <li class="list-group-item">
                <b>Shift</b> <a class="float-right">{{$student->getShift->name}}</a>
              </li>
              <li class="list-group-item">
                <b>Class</b> <a class="float-right">{{$student->getClass->name}}</a>
              </li>
              <li class="list-group-item">
                <b>Section</b> <a class="float-right">{{$student->getSection->name}}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    @endforeach              
    </div>
  </div>
</section>
@endsection
