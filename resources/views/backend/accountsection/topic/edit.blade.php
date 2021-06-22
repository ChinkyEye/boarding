@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }} Edit</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
	<div class="card">
		@foreach($topics as $topic)
		<form role="form" method="POST" action="{{ route('admin.topic.update',$topic->id)}}">
			<div class="card-body">
				@method('PATCH')
				@csrf
				<div class="row">
					<div class="form-group col-md-4">
						<label for="topic" class="control-label col-md-3">Class</label>
						<select class="form-control" name="class_id" id="class_id">
							<option value="">Select Your Class</option>
							@foreach ($classes as $key => $data)
							<option value="{{ $data->id }}" {{ $topic->class_id == $data->id ? 'selected' : ''}}> 
								{{$data->name}}
							</option>
							@endforeach    
						</select>
						@error('class_id')
						<span class="text-danger font-italic" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
					<div class="form-group col-md-4">
						<label for="topic" class="control-label col-md-3">Topic</label>
						<input type="text"  class="form-control @error('topic') is-invalid @enderror max" id="topic" placeholder="Enter topic" name="topic" autocomplete="off" autofocus value="{{ $topic->topic }}">
						@error('topic')
						<span class="text-danger font-italic" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
					<div class="form-group col-md-4">
						<label for="fee" class="control-label col-md-3">Fee</label>
						<input type="text"  class="form-control @error('fee') is-invalid @enderror max" id="fee" placeholder="Enter fee" name="fee" autocomplete="off" autofocus value="{{ $topic->fee }}">
						@error('fee')
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