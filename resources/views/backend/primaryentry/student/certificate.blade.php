@extends('backend.main.app')
@section('content')
<?php
$page= str_replace('admin.','',Route::currentRouteName());
 ?>
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
<section class="content">
  <button class="btn btn-xs btn-info rounded-0 mt-2" onclick="PrintDiv('printTable')">Print me</button>
  <div class="card" id="printTable">
    <div class="col-md-12 float-sm-right">
      <h4 style="text-align: right"><small>Tel:-{{$students->getSchool->phone_no}}</small></h4>
    </div>
    <h3 class="text-center text-uppercase">{{$students->getSchool->school_name}}</h3>
    <h5 class="text-center">{{$students->getSchool->address}} , Morang (Nepal)</h5>
    {{-- <img src="{{URL::to('/')}}/images/main/{{$students->getSchool->slug}}/{{$students->getSchool->image}}" class="img-fluid mx-auto" style="width: 200px"> --}}
    <div class="text-center">
      <img src="{{URL::to('/')}}/backend/images/school.png" class="img-fluid mx-auto" style="width: 200px">
    </div>
    <div class="row col pb-4">
    	<div class="col-md-3 px-3"><h5>S.No:- <abbr title="">{{$students->roll_no}}/{{$current_date}}</abbr></h5></div>
    	<div class="col-md-6 my-auto"><h3 class="text-center"><strong>CHARACTER CERTIFICATE</strong></h3></div>
    	<div class="col-md-3 my-auto"><img src="{{URL::to('/')}}/images/student/{{$students->slug}}/{{$students->image}}" class="img-responsive float-sm-right img-thumbnail" style="width: 100px;"></div>
    </div>
    <div class="col">
    	<p class="font-weight-bold bitch" ><span class="pl-5">This</span> is to certify that MR/Miss/Mrs. <abbr title="">{{$students->getStudentUser->name}} {{$students->getStudentUser->middle_name}} {{$students->getStudentUser->last_name}}</abbr title=""> son/daughter of <abbr title="">{{$students->Student_has_parent->father_name}}</abbr>, inhabitant of <abbr title="">{{$students->address}}</abbr> VDC/Municipality Morang District Koshi Zone was  bonafide student of this school. He/She has successfully passed the class <abbr title="">{{$students->getClass->name}}</abbr> examination of the year <abbr title="">{{$students->getBatch->name}}</abbr> B.S / A.D. According to the School record his/her date of birth is <abbr title="">{{$students->dob}}</abbr> A.D.He/She bears a good moral character.<br>
    		<span class="pl-5">I wish him/her every success in his/her endeavours</span></p>
    </div>
    <div class="col row">
      <div class="col-md-5">
        <div class="form-group">
          <label class="form-label" style="font-family: monospace;">Date of Issue:- {{$current_date}}</label>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group row">
          <label class="form-label" style="font-family: monospace;">Prepared By:</label>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group row">
          <label class="form-label" style="font-family: monospace;">Principal:</label>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection