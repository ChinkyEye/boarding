@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <button type="button" class="btn btn-xs btn-info rounded-0" onclick="PrintDiv('printTable')">Print me</button>
  </div>
</section>
<section class="content" id="printTable">
  <div class="container-fluid">
    <div class="row">
      @foreach($students as $key=>$student)
      <div class="col-md-6">
        <div class="card mb-3 card-primary card-outline">
            <div class="card-body border">
              <div class="row mx-auto">
                <div class="col-md-2">
                  <img src="{{URL::to('/')}}/images/main/{{Auth::user()->getSchool->slug}}/{{Auth::user()->getSchool->image}}" class="img-fluid" alt="{{$student->first_name}} Card">
                </div>
                <h5 class="col-md-10 font-weight-bold">
                  {{Auth::user()->getSchool->school_name}}
                  <br>
                  <small>{{ Auth::user()->getSchool->address }} </small>
                </h5>
              </div>
              <div class="my-2 text-center font-weight-bold"><u>{{ $exams }} Admit Card</u></div>
              <div class="row">
                <div class="col-md-4">
                  <img src="{{URL::to('/')}}/images/student/{{$student->slug}}/{{$student->image}}" class="img-fluid img-circle admit-card-user-img" alt="{{$student->first_name}} Card">
                </div>
                <div class="col-md-8">
                  <ul class="list-group list-group-flush card-text">
                    <li class="list-group-item px-0">
                      <b>Student Name</b> <a class="float-right">{{$student->getStudentUser->name." ".$student->getStudentUser->middle_name." ".$student->getStudentUser->last_name}}({{$student->student_code}})</a>
                    </li>
                    <li class="list-group-item px-0">
                      <b>Roll No</b> <a class="float-right">{{$student->roll_no}}</a>
                    </li>
                    <li class="list-group-item px-0">
                      <b>Class</b> <a class="float-right">{{$student->getShift->name}} / {{$student->getClass->name}} / {{$student->getSection->name}}</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
        </div>
      </div> 
      @endforeach           
    </div>
  </div>
</section>
@endsection