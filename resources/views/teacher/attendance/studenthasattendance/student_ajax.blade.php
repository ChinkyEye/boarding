<div class="h6"><b>Search results:</b> 
@if($dates) <b>Date:</b> {{$dates}} @endif 
</div>
@if(count($datas) != 0)
<table class="table table-bordered table-hover position-relative m-0">
	<thead class="bg-dark text-center th-sticky-top">
		<tr>
			<th width="100">Roll no</th>
			<th width="20">Mark</th>
			<th width="200">Attendance</th>
			<th class="text-left">Student Name</th>
			<th class="text-left">Remark</th>
		</tr>
	</thead>
	@foreach($datas as $key=>$data)             
	<tr class="text-center {{$data ? ($data->status == 1 ? '' : 'bg-light-danger') : 'bg-light-warning'}}">
		<td>{{$data->roll_no}}</td>
		<td>
			@if($dates != date('Y-m-d'))
				@if($data->status == 1)
				<i class="fa fa-check text-success"></i>
				@else
				<i class="fa fa-times text-danger"></i>
				@endif
			@else
				<i class="fa fa-circle"></i>
			@endif
		</td>
		<td>
			{{-- @if($dates == date('Y-m-d'))
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" id="present{{$key}}" value="1" name="status[{{$key}}]" {{ !empty($data) ? ($data->status == 1 ? 'checked' : '') : 'checked'}}>
					<label class="form-check-label" for="present{{$key}}">Present</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" id="absent{{$key}}" value="0" name="status[{{$key}}]" {{ !empty($data) ? ($data->status == 0 ? 'checked' : '') : ''}}>
					<label class="form-check-label" for="absent{{$key}}">Absent</label>
				</div>
				
			@else
				{{ !empty($data) != 0 ? ($data->status == 1 ? 'Presents' : 'Absents') : 'Not Filed' }}
			@endif --}}

			{{ $dates != date('Y-m-d') ? ($data->status == 1 ? 'Presents' : 'Absents') : 'Not Filed' }}
			<input type="text" name="student_id[{{$key}}]" value="{{$data->id}}">
			{{-- <input type="text" name="student_id[{{$key}}]" value="3"> --}}

		</td>
		<td class="text-left">{{$data->getStudentUser->name}} {{$data->getStudentUser->middle_name}} {{$data->getStudentUser->last_name}}</td>
		<td>
			<input type="text" class="w-100 border-0 bg-transparent" name="remark" placeholder="Enter here...">
		</td>
	</tr>
	@endforeach
</table>
@if($dates == date('Y-m-d'))
	<button class="btn btn-primary btn-block rounded-0" type="submit">Save</button>
@endif
@else
<div class="text-center h5">No attendance result found for "{{$dates}}"</div>
@endif
