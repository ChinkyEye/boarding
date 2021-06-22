@extends('teacher.main.app')
@section('content')
<?php $page = 'Homework'; ?>
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
  <div class="card card-info">
    <div class="card-body p-0">
      <div class="table-responsive">
        @foreach($homeworks as $homework)
        <table class="table m-0">
          <thead>
            <tr>
              <th width="200px">Subject Name</th>
              <th>Class Name</th>
              <th width="10" class="text-center">Status</th>
            </tr>
          </thead>
            <tr>
              <td>{{ $homework->getSubject->name }}</td>
              <td>{{ $homework->getsection->name }} | {{ $homework->getClass->name }} | {{ $homework->getShift->name }}</td>
              <td class="text-center">
                <a href="{{ route('teacher.homework.active',$homework->id) }}">
                  <i class="fa {{ $homework->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
                </a>
              </td>
            </tr>
            <tfoot>
              <tr>
                <td colspan="6">{!! $homework->description !!}</td>
              </tr>
            </tfoot>
        </table>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection