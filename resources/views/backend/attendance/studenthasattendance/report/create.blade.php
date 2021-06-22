<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printTable')" >PRINT<i class="fa fa-print"></i></button>
<div id="printTable">
  <table class="table table-bordered table-hover position-relative m-0">
    <thead class="bg-dark text-center th-sticky-top">
      <tr>
        <th width="10">SN</th>
        <th class="text-left">Student</th>
        <th>Date/Time</th>
        <th width="150">Status</th>
      </tr>
    </thead>              
    @foreach ($studentattendance_list as $key=>$element)
      <tr class="text-center">
        <td>{{$key+1}}</td>
        <td class="text-left">{{$element->getStudentName->name}} {{$element->getStudentName->middle_name}} {{$element->getStudentName->last_name}} ({{$element->getStudentOne->student_code}})</td>
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
  {!! $studentattendance_list->appends(request()->input())->links() !!}
</div>