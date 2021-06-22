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
          <li class="breadcrumb-item"><a href="{{ route('main.book.index',$school_info->slug) }}">Library</a></li>
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
              <th width="10" class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ $books->name }}</td>
              <td>{{ $books->book_no }}</td>
              <td>{{ $books->getClass->name}}</td>
              <td>{{$books->getSubject->name}}</td>
              <td>{{ $books->publisher }}</td>
              <td>{{ $books->auther }}</td>
              <td>{{ $books->quantity }}</td>
              <td class="text-center">
                <i class="fa {{ $books->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

@endsection