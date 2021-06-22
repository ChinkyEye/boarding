<div class="h6"><b>Search results:</b> 
@if($shifts) <b>Shift:</b> {{$shifts}} | @endif 
@if($classes) <b>Class:</b> {{$classes}} | @endif 
@if($sections) <b>Section:</b> {{$sections}} | @endif 
@if($dates) <b>Date:</b> {{$dates}} @endif 
<span class="float-right">Total Student : {{$datas->count()}} , Total Present : {{$datas_present}} </span>
</div>
@if(count($datas) != 0)
<button class="btn btn-xs btn-info rounded-0 float-right" onclick="PrintDiv('printTable')">Print me</button>
<div id="printTable">
	<table class="table table-bordered table-hover position-relative m-0">
		<thead class="bg-dark text-center th-sticky-top">
			<tr>
				<th width="100">Roll no</th>
				<th width="20">Mark</th>
				<th width="200">Attendance</th>
				<th class="text-left">Student Name</th>
			</tr>
		</thead>
		@foreach($datas as $key=>$data)             
		<tr class="text-center {{$data ? ($data->status == 1 ? '' : ($data->restatus == 1 ? '' : 'bg-light-danger')) : 'bg-light-warning'}}">
			<td>{{$data->getStudentOne->roll_no}}</td>
			<td>
				@if($data->status == 1)
				<i class="fa fa-check text-success"></i>
				@else
					@if ( $data->restatus == 1)
					<i class="fa fa-check text-success"></i>
					@else
					<i class="fa fa-times text-danger"></i>
					@endif
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

				{{ $data->status == 1 ? 'Present' : ($data->restatus == 1 ? 'Present' : 'Absent') }}
				<input type="hidden" name="student_id[{{$key}}]" value="{{$data->student_id}}">
			</td>
			<td class="text-left">{{$data->getStudentName->name}} {{$data->getStudentName->middle_name}} {{$data->getStudentName->last_name}} ({{$data->getStudentOne->student_code}})</td>
			
		</tr>
		@endforeach
	</table>
	
	@if($dates == date('Y-m-d'))
	{{-- Save --}}
	@endif
	@else
	<div class="text-center h5">No attendance result found for "{{$dates}}"</div>
	@endif
</div>

