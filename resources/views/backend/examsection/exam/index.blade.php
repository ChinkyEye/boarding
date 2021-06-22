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
  <div class="card">
    <div class="card-header">
      <a href="{{ route('admin.exam.create')}}" class="btn btn-sm btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Add exam">Add {{ $page }} </a>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-hover m-0">
          <thead class="bg-dark">
            <tr class="text-center">
              <th width="10">SN</th>
              <th class="text-left">Name</th>
              <th class="text-center">Exam Date Duration</th>
              <th width="10">Manage</th>
              <th width="100">Action</th>
            </tr>
          </thead>
           @foreach($exams as $key => $exam)
          <tr class="text-center">
            <td>{{$key + 1}}</td>
            <td class="text-left">{{$exam->name}}</td>
            <td>{{$exam->start_date}} &nbsp to &nbsp  {{$exam->end_date}}</td>
            <td>
              <a href="{{route('admin.examhasclass',$exam->slug)}}" class="btn btn-xs btn-outline-{{$exam->getExamClass->count() == 0 ? 'danger' : 'success'}}" data-toggle="tooltip"  title="Manage Exam">{{$exam->getExamClass->count()}} / {{$all_class_section->count()}} <i class="fa fa-plus"></i></a>
            </td>
            <td>
              <a href="{{ route('admin.exam.show',$exam->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Show Detail"><i class="fas fa-eye"></i></a>
              <a href="{{ route('admin.exam.edit',$exam->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a>
              <form action="{{ route('admin.exam.destroy',$exam->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
                @csrf
                <input name="_method" type="hidden" value="DELETE">
                <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
              </form>
            </td>
          </tr> 
          @endforeach            
        </table>
      </div>
    </div>
  </div>
</section>
@endsection