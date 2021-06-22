<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>
<div id="printDiv">
  <table class="table table-bordered table-hover position-relative m-0">
    <thead class="bg-dark text-center th-sticky-top">
      <tr>
        <th width="10">SN</th>
        <th class="text-left">Teacher</th>
        <th>Date/Time</th>
        <th width="150">Status</th>
      </tr>
    </thead>              
    @foreach ($teacherattendance_list as $key=>$element)
      <tr class="text-center">
        <td>{{$key+1}}</td>
        <td class="text-left">{{$element->getTeacherName->name}} {{$element->getTeacherName->middle_name}} {{$element->getTeacherName->last_name}}</td>
        <td>{{$element->date}}/ {{$element->date_en}}</td>
        <td>{{ $element->status == 1 ? 'Present' : 'Absent' }}</td>
      </tr>
    @endforeach
    <tfoot>
      <tr>
        <td colspan="4" class="text-right"><b>Total : {{$total_count}}</b></td>
      </tr>
    </tfoot>
  </table>
</div>
<div class="card-footer">
  {!! $teacherattendance_list->appends(request()->input())->links() !!}
</div>