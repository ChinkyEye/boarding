<div class="card-body">
	<div class="h6"><b>Search results:</b> 
		@if($shifts) <b>Shift:</b> {{$shifts}} @endif 
		@if($classes) | <b>Class:</b> {{$classes}} @endif 
		@if($sections) | <b>Section:</b> {{$sections}} @endif 
		@if($dates) | <b>Date:</b> {{$dates}} @endif 
		@if(count($datas)) | <b>Absent:</b> <span class="badge badge-danger">{{$absent_count}} </span> @endif 
		<div class="float-right">
			<button type="button" class="btn btn-xs btn-info rounded-0" onclick="PrintDiv('printTable')">Print me</button>
		</div>
	</div>
	@if(count($datas) != 0)
	<div id="printTable">
		<table class="table table-bordered table-hover position-relative m-0" id="printTable">
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
				<td>{{$data->getStudentOne->roll_no}}</td>
				<td>
					@if($dates != $nepali_date)
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
					@if($dates == $nepali_date)
					{{-- today --}}
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" id="present{{$key}}" value="1" name="status[{{$key}}]" {{ $data->status == 1 ? 'checked' : ''}}>
						<label class="form-check-label" for="present{{$key}}">Present</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" id="absent{{$key}}" value="0" name="status[{{$key}}]" {{ $data->status == 0 ? 'checked' : ''}}>
						<label class="form-check-label" for="absent{{$key}}">Absent</label>
					</div>
					<input type="hidden" name="student_id[{{$key}}]" value="{{$data->student_id}}">
					<input type="hidden" name="date[{{$key}}]" value="{{$data->date_en}}">
					@else
					{{-- other day --}}
					{{ !empty($data) != 0 ? ($data->status == 1 ? 'Presents' : 'Absents') : 'Not Filed' }}
					@endif
				</td>
				<td class="text-left">{{$data->getStudentName->name}} {{$data->getStudentName->middle_name}} {{$data->getStudentName->last_name}}({{$data->getStudentOne->student_code}})</td>
				<td>
					<input type="text" class="w-100 border-0 bg-transparent" name="remarks[]" placeholder="Enter here..." value="{{$data->remark}}">
				</td>
			</tr>
			@endforeach
		</table>
	</div>
	
	@if($dates == $nepali_date)
	<button class="btn btn-primary btn-block rounded-0" type="submit">Save</button>
	@endif
	@else
	<div class="text-center h5">No attendance result found for "{{$dates}}"</div>
	@endif
</div>