@extends('main.school.include.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/loader.main.css">
@endpush
@section('school-content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }} </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('main.school.info.index',$school_info->slug) }}">{{$school_info->school_name}}</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered table-hover position-relative w-100 m-0" id="book_ajax">
        <thead class="bg-dark text-center th-sticky-top">
          <th width="10">SN</th>
          <th class="text-left">Name</th>
          <th class="text-left">Author</th>
          <th class="text-left">Quantity</th>
          <th width="100">Action</th>
        </thead>              
       
      </table>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{url('/')}}/backend/js/jquery.dataTables.js"></script>
<script src="{{url('/')}}/backend/js/dataTables.bootstrap4.js"></script>
<script type="text/javascript">
  $('#book_ajax').DataTable({
    "processing": true,
    "language": {
      processing: '<span class="d-flex justify-content-center flex-row loader-with-text"><div class="book"><div class="inner"><div class="left"></div><div class="middle"></div><div class="right"></div></div><ul>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li></ul></div> <span class="h6 loader-text">Techware School System processing...</span></span> ',
      searchPlaceholder: "Search name"
    },
    "serverSide": true,
    "ajax":{
      "url": "{{ route('main.getAllBook',$school_info->slug)}}",
      "dataType": "json",
      "type": "POST",
      "data":{ 
        _token: $('meta[name="csrf-token"]').attr('content'),
        
      }
    },
    "columns": [
      { "data": "id" },
      { "data": "name" },
      { "data": "auther" },
      { "data": "quantity" },
      { "data": "action", orderable: false, searchable: false },
    ],
    "order": [
      [ 0 ,"asc" ]
    ]

  });
</script>
<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>
@endpush


