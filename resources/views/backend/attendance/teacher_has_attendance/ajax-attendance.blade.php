<div class="card-body">
	<div class="h6">
		<b>Search results:</b> 
		@if($shifts) <b>Shift:</b> {{$shifts}} @endif 
		@if($date) | <b>Date:</b> {{$date}} @endif 
		@if($check_count) | <b>Absent:</b> <span class="badge badge-danger">{{$absent_count}} </span> @endif 
		<button class="btn btn-xs btn-info rounded-0 float-right" onclick="PrintDiv('printTable')">Print me</button>
	</div>
	<input type="hidden" name="shift_id" value="{{$shift_id}}">
	@if(count($datas) != 0)
	<div id="printTable">   
	  <table class="table table-bordered table-hover position-relative m-0" id="printTable">
	  	<thead class="bg-dark text-center th-sticky-top">
	  		<tr>
	  			{{-- <th width="100">Code</th> --}}
	  			<th width="200">Attendance</th>
	  			<th class="text-left">Teacher Name</th>
	  			<th class="text-left">Remark</th>
	  		</tr>
	  	</thead>
	  	@if ($date == $nepali_date)
	  	{{-- today attendance --}}
	  		@if ($check_count)
	  		{{-- teacher attendanc --}}
	  		{{-- {{dd($check_count,$datas)}} --}}
	  			@foreach ($datas as $key => $data)
	  			<tr class="text-center {{$data ? ($data->status == 1 ? '' : 'bg-light-danger') : 'bg-light-warning'}}">
	  				<td>
	  					<div class="form-check form-check-inline">
	  						<input class="form-check-input" type="radio" id="present{{$key}}" value="1" name="status[{{$key}}]" {{$data->status == 1 ? 'checked' : ''}} >
	  						<label class="form-check-label" for="present{{$key}}">Present</label>
	  					</div>
	  					<div class="form-check form-check-inline">
	  						<input class="form-check-input" type="radio" id="absent{{$key}}" value="0" name="status[{{$key}}]" {{ $data->status == 0 ? 'checked' : '' }}>
	  						<label class="form-check-label" for="absent{{$key}}">Absent</label>
	  					</div>
	  					<input type="hidden" name="teacher_id[{{$key}}]" value="{{$data->teacher_id}}">
	  				</td>
	  				<td class="text-left">{{$data->getTeacherName->name}} {{$data->getTeacherName->middle_name}} {{$data->getTeacherName->last_name}} {{"(".$data->getTeacherInfoOne->teacher_code.")"}}</td>
	  				<td>
	  					<input type="text" class="w-100 border-0 bg-transparent" name="remark" placeholder="Enter here..." value="{{$data->remark}}">
	  				</td>
	  			</tr>
	  			@endforeach
	  		@else
	  		{{-- teacher --}}
	  			@forelse ($datas as $key => $data)
	  			<tr class="text-center">
	  				<td>
	  					<div class="form-check form-check-inline">
	  						<input class="form-check-input" type="radio" id="present{{$key}}" value="1" name="status[{{$key}}]" checked>
	  						<label class="form-check-label" for="present{{$key}}">Present</label>
	  					</div>
	  					<div class="form-check form-check-inline">
	  						<input class="form-check-input" type="radio" id="absent{{$key}}" value="0" name="status[{{$key}}]">
	  						<label class="form-check-label" for="absent{{$key}}">Absent</label>
	  					</div>
	  					<input type="hidden" name="teacher_id[{{$key}}]" value="{{$data->teacher_id}}">
	  				</td>
	  				<td class="text-left">{{$data->getTeacherInfo->getTeacherUser->name}} {{$data->getTeacherInfo->getTeacherUser->middle_name}} {{$data->getTeacherInfo->getTeacherUser->last_name}} {{"(".$data->getTeacherInfo->teacher_code.")"}}</td>
	  				<td>
	  					<input type="text" class="w-100 border-0 bg-transparent" name="remark" placeholder="Enter here...">
	  				</td>
	  			</tr>
	  			@empty
	  			<tr>
	  				<td colspan="4" class="text-center">There is no teacher on {{$shifts}} </td>
	  			</tr>
	  			@endforelse
	  		@endif
	  	@else
	  	{{-- teacher attend previous day --}}
	  	{{-- {{dd($check_count , $datas)}} --}}
	  		@if ($check_count)
	  			@forelse ($datas as $key => $data)
	  			<tr class="text-center {{$data ? ($data->status == 1 ? '' : 'bg-light-danger') : 'bg-light-warning'}}">
	  			  <td>
	  			    {{ $date != $nepali_date ? ($data->status == 1 ? 'Presents' : 'Absents') : 'Not Filed' }}
	  			  </td>
	  			  <td class="text-left">{{$data->getTeacherName->name}} {{$data->getTeacherName->middle_name}} {{$data->getTeacherName->last_name}} {{"(".$data->getTeacherInfoOne->teacher_code.")"}}</td>
	  			  <td>
	  			    {{$data->remark}}
	  			  </td>
	  			</tr>
	  			@empty
	  			<tr>
	  				<td colspan="4" class="text-center">No attendance result found for "{{$date}}"</td>
	  			</tr>
	  			@endforelse
	  		@else
	  		<tr>
	  			<td colspan="4" class="text-center">No attendance result found for {!! $shifts ? '"<b>'.$shifts.'</b>"' : "" !!} {!! $date ? 'on "<b>'.$date.'</b>"' : "" !!}</td>
	  		</tr>
	  		@endif
	  	@endif
	  </table>             
	</div>
	
	@if ($date == $nepali_date)

	<button class="btn btn-primary btn-block rounded-0" type="submit">Save</button>
	@endif
	@else
		<div class="text-center h5">No result found for "{{$date}}"</div>
	@endif
</div>