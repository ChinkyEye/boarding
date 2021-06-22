@extends('library.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 8, strpos(str_replace('library.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('library.home')}}">Home</a></li>
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
       @foreach($books as $book)
        <table class="table m-0">
          <thead>
            <tr>
              <th>Book Name</th>
              <th>Book No</th>
              <th>Class</th>
              <th>Subject</th>
              <th>Publisher</th>
              <th>Auther</th>
              <th>Quantity</th>
              <th>Created by</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ $book->name }}</td>
              <td>{{ $book->book_no }}</td>
              <td>{{ $book->getClass->name}}</td>
              <td>{{$book->getSubject->name}}</td>
              <td>{{ $book->publisher }}</td>
              <td>{{ $book->auther }}</td>
              <td>{{ $book->quantity }}</td>
              <td>{{ $book->getUser->name }}</td>
            </tr>
          </tbody>
        </table>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection