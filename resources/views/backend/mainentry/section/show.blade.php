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
		<div class="card-body">
			<div class="form-group">
				<label for="name">Name: </label>
				<input type="text" class="form-control" name="name" id="name" readonly="true" value="{{$data->name}}">
			</div>
			<div class="form-group">
				<label for="Created By">Created By: </label>
				<input type="text" class="form-control" name="created_by" id="created_by" readonly="true" value="{{$data->getUser->name}}">
			</div>
			<div class="form-group">
				<label for="Updated By">Updated By: </label>
				<input type="text" class="form-control" name="updated_by" id="updated_by" readonly="true" value="{{$data->getUser->name}}">
			</div>
		</div>
	</div>
</section>
@endforeach
@endsection