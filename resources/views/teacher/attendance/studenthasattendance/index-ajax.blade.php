Total Record: <b> {{$students_count}}</b> | Date: <b> {{$date}}</b>
<div class="table-responsive">
	<table class="table table-bordered table-hover position-relative m-0">
		<thead class="bg-dark text-center th-sticky-top">
			<tr class="th-sticky-top">
				<th width="100">SN</th>
				<th width="100">Roll no</th>
				<th width="200">Attendance</th>
				<th class="text-left">Student Name</th>
				<th class="text-left">Remark</th>
			</tr>
		</thead>
		<tbody>
			@if($check_attendence == '0')
				@if($date == $nepali_date)
					@forelse($students as $index=>$student)
					<tr class="text-center">
						<td>{{$index+1}}</td>
						<td>{{$student->roll_no}}</td>
						<td>
							<input type="hidden" name="student_id[{{$index}}]" value="{{ $student->id }}">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" id="present{{$index}}" value="1" name="status[{{$index}}]" checked>
								<label class="form-check-label" for="present{{$index}}">Present</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" id="absent{{$index}}" value="0" name="status[{{$index}}]">
								<label class="form-check-label" for="absent{{$index}}">Absent</label>
							</div>
						</td>
						<td class="text-left">{{$student->getStudentUser->name}} {{$student->getStudentUser->middle_name}} {{$student->getStudentUser->last_name}} ({{$student->student_code}})</td>
						<td>
							<input type="text" class="w-100 border-0 bg-transparent" name="remark[{{$index}}]" placeholder="Enter here...">
						</td>
					</tr>
					@empty
					<tr class="text-center">
						<td colspan="5">No Student Record found for "<b>{{$date}}</b>" </td>
					</tr>
					@endforelse
				@else
					<tr class="text-center">
						<td colspan="5">No Record found for "<b>{{$date}}</b>" </td>
					</tr>
				@endif
			@else
				@forelse($students as $index=>$student)
				<tr class="text-center">
					<td>{{$index+1}}</td>
					<td>{{$student->getStudent->roll_no}}</td>
						@if($student->status)
					<td>
							<span class="badge badge-success">Present</span>
					</td>
						@else
						<td>
							<span class="badge badge-warning">Absent</span>
						</td>
						@endif
					<td class="text-left">{{$student->getStudentName->name}} {{$student->getStudentName->middle_name}} {{$student->getStudentName->last_name}}</td>
					<td>
						{{$student->remark}}
					</td>
				</tr>
				@empty
				<tr class="text-center">
					<td colspan="5">No Record found for "<b>{{$date}}</b>" </td>
				</tr>
				@endforelse
			@endif
		</tbody>
	</table>
	@if($check_attendence == '0')
		@if($date == $nepali_date)
			@if ($students)
			<button class="btn btn-primary btn-block rounded-0" type="submit" id="std_attend">Save</button>
			@endif
		@endif
	@endif
</div>
