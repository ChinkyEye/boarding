<label class="control-label">Total Record Found: <span class="badge badge-info">{{$routines_count}}</span></label>
<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>

<div id="printDiv">                
  <table class="table table-bordered table-hover">
    <thead class="bg-dark">
      <tr class="text-center">
        <th width="10">SN</th>
        <th class="text-left">Period</th>
        <th class="text-left">Class</th>
        <th class="text-left">Teacher</th>
        <th class="text-left">Subject</th>
        <th width="100">Day</th>
        <th width="150">Created By</th>
      </tr>
    </thead>              
    <tbody>
      @foreach ($routines as $index => $element)
      <tr class="text-center {{$element->is_active == '1' ? '' : 'bg-light-danger'}}">
        <td>{{$index+1}}</td>
        <td class="text-left">{{$element->getPeriod->name}} <span class="badge badge-info">{{$element->getPeriod->start_time}} to {{$element->getPeriod->end_time}}</span></td>
        <td class="text-left">{{$element->getShift->name}} | {{$element->getClass->name}} | {{$element->getSection->name}}</td>
        <td>{{$element->getUser->name}}</td>
        <td>
          {{$element->getTeacherSubjectList->getSubject->name}}
          ({{$element->getTeacherSubjectList->getSubject->theory_practical == 1 ? 'Th' : 'Pr'}})
        </td>
        <td>
          {{$element->day_id == 0 ? 'Sunday':''}}
          {{$element->day_id == 1 ? 'Monday':''}}
          {{$element->day_id == 2 ? 'Tuesday':''}}
          {{$element->day_id == 3 ? 'Wednesday':''}}
          {{$element->day_id == 4 ? 'Thrusday':''}}
          {{$element->day_id == 5 ? 'Firday':''}}
          {{$element->day_id == 6 ? 'Saturday':''}}
        </td>
        <td>{{$element->getUser->name}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>