@extends('main.main.app')
@section('content')
<?php $page = request()->segment(count(request()->segments())) ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('teacher.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('main.school.create')}}" class="btn btn-sm btn-info text-capitalize">Add {{ $page }} </a>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover m-0">
        <thead class="bg-secondary"> 
            <tr class="text-center">
                <th style="width: 10px">#</th>
                <th class="text-left">School Name</th>
                <th class="text-left">Address</th>
                <th>Create</th>
                <th>Status</th>
                <th>Action</th>
                <!-- <th>Created At</th> -->
            </tr>                 
        </thead>
        <tbody>
            @foreach($settings as $key => $school)
            <tr>
                <td>{{$key + 1}}</td>
                <td>{{$school->school_name}}</td>
                <td>{{$school->address}}</td>
                <td class="text-center">
                  @if($school->getCountSection()->count() == 0)
                    <a href="{{ route('main.create-admin',$school->slug)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip"  title="Create Admin"><i class="fa fa-plus"></i></a>
                    @else
                    <a href="{{ route('main.create-admin',$school->slug)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip"  title="Create Admin"><i class="fa fa-check"></i></a>
                  @endif
                </td>
                <td class="text-center">
                  <a class='d-block text-center' href="{{ route('main.school.isactive',$school->id) }}" data-toggle='tooltip' data-placement='top' title="{{ $school->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
                    <i class="fa {{ $school->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>                  
                  </a>
                </td>
                <td class="text-center">
                    <a href="{{ route('main.school.show',$school->slug) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Show Detail"><i class="fas fa-eye"></i></a>
                    <!-- <a href="{{ route('main.school.edit',$school->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a> -->
                    <form action="{{ route('main.school.destroy',$school->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
                <!-- <td>{{$school->getUser->name}}</td> -->
            </tr>
            @endforeach
        </tbody>              
        <!--  -->
      </table>
    </div>
    <div class="card-footer">
    </div>
  </div>
</section>
@endsection

