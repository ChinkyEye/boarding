<label class="control-label">Total Record Found: <span class="badge badge-info">{{$remainders_count}}</span></label>
<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>

<div id="printDiv">                
  <table class="table table-bordered table-hover position-relative w-100 m-0">
    <thead class="bg-dark">
      <tr class="text-center">
        <th width="10">SN</th>
        <th class="text-left">Title</th>
        <th>Description</th>
        <th width="250px" class="text-left">Class</th>
        <th width="100">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($remainder as $key => $element)
      <tr class="text-center">
        <td>{{$key+1}}</td>
        <td class="text-left">{{$element->title}}</td>
        <td class="text-left">{!! Str::limit(strip_tags($element->description),20) !!}</td>
        <td class="text-left">{{$element->getShift->name}} | {{$element->getClass->name}} | {{$element->getSection->name}}</td>
        <td>
          <a href="{{route('teacher.remainder.show',$element->id)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a>
          <a href="{{route('teacher.remainder.edit',$element->id)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top"title="Update"><i class="fas fa-edit"></i></a> 
          <form action="{{route('teacher.remainder.destroy',$element->id)}}" method="post" class="d-inline-block" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
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