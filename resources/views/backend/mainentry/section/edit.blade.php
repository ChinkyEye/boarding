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
@foreach($sections as $data)
<section class="content">
	<div class="card">
		<form role="form" method="POST" action="{{route('admin.section.update', $data->id)}}">
			<div class="card-body">
				@method('PATCH')
		        @csrf
				<div class="form-body">
					<div class="form-group">
						<label for="name">Name: </label>
						<input type="text" class="form-control" name="name" id="name" placeholder="Enter name" autocomplete="off" value="{{$data->name}}" autofocus>
						@error('name')
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
	</div>
</section>
@endforeach
@endsection