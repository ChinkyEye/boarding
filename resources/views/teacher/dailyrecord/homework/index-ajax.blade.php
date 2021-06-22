<label class="control-label">Total Record Found: <span class="badge badge-info">{{$homeworks_count}}</span></label>
<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>

<div id="printDiv">                
    <table class="table table-bordered table-hover" id="">
      <thead class="bg-dark">
        <tr class="text-center">
          <th width="10">SN</th>
          <th class="text-left">Class</th>
          <th class="text-left">Subject</th>
          <th class="text-left">Date</th>
          <th width="10">Status</th>
          <th width="100">Action</th>
        </tr>
      </thead>              
      <tbody>
        @foreach ($homeworks as $index => $element)
          <tr class="text-center">
            <td>{{$index+1}}</td>
            <td class="text-left">{{$element->getShift->name}} | {{$element->getClass->name}} | {{$element->getSection->name}}</td>
            <td class="text-left">
              {{$element->getSubject->name}}
              ({{$element->getSubject->theory_practical == 1 ? 'Th' : 'Pr'}})
            </td>
            <td class="text-left">{{$element->date}}</td>
            <td>
              <a class="d-block text-center" href="{{ route('teacher.homework.active',$element->id) }}" data-toggle="tooltip" data-placement="top" title="{{$element->is_active == 1 ? 'Click to deactivate' : 'Click to activate'}}">
              <i class="fa {{$element->is_active == 1 ? 'fa-check check-css' : 'fa-times cross-css'}}"></i>
              </a>
            </td>
            <td>
              <a href="{{route('teacher.homework.show',$element->id)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a>
              <a href="{{route('teacher.homework.edit',$element->id)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top"title="Update"><i class="fas fa-edit"></i></a> 
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
</div>