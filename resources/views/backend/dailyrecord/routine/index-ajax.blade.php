<div class="card-body table-responsive">
  <label class="control-label">Total Record Found: <span class="badge badge-info">{{$routines_count}}</span></label>
  <button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>

  <div id="printDiv">                
    <table class="table table-bordered table-hover" id="">
      <thead class="bg-dark">
        <tr class="text-center">
          <th width="10">SN</th>
          <th class="text-left">Period</th>
          <th class="text-left">Class</th>
          <th>Teacher</th>
          <th>Subject</th>
          <th>Day</th>
          <th width="150">Created By</th>
          <th width="10">Status</th>
          <th width="100" class="print-0">Action</th>
        </tr>
      </thead>              
      <tbody>
        @foreach ($routines as $index => $element)
        <tr class="text-center {{$element->is_active == '1' ? '' : 'bg-light-danger'}}">
          <td>{{$index+1}}</td>
          <td class="text-left">
            {{$element->getPeriod->name}} 
            <span class="badge badge-info float-right">{{$element->getPeriod->start_time}} to {{$element->getPeriod->end_time}}</span></td>
            <td class="text-left">{{$element->getShift->name}} | {{$element->getClass->name}} | {{$element->getSection->name}}</td>
            <td>{{$element->getTeacherName->name}} {{$element->getTeacherName->middle_name}} {{$element->getTeacherName->last_name}}</td>
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
              <td>
                <a href="{{route('admin.routine.active',$element->id)}}" data-toggle="tooltip" data-placement="top" title="{{$element->is_active == '1' ? 'Click to deactivate' : 'Click to activate'}}">
                  <i class="fa {{$element->is_active == '1' ? 'fa-check check-css' : 'fa-times cross-css'}}"></i>
                </a>
              </td>
              <td class="print-0">
                {{-- <a href="{{route('admin.routine.show',$element->id)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a> --}}
                <a href="{{route('admin.routine.edit',$element->id)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top"title="Update"><i class="fas fa-edit"></i></a> 
                <form action="{{route('admin.routine.destroy',$element->id)}}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Deleted">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>