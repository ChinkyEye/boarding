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
    @foreach($nationalities as $nationality)
    <form role="form" method="POST" action="{{ route('admin.nationality.update',$nationality->id)}}">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text"  class="form-control max" id="n_name" placeholder="Enter Nationality" name="n_name" required="true" maxlength="30" autocomplete="off" value="{{ $nationality->n_name }}">
          @error('n_name')
          <span class="text-danger font-italic" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
    @endforeach
  </div>
</section>
@endsection