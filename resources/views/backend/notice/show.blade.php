@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
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
       @foreach($notices as $notice)
        <table class="table m-0">
          <thead>
            <tr>
              <th width="20">ID</th>
              <th>Title</th>
              <th>Description</th>
              {{-- <th>Class</th> --}}
              {{-- <th>Section</th> --}}
              <th width="10" class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><a>OR9842</a></td>
              <td>{{ $notice->title }}</td>
              <td>{{ $notice->description }}</td>
              {{-- <td></td> --}}
              {{-- <td></td> --}}
              <td class="text-center">
                <a href="">
                  <i class="fa {{ $notice->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection