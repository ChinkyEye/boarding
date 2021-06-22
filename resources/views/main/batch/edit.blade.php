@extends('main.main.app')
@section('content')
<?php $page = 'Batch'; ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    @foreach($batchs as $batch)
    <form role="form" method="POST" action="{{ route('main.batch.update',$batch->id)}}">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="form-group">
          <label for="name">Batch</label>
          <input type="text"  class="form-control max" id="name" placeholder="Enter name" name="name" required="true" maxlength="30" autocomplete="off" value="{{ $batch->name }}">
          @error('name')
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