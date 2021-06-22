<div class="h6"><b>Search results:</b> 
@if($shifts) <b>Shift:</b> {{$shifts}} | @endif 
@if($classes) <b>Class:</b> {{$classes}} | @endif 
@if($sections) <b>Section:</b> {{$sections}} | @endif 
@if($exams) <b>Exam:</b> {{$exams}} @endif 
</div>
<table class="table table-bordered table-hover position-relative m-0">
	<thead class="bg-dark text-center th-sticky-top">
		<tr>
			<th width="100">Roll no</th>
			<th class="text-left">Student Name</th>
			<th width="20">Email</th>
			<th width="20">Status</th>
			<th width="200">Action</th>
		</tr>
	</thead>
	@foreach($datas as $key=>$data)             
	<tr class="text-center">
		<td>{{$data->roll_no}}</td>
		<td class="text-left">{{$data->getStudentUser->name}} {{$data->getStudentUser->middle_name}} {{$data->getStudentUser->last_name}} ({{$data->student_code}})</td>
		<td>{{$data->getStudentUser->email}}</td>
		<td></td>
		<td class="text-center">
		  <a target="_blank" href="{{ route('teacher.studenthasmark',[$data->slug,$exam]) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Show Detail"><i class="fas fa-plus"></i></a>
		</td>
	</tr>
	@endforeach
</table>
	<!-- <button class="btn btn-primary btn-block rounded-0" type="submit">Save</button> -->
<!-- <div class="text-center h5">No attendance result found for "{{$shifts}}"</div> -->
