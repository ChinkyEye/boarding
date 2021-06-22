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
          <li class="breadcrumb-item"><a href="{{ route('main.teacher.index',$school_info->slug) }}">Teacher</a></li>
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
              <img class="profile-user-img img-fluid img-circle"
              src="{{URL::to('/')}}/images/teacher/{{$teachers_detail->slug}}/{{$teachers_detail->image}}"
              alt="User profile picture">
            </div>

            <h3 class="profile-username text-center">{{ $teachers->name }} {{ $teachers->middle_name }} {{ $teachers->last_name }}</h3>
            <div class="d-block text-center text-muted">
              <span>{{$teachers_detail->email}}</span>
              <br>
              <span>{{$teachers_detail->phone}}</span>
            </div>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Teacher Code</b> <a class="float-right">{{$teachers_detail->teacher_code}}</a>
              </li>
              <li class="list-group-item">
                <b>Designation</b> <a class="float-right">{{$teachers_detail->designation}}</a>
              </li>
              <li class="list-group-item">
                <b>Joining Date</b> <a class="float-right">{{$teachers_detail->j_date}}</a>
              </li>
              <li class="list-group-item">
                <b>Marital Status</b> <a class="float-right">{{$teachers_detail->marital_status}}</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">About {{$teachers_detail->f_name}} {{substr($teachers_detail->m_name, 0, 1)}} {{substr($teachers_detail->l_name, 0, 1)}}</h3>
          </div>

          <div class="card-body">
            <strong><i class="fas fa-book mr-1"></i> Education</strong>
            <p class="text-muted">
              {{$teachers_detail->qualification}}
            </p>
            <strong><i class="fas fa-book mr-1"></i> Teacher Designation</strong>
            <p class="text-muted">
              {{$teachers_detail->t_designation}}
            </p>
            <hr>
            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
            <p class="text-muted">{{$teachers_detail->address}}</p>
            <hr>
            <strong><i class="fas fa-pencil-alt mr-1"></i> Training</strong>
            <p class="text-muted">
              <span class="tag tag-danger">{{$teachers_detail->training}}</span>
            </p>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
