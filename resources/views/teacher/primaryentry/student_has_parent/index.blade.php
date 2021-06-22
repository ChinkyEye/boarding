@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 0, strpos((Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('parent.create')}}" class="btn btn-sm btn-info text-capitalize">Add {{ $page }} </a>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="bg-dark">
          <tr class="text-center">
            <th width="10">SN</th>
            <th>Father Name</th>
            <th>Mother Name</th>
            <th width="150">Created By</th>
            <th width="10">Status</th>
            <th width="10">Sort</th>
            <th width="100">Action</th>
          </tr>
        </thead>              
        @foreach($parents as $key=>$parent)             
        <tr data-toggle="tooltip" data-placement="top" title="{{ $parent->is_active == '1' ? 'This data is published':' This data is not published'}}" style="background-color: {{$parent->is_active == '1' ? '#cde0ba' : '#eccdcd'}}">
          <td>{{$key+1}}</td>
          <td>{{$parent->name}}</td>
          <td class="text-center">{{Auth::user()->name}}</td>
          <td class="text-center">
            <a href="{{ route('parent.active',$parent->id) }}">
              <i class="fa {{ $parent->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
            </a>
          </td>
          <td>
            <p id="parent{{$parent->id}}" ids="{{$parent->id}}" class="text-center sort mb-0" contenteditable="plaintext-only">{{$parent->sort_id}}</p>
          </td>
          <td class="text-center" >
            <a href="{{ route('parent.show',$parent->id) }}" class="btn btn-xs btn-outline-success"><i class="fa fa-eye"></i></a>
            <a href="{{ route('parent.edit',$parent->id) }}" class="btn btn-xs btn-outline-info"><i class="fas fa-edit"></i></a>
            <form action="{{ route('parent.destroy',$parent->id) }}" method="post" class="d-inline-block">
              @csrf
              <input name="_method" type="hidden" value="DELETE">
              <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    <div class="card-footer">
      {!! $parents->links("pagination::bootstrap-4") !!}            
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>
@endpush