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
			<a href="{{ route('admin.section.create')}}" class="btn btn-sm btn-info text-capitalize">Add {{ $page }} </a>
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
						<th class="text-left">Name</th>
						<th width="150">Created By</th>
						<th width="10">Sort</th>
						<th width="10">Publish</th>
						<th width="100">Action</th>
					</tr>
				</thead> 
				<tbody>
					@foreach($sections as $key => $section)
					<tr data-toggle="tooltip" data-placement="top" title="{{ $section->is_active == '1' ? 'This data is published':' This data is not published'}}" style="background-color: {{$section->is_active == '1' ? '#cde0ba' : '#eccdcd'}}">
						<td class="text-center">{{$key + 1}}</td>
						<td>{{$section->name}}</td>
						<td class="text-center">{{$section->getUser->name}}</td>
						<td class="text-center">
							<p id="main{{$section->id}}" ids="{{$section->id}}" class="text-center sort mb-0" page="section" contenteditable="plaintext-only" url="{{route('admin.section.sort',$section->id) }}">{{$section->sort_id}}</p>
						</td>
						<td class="text-center">
							<a href="{{ route('admin.section.active',$section->id)}}" data-toggle="tooltip" data-placement="top" title="{{ $section->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
								<i class="fa {{ $section->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css' }}"></i>
							</a>
						</td>
						<td class="text-center">
							<a href="{{ route('admin.section.edit',$section->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a>
							<form action="{{ route('admin.section.destroy',$section->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
								@csrf
								@method('DELETE')
								<button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
							</form>
						</td>
					</tr>
					@endforeach
				</tbody>             
			</table>
		</div>
	</div>
</section>
@endsection
