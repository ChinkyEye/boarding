@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
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
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-info">
        <div class="card-body p-0">
          <div class="table-responsive">
           @foreach($periods as $key=>$period) 
           <table class="table m-0">
            <thead>
              <tr>
                <th>SN</th>
                <th>Shift</th>
                <th>Start Time</th>
                <th>End Time</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{$key+1}}</td>
                <td>{{ $period->name }}</td>
                <td><span class="badge badge-success">{{ $period->start_time }}</span></td>
                <td><span class="badge badge-danger">{{ $period->end_time }}</span></td>
              </tr>
            </tbody>
           </table>
           @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection