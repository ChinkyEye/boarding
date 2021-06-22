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
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('admin.student.create')}}" class="btn btn-sm btn-info text-capitalize">Add {{ $page }} </a>
      <form action="{{route('admin.student.export')}}" method="GET" class="text-right">
        <input type="hidden" id="excShift" name="excShift">
        <input type="hidden" id="excClass" name="excClass">
        <input type="hidden" id="excSection" name="excSection">
        <input type="submit" name="submit" value="Export" class="btn btn-info">
      </form>
      <form action="{{route('admin.student.idcard')}}" method="GET" class="text-left">
        <input type="hidden" id="idcardShift" name="idcardShift">
        <input type="hidden" id="idcardClass" name="idcardClass">
        <input type="hidden" id="idcardSection" name="idcardSection">
        <input type="submit" name="submit" value="Idcard" class="btn btn-info">
      </form>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-hover position-relative w-100" id="student_ajax">
        <div class="row pb-3">
          <div class="col">
            <select class="form-control filter_shift" name="shift_id" id="filter_shift">
              <option value="">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
                {{$shift->name}}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col">
            <select class="form-control filter_class" name="class_id" id="filter_class">
              <option value="">Select Your Class</option>
                 
            </select>
          </div>
          <div class="col">
            <select class="form-control filter_section" name="section_id" id="filter_section">
              <option value="">Select Your Section</option>
              
            </select>
          </div>
          <div class="col-1">
            <div class="form-group" align="center">
                <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button>
            </div>
          </div>
        </div>
        <thead class="bg-dark text-center th-sticky-top">
          <th width="10">SN</th>
          <th class="text-left">Name</th>
          <th class="text-left">Code</th>
          <th class="text-left">Email</th>
          <th width="100">Created By</th>
          <th width="10">Status</th>
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
  $("body").on("change","#filter_shift", function(event){
    Pace.start();
    var data_id = $(event.target).val();
      $('#excShift').val(data_id);
      $('#idcardShift').val(data_id);
      var  token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getClassList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
        $('#filter_class').html('');
        $('#filter_class').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#filter_class').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
<script type="text/javascript">
  $("body").on("change","#filter_class", function(event){
    var data_id = $(event.target).val();
      $('#excClass').val(data_id);
      $('#idcardClass').val(data_id);
       var shift_id = $('#filter_shift').val();
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getSectionList')}}",
      data:{
        _token: token,
        data_id: data_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#filter_section').html('');
        $('#filter_section').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#filter_section').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alertify.alert("Sorry");
      }
    });
  });
</script>
<script type="text/javascript">
  $("body").on("change","#filter_section", function(event){
    var data_id = $(event.target).val();
      $('#excSection').val(data_id);
      $('#idcardSection').val(data_id);
       var shift_id = $('#filter_shift').val();
        token = $('meta[name="csrf-token"]').attr('content');
  
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    fill_datatable();
    function fill_datatable(filter_shift = '', filter_class = '', filter_section = '')
    {
        var dataTable = $('#student_ajax').DataTable({
                        "processing": true,
                        "language": {
                          processing: '<span class="d-flex justify-content-center flex-row loader-with-text"><div class="book"><div class="inner"><div class="left"></div><div class="middle"></div><div class="right"></div></div><ul>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li></ul></div> <span class="h6 loader-text">Techware School System processing...</span></span> '
                        },
                        "serverSide": true,
                        "ajax":{
                          "url": "{{route('admin.getAllStudent')}}",
                          "dataType": "json",
                          "type": "POST",
                          "data":{ 
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            data:{
                              filter_shift: filter_shift, 
                              filter_class: filter_class, 
                              filter_section: filter_section
                            }
                          }
                        },
                        "columns": [
                          { "data": "id" },
                          { "data": "first_name" },
                          { "data": "student_code" },
                          { "data": "email" },
                          { "data": "created_by" },
                          { "data": "status", orderable: false, searchable: false },
                          { "data": "action", orderable: false, searchable: false },
                        ],
                        "order": [
                          [ 0 ,"desc" ]
                        ]

                      });
    }

    $('#filter_shift, #filter_class, #filter_section').change(function(){
        var filter_shift = $('#filter_shift').val();
        var filter_class = $('#filter_class').val();
        var filter_section = $('#filter_section').val();
        if(filter_shift != '')
        {
          $('#student_ajax').DataTable().destroy();
          fill_datatable(filter_shift, filter_class, filter_section);
        }
        else
        {
          alert('Please field !!!');
        }
    });

    $('#reset').click(function(){
        $('#filter_shift').val('');
        $('#filter_class').val('');
        $('#filter_section').val('');
        $('#student_ajax').DataTable().destroy();
        fill_datatable();
    });
  });

  
</script>
<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>
@endpush


