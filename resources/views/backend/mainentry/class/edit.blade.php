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
	<div class="card">
		@foreach($classes as $class)
		<form role="form" method="POST" action="{{ route('admin.class.update',$class->id)}}">
			<div class="card-body">
				@method('PATCH')
				@csrf
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="name">Name</label>
						<input type="text"  class="form-control max" id="name" placeholder="Enter name" name="name" maxlength="30" autocomplete="off" value="{{ $class->name }}" autofocus>
						@error('name')
						<span class="text-danger font-italic" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
					<div class="form-group col-sm-6">
						<label for="code">Code <small class="text-danger font-italic">* if requried (code: <u>abcd-1234</u>)</small></label>
						<input type="text"  class="form-control max" id="slug" placeholder="Enter code" name="slug" maxlength="30" autocomplete="off" value="{{ $class->slug }}">
						@error('slug')
						<span class="text-danger font-italic" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
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