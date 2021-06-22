<label class="control-label">Total Record Found: <span class="badge badge-info">{{$data_count}}</span></label>
<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>

<div id="printDiv">                
  <table class="table table-bordered table-hover position-relative w-100 m-0" id="">
    <thead class="bg-dark">
      <tr class="text-center">
        <th width="10">SN</th>
        <th class="text-left">Title</th>
        <th class="text-left">Starting Date</th>
        <th class="text-left">Ending Date</th>
        <th class="text-left">Time</th>
        <th width="100">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $key => $post)
      <tr class="text-center">
        <td>{{$key+1}}</td>
        <td class="text-left">{{$post->title}}</td>
        <td class="text-left">{{$post->start_date}}</td>
        <td class="text-left">{{$post->end_date}}</td>
        <td class="text-left">{{$post->start_time}}</td>
        <td>
          <a href="{{route('admin.event.show',$post->id)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a>
          <a href="{{route('admin.event.edit',$post->id)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top"title="Update"><i class="fas fa-edit"></i></a> 
          <form action="{{route('admin.event.destroy',$post->id)}}" method="post" class="d-inline-block" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
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