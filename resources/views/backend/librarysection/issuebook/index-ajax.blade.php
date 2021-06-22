<label class="control-label">Total Record Found: <span class="badge badge-info">{{$issuebooks_count}}</span> for date <span class="badge badge-primary">{{$date}}</span></label>
<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>

<div id="printDiv">                
  <table class="table table-bordered table-hover position-relative w-100 m-0">
    <thead class="bg-dark">
      <tr class="text-center">
        <th width="10">SN</th>
        <th class="text-left">Student</th>
        <th class="text-left">Book</th>
        <th>Info</th>
        <th>Issue Date</th>
        <th>Return Date</th>
        <th width="100">Action</th>
      </tr>
    </thead> 
    <tbody>
      @foreach($issuebooks as $index=>$issuebook)
      <tr class="text-center">
        <td>{{$index+1}}</td>
        <td class="text-left">{{$issuebook->getStudent->getStudentUser->name}} {{$issuebook->getStudent->getStudentUser->middle_name}} {{$issuebook->getStudent->getStudentUser->last_name}}</td>
        <td class="text-left">{{$issuebook->getBook->name}} <span class="badge badge-info">{{$issuebook->getBook->book_no}}</span></td>
        <td>{{$issuebook->getStudent->getShift->name}} | {{$issuebook->getStudent->getClass->name}} | {{$issuebook->getStudent->getSection->name}}</td>
        <td>{{$issuebook->issue_date}}</td>
        <td align="center">
          @if($issuebook->return_date == null)
         {{--  <button class="btn btn-sm btn-info text-capitalize" data-toggle="modal" data-target="#modal-default" id="returndate" data-id="{{$issuebook->id}}" ><i class="fas fa-book"></i></button> --}}
          <a data-target="#modal-default" data-toggle="modal" class="btn btn-xs btn-outline-success" id="returndate" 
          href="#modal-default" data-id="{{$issuebook->id}}"><i class='fa fa-book'></i></a>
          @else
          {{$issuebook->return_date}}
          @endif
        </td>
        <td>
          @if($issuebook->return_date != null)
          <a href="{{route('admin.issuebook.show',$issuebook->id)}}" class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-eye'></i></a>
          @else
          <a href="{{route('admin.issuebook.edit',$issuebook->id)}}" class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Update' data-original-title='Update.'><i class='fas fa-edit'></i></a> 
          <form action="{{route('admin.issuebook.destroy',$issuebook->id)}}" method='issuebook' class='d-inline-block delete-c' data-toggle='tooltip' data-placement='top' title='Permanent Delete'>
            <input type='hidden' name='_token' value='".csrf_token()."'>
            <input name='_method' type='hidden' value='DELETE'>
            <button class='btn btn-xs btn-outline-danger' type='submit'><i class='fa fa-trash'></i></button>
          </form>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>             
  </table>
</div>