@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <div class="card-body p-0">
      <div class="table-responsive">
       @foreach($issuebooks as $issuebook)
        <table class="table m-0">
          <thead>
            <tr>
              <th>Student Name</th>
              <th>Book Name</th>
              <th>Shift</th>
              <th>Class</th>
              <th>Section</th>
              <th>Issue Date</th>
              <th>Return Date</th>
              <th>Issued by</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ $issuebook->getStudent->getStudentUser->name }} {{ $issuebook->getStudent->getStudentUser->middle_name }} {{ $issuebook->getStudent->getStudentUser->last_name }} ({{ $issuebook->getStudent->student_code }})</td>
              <td>{{ $issuebook->getBook->name }}</td>
              <td>{{ $issuebook->getShift->name }}</td>
              <td>{{ $issuebook->getClass->name }}</td>
              <td>{{ $issuebook->getSection->name }}</td>
              <td>{{ $issuebook->issue_date}}</td>
              <td>{{ $issuebook->return_date}}</td>
              <td>{{ $issuebook->getUser->name}}</td>
            </tr>
          </tbody>
        </table>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection