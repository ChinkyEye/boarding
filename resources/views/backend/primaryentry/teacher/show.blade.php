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
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">About Me</h3>
          </div>

          <div class="card-body">
            <strong><i class="fas fa-book mr-1"></i> Education</strong>
            <p class="text-muted">
              {{$main_datas->qualification}}
            </p>
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
