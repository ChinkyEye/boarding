@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <form action="{{route('admin.student.count')}}" method="POST" class="d-inline-block">
          {{ csrf_field() }}
          <input type="hidden" name="user_id" id="user_id"  value="{{$user_id}}">
          <input type="hidden" name="category" id="category"  value="idcard">
          <input type="submit" name="submit" value="Print" class="btn btn-info" onclick='idCardCount()'>
        </form>
        <span>This Student has Printed Idcard {{$studentcount}} times</span>
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
                <b>Student Name</b> <a class="float-right">{{$student->first_name." ".$student->middle_name." ".$student->last_name}}</a>
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
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/jquery.printPage.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$('.btnprn').printPage();
});
</script>
@endpush
