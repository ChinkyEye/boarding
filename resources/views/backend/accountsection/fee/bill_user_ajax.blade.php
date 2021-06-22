
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
	<tr class="text-center">
		<td>{{$user_datas->getUserStudent->roll_no}}</td>
		<td class="text-left">{{$user_datas->name}}</td>
		<td>{{$user_datas->email}}</td>
		<td></td>
		<td class="text-center">
		  <a target="_blank" href="{{ route('admin.fee.student',[$user_datas->getUserStudent->slug]) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Show Detail"><i class="fas fa-plus"></i></a>
		  <a target="_blank" href="{{ route('admin.fee.viewstudentfee',[$user_datas->getUserStudent->slug]) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Show Detail"><i class="fas fa-eye"></i></a>
		</td>
	</tr>
</table>

