@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">You are Managing Class {{$class_name}}</h1>
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
      <form role="form" method="POST" action="{{route('admin.c_has_shift.store')}}"  class="validate" id="validate">
        @csrf
        <input type="hidden" name="class_id" value="{{$class_id}}">
        <div class="row">
          <div class="form-group m-0 col">
            <select class="form-control" name="shift_id" id="filter_shift">
              <option value=" ">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
                {{$shift->name}}
              </option>
              @endforeach    
            </select>
            @error('shift_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="col-1">
            <button type="submit" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" title="Save">Save</button>
          </div>
        </div>
      </form>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="bg-dark">
          <tr class="text-center">
            <th width="10">SN</th>
            <th class="text-left">Shift</th>
            <th class="text-left">Class</th>
            <th width="150">Created By</th>
            <th width="10">Status</th>
            <th width="100">Action</th>
          </tr>
        </thead>              
        @foreach($classhasshifts as $key=>$classshift)             
        <tr class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $classshift->is_active == '1' ? 'This data is published':' This data is not published'}}" style="background-color: {{$classshift->is_active == '1' ? '#cde0ba' : '#eccdcd'}}">
          <td>{{$key+1}}</td>
          <td class="text-left">{{$classshift->getShift->name}}</td>
          <td class="text-left">{{$classshift->getClass->name}}</td>
          <td>{{$classshift->getUser->name}}</td>
          <td>
            <a href="{{route('admin.c_has_shift.active',$classshift->id)}}" data-toggle="tooltip" data-placement="top" title="{{ $classshift->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
              <i class="fa {{ $classshift->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
            </a>
          </td>
          <td>
            {{-- <a href="{{ route('admin.c_has_shift.edit',$classshift->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a> --}}
            <form action="{{ route('admin.c_has_shift.destroy',$classshift->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
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
</section>
@endsection
@push('javascript')
<script src="{{url('/')}}/backend/js/jquery.dataTables.js"></script>
<script src="{{url('/')}}/backend/js/dataTables.bootstrap4.js"></script>
@endpush