@extends('backend.main.app')
@push('style')
<style type="text/css" media="print">
  @page { 
  	size: auto;    auto is the initial value 
    size: A4 portrait;
    margin: 26px 0px 534px 0px;   this affects the margin in the printer settings
    border: 1px solid red;   set a border for all printed pages 
  }
</style>
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <button class="btn btn-xs btn-info rounded-0" onclick="PrintDiv('printTable')">Print me</button>
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
    <div class="row" id="printTable">
      
     @foreach($students as $key=>$student)
      <div class="col-md-6">
      	<div class="card">
      		<div class="col-md-12 float-sm-right">
      			<h4 style="text-align: right"><small>Tel:-{{$student->getStudentBatch->phone_no}}</small></h4>
      		</div>
      		<h3 class="text-center text-uppercase">{{$student->getStudentBatch->school_name}}</h3>
      		<h5 class="text-center">{{$student->getStudentBatch->address}} , Morang (Nepal)</h5>
      		<div class="text-center">
      			<img src="{{URL::to('/')}}/backend/images/school.png" class="img-fluid mx-auto" style="width: 200px">
      		</div>
      		<div class="row col pb-4">
      			<div class="col-md-3 px-3"><h5>S.No:- <abbr title="">{{$student->getStudentBatch->roll_no}}/{{$current_date}}</abbr></h5></div>
      			<div class="col-md-6 my-auto"><h3 class="text-center"><strong>CHARACTER CERTIFICATE</strong></h3></div>
      			<div class="col-md-3 my-auto"><img src="{{URL::to('/')}}/images/student/{{$student->getStudentBatch->slug}}/{{$student->getStudentBatch->image}}" class="img-responsive float-sm-right img-thumbnail" style="width: 100px;"></div>
      		</div>
      		<div class="col">
            <p class="font-weight-bold bitch" ><span class="pl-5">This</span> is to certify that MR/Miss/Mrs. <abbr title="">{{$student->getStudentUserBatch->name}} {{$student->getStudentUserBatch->middle_name}} {{$student->getStudentUserBatch->last_name}}</abbr title=""> son/daughter of <abbr title="">{{-- {{$student->Student_has_parent->father_name}} --}}</abbr>, inhabitant of <abbr title="">{{$student->address}}</abbr> VDC/Municipality Morang District Koshi Zone was  bonafide student of this school. He/She has successfully passed the class <abbr title="">{{-- {{$student->getClass->name}} --}}</abbr> examination of the year <abbr title="">{{-- {{$student->getBatch->name}} --}}</abbr> B.S / A.D. According to the School record his/her date of birth is <abbr title="">{{-- {{$student->dob}} --}}</abbr> A.D.He/She bears a good moral character.<br>
              <span class="pl-5">I wish him/her every success in his/her endeavours</span></p>
      			</div>
      			<div class="col-md-12 pb-2">
      				<div class="row">
      					<div class="form-group col">
      						<label class="form-label" style="font-family: monospace;">Date of Issue:- <abbr title="">{{$current_date}}</abbr></label>
      					</div>
      					<div class="form-group col">
      						<label class="form-label" style="font-family: monospace;">Prepared By:</label>

      					</div>
      					<div class="form-group col">
      						<label class="form-label" style="font-family: monospace;">Principal:</label>
      					</div>
      				</div>
      			</div>
      		</div>
      </div>
      {{-- <div class="col-md-6">
        <div class="card">
          <div class="col-md-12 float-sm-right">
            <h4 style="text-align: right"><small>Tel:-{{$student->getSchool->phone_no}}</small></h4>
          </div>
          <h3 class="text-center text-uppercase">{{$student->getSchool->school_name}}</h3>
          <h5 class="text-center">{{$student->getSchool->address}} , Morang (Nepal)</h5>
          <div class="text-center">
            <img src="{{URL::to('/')}}/backend/images/school.png" class="img-fluid mx-auto" style="width: 200px">
          </div>
          <div class="row col pb-4">
            <div class="col-md-3 px-3"><h5>S.No:- <abbr title="">{{$student->roll_no}}/{{$current_date}}</abbr></h5></div>
            <div class="col-md-6 my-auto"><h3 class="text-center"><strong>CHARACTER CERTIFICATE</strong></h3></div>
            <div class="col-md-3 my-auto"><img src="{{URL::to('/')}}/images/student/{{$student->slug}}/{{$student->image}}" class="img-responsive float-sm-right img-thumbnail" style="width: 100px;"></div>
          </div>
          <div class="col">
            <p class="font-weight-bold bitch" ><span class="pl-5">This</span> is to certify that MR/Miss/Mrs. <abbr title="">{{$student->getStudentUser->name}} {{$student->getStudentUser->middle_name}} {{$student->getStudentUser->last_name}}</abbr title=""> son/daughter of <abbr title="">{{$student->Student_has_parent->father_name}}</abbr>, inhabitant of <abbr title="">{{$student->address}}</abbr> VDC/Municipality Morang District Koshi Zone was  bonafide student of this school. He/She has successfully passed the class <abbr title="">{{$student->getClass->name}}</abbr> examination of the year <abbr title="">{{$student->getBatch->name}}</abbr> B.S / A.D. According to the School record his/her date of birth is <abbr title="">{{$student->dob}}</abbr> A.D.He/She bears a good moral character.<br>
              <span class="pl-5">I wish him/her every success in his/her endeavours</span></p>
            </div>
            <div class="col-md-12 pb-2">
              <div class="row">
                <div class="form-group col">
                  <label class="form-label" style="font-family: monospace;">Date of Issue:- <abbr title="">{{$current_date}}</abbr></label>
                </div>
                <div class="form-group col">
                  <label class="form-label" style="font-family: monospace;">Prepared By:</label>

                </div>
                <div class="form-group col">
                  <label class="form-label" style="font-family: monospace;">Principal:</label>
                </div>
              </div>
            </div>
          </div>
      </div> --}}
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