@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/loader.main.css">
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize"></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('admin.book.create')}}" class="btn btn-sm btn-info text-capitalize">Add {{ $page }} </a>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-hover position-relative w-100 m-0" id="book_ajax">
        <thead class="bg-dark text-center th-sticky-top">
          <th width="10">SN</th>
          <th class="text-left">Name</th>
          <th class="text-left">Class</th>
          <th class="text-left">Subject</th>
          <th class="text-left">Author</th>
          <th class="text-left">Publisher</th>
          <th class="text-left">Quantity/Issue</th>
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
      searchPlaceholder: "Search name or code"
    },
    "serverSide": true,
    "ajax":{
      "url": "{{ route('admin.getAllBook')}}",
      "dataType": "json",
      "type": "POST",
      "data":{ 
        _token: $('meta[name="csrf-token"]').attr('content'),
        
      }
    },
    "columns": [
      { "data": "id" },
      { "data": "name" },
      { "data": "class" , orderable: false, searchable: false },
      { "data": "subject" , orderable: false, searchable: false },
      { "data": "auther" },
      { "data": "publisher" },
      { "data": "quantity" },
      { "data": "action", orderable: false, searchable: false },
    ]

  });
</script>
<script>
  function myFunction(el) {
    const url = $(el).attr('data_url');
      var token = $('meta[name="csrf-token"]').attr('content');
      debugger;
      swal({
        title: 'Are you sure?',
        text: 'This record and it`s details will be permanantly deleted!',
        icon: 'warning',
        buttons: ["Cancel", "Yes!"],
        dangerMode: true,
        closeOnClickOutside: false,
      }).then(function(value) {
        if(value == true){
          $.ajax({
            url: url,
            type: "POST",
            data: {
              _token: token,
              '_method': 'DELETE',
            },
            success: function (data) {
              swal({
                title: "Success!",
                type: "success",
                text: "Click OK",
                icon: "success",
                showConfirmButton: false,
              }).then(location.reload(true));
              
            },
            error: function (data) {
              swal({
                title: 'Opps...',
                text: "Please refresh your page",
                type: 'error',
                timer: '1500'
              });
            }
          });
        }else{
          swal({
            title: 'Cancel',
            text: "Data is safe.",
            icon: "success",
            type: 'info',
            timer: '1500'
          });
        }
      });
  }
</script>
@endpush