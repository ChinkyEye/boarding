@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <p class="text-danger m-0">The Grade Max and Min must be from 100% to 0%.</p>
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
      <a href="{{ route('admin.grade.create')}}" class="btn btn-sm btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Add Grade">Add {{ $page }} </a>
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
              <th class="text-left">Min</th>
              <th class="text-left">Max</th>
              <th class="text-left">Grade Point</th>
              <th width="10">Status</th>
              <th width="100">Action</th>
            </tr>
          </thead>
           @foreach($grades as $key => $grade)
          <tr>
            <td>{{$key + 1}}</td>
            <td>{{$grade->name}}</td>
            <td>{{$grade->min}}</td>
            <td>{{$grade->max}}</td>
            <td>{{$grade->grade_point}}</td>
            <td class="text-center">
              <a href="{{ route('admin.grade.active',$grade->id) }}" data-toggle="tooltip" data-placement="top" title="{{ $grade->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
              <i class="fa {{ $grade->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
            </a>
            </td>
            <td class="text-center">
              {{-- <a href="{{ route('admin.grade.show',$grade->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Show Detail"><i class="fas fa-eye"></i></a> --}}
              <a href="{{ route('admin.grade.edit',$grade->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a>
              <form action="{{ route('admin.grade.destroy',$grade->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
                @csrf
                @method('DELETE')
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