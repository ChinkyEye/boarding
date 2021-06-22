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
<section class="content">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('admin.nationality.create')}}" class="btn btn-sm btn-info text-capitalize">Add {{ $page }} </a>
       {{-- <a href="{{ route('admin.nationality.export')}}" class="btn btn-sm btn-info text-capitalize">Export</a> --}}
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover m-0">
        <thead class="bg-dark">
          <tr class="text-center">
            <th width="10">SN</th>
            <th class="text-left">Nationality</th>
            <th width="150">Created By</th>
            <th width="10">Sort</th>
            <th width="10">Status</th>
            <th width="100">Action</th>
          </tr>
        </thead>              
        @foreach($nationalities as $key=>$nationality)             
        <tr class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $nationality->is_active == '1' ? 'This data is published':' This data is not published'}}" style="background-color: {{$nationality->is_active == '1' ? '#cde0ba' : '#eccdcd'}}">
          <td>{{$key+1}}</td>
          <td class="text-left">{{$nationality->n_name}}</td>
          <td>{{$nationality->getUser->name}}</td>
          <td>
            <p id="main{{$nationality->id}}" ids="{{$nationality->id}}" class="text-center sort mb-0" page="nationality" contenteditable="plaintext-only" url="{{route('admin.nationality.sort',$nationality->id) }}">{{$nationality->sort_id}}</p>
          </td>
          <td>
            <a href="{{ route('admin.nationality.active',$nationality->id) }}" data-toggle="tooltip" data-placement="top" title="{{ $nationality->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
              <i class="fa {{ $nationality->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
            </a>
          </td>
          <td >
            {{-- <a href="{{ route('admin.nationality.show',$nationality->id) }}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a> --}}
            <a href="{{ route('admin.nationality.edit',$nationality->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a>
            <form action="{{ route('admin.nationality.destroy',$nationality->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
              @csrf
              @method('DELETE')
              <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    <div class="card-footer">
      {!! $nationalities->links("pagination::bootstrap-4") !!}            
    </div>
  </div>
</section>
@endsection
