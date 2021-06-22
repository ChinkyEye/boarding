@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <p>This Student has Printed Idcard {{$studentcount}} times</p>
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
      <form action="{{route('admin.student.count')}}" method="POST" class="text-right">
        {{ csrf_field() }}
        <input type="hidden" name="student_id" id="student_id"  value="{{$id}}">
        <input type="submit" name="submit" value="Print" class="btn btn-info" onclick='idCardCount()'>
      </form>
     @foreach($students as $key=>$student)
      <div class="col-md-4">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <h3 class="profile-username text-center font-weight-bold">Id Card</h3>
            <h3 class="profile-username text-center font-weight-bold">Ganga Memorial Secondary School</h3>
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle"
              src="{{URL::to('/')}}/backend/images/user.png"
              alt="Student profile picture">
            </div>
            <div class="d-block text-center text-muted">
              <br>
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
@push('javascript')
<script>
  function idCardCount(){
      $.ajax({
        type: "post",
        url: "{{route('admin.student.count')}}",
        data:{ _token: "{{csrf_token()}}"},
        dataType: 'json',
      });
  }
</script>
@endpush
