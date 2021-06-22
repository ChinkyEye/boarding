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
			<a href="{{ route('admin.class.create')}}" class="btn btn-sm btn-info text-capitalize">Add {{ $page }} </a>
			<div class="card-tools">
				<button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
				<button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
				<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
				<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-hover m-0 w-100">
				<thead class="bg-dark">
					<tr class="text-center">
						<th width="10">SN</th>
						<th class="text-left">Name</th>
						<th width="150">Created By</th>
						<th width="10">Sort</th>
						<th width="10">Status</th>
						<th width="100">Action</th>
						<th width="100">Manage</th>
					</tr>
				</thead> 
				<tbody>
					@foreach($classes as $key => $class)
					<tr class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $class->is_active == '1' ? 'This data is published':' This data is not published'}}" style="background-color: {{$class->is_active == '1' && $class->getClassList()->count() != 0 && $class->getClassSectionList()->count() != 0 ? '#cde0ba' : '#eccdcd'}}">
						<td>{{$key + 1}}</td>
						<td class="text-left">
							<a href="{{route('admin.subject',$class->slug)}}">
								{{$class->name}} <span class="badge badge-{{$class->getClassSubject()->count() == 0 ? 'danger' : 'success'}}">{{$class->getClassSubject()->count()}}</span>
							</a>
						</td>
						<td>{{$class->getUser->name}}</td>
						<td>
							<p id="main{{$class->id}}" ids="{{$class->id}}" class="text-center sort mb-0" page="class" contenteditable="plaintext-only" url="{{route('admin.class.sort',$class->id) }}">{{$class->sort_id}}</p>
						</td>
						<td>
							<a href="{{ route('admin.class.active',$class->id) }}" data-toggle="tooltip" data-placement="top" title="{{ $class->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
								<i class="fa {{ $class->is_active == '1' ? 'fa-check check-css':'fa-times cross-css'}}"></i>
							</a>
						</td>
						<td>
							{{-- <a href="{{ route('admin.class.show',$class->id) }}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a> --}}
							<a href="{{ route('admin.class.edit',$class->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update"><i class="fas fa-edit"></i></a>
							<form action="{{ route('admin.class.destroy',$class->id) }}" method="post" class="d-inline-block" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
								@csrf
								<input name="_method" type="hidden" value="DELETE">
								<button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
							</form>
						</td>
						<td>
							<a href="{{ route('admin.class_has_shift',$class->slug) }}" class="btn btn-xs btn-outline-{{$class->getClassList()->count() == 0 ? 'danger' : 'success'}}" data-toggle="tooltip" data-placement="top" title="Manage Shift">{{$class->getClassList()->count()}} <i class="fas fa-store-alt"></i></a>
							@if($class->getClassList()->count() >= 1)
							<a href="{{ route('admin.class_has_section',$class->slug) }}" class="btn btn-xs btn-outline-{{$class->getClassSectionList()->count() == 0 ? 'danger' : 'info'}}" data-toggle="tooltip" data-placement="top" title="Manage Section">{{$class->getClassSectionList()->count()}} <i class="fas fa-building"></i></a>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>             
			</table>
		</div>
		<div class="card-footer">
			{!! $classes->links("pagination::bootstrap-4") !!}            
		</div>
	</div>
</section>
@endsection