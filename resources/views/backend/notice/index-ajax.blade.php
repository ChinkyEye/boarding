<label class="control-label">Total Record Found: <span class="badge badge-info">{{$data_count}}</span></label>
<button type="button" class="btn btn-info btn-xs hidden-print float-right" onclick="PrintDiv('printDiv')" >PRINT<i class="fa fa-print"></i></button>

<div id="printDiv">                
  <table class="table table-bordered table-hover position-relative w-100 m-0">
    <thead class="bg-dark">
      <tr class="text-center">
        <th width="10">SN</th>
        <th class="text-left">Title</th>
        <th class="text-left">Description</th>
        <th width="200px">Class</th>
        <th width="150">Action</th>
      </tr>
    </thead> 
    <tbody>
      @foreach ($data as $index => $post)
      <tr class="text-center">
        <td>{{$index+1}}</td>
        <td class="text-left">{{$post->title}}</td>
        <td class="text-left">{!! Str::limit(strip_tags($post->description),20) !!}</td>
        <td>
          @foreach ($post->getNoticeList()->get() as $class_list)
          <span class="badge badge-primary">{{$class_list->getClassOne->name}}</span>
          @endforeach
        </td>
        <td>
          <a  href="{{route('admin.addnotice',$post->slug)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Add Class">{{$post->getNoticeList()->count()}} <i class="fa fa-plus"></i></a>
          <a href="{{route('admin.notice.show',$post->id)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a>
          <a href="{{route('admin.notice.edit',$post->id)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top"title="Update"><i class="fas fa-edit"></i></a> 
          <form action="{{route('admin.notice.destroy',$post->id)}}" method="post" class="d-inline-block" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
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