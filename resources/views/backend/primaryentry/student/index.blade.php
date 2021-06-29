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
      <a href="{{ route('admin.student.create')}}" class="btn btn-sm btn-info text-capitalize">Add {{ $page }} </a>
      <div class="d-inline-block">
        <form action="{{route('admin.student.export')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="idcardShift" name="excShift">
          <input type="hidden" id="idcardClass" name="excClass">
          <input type="hidden" id="idcardSection" name="excSection">
          <input type="hidden" id="idcardBatchh" name="excBatchh">
          <input type="submit" name="submit" value="Export" class="btn btn-sm btn-info">
        </form>
        <form action="{{route('admin.student.idcard')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="idcardShift" name="idcardShift">
          <input type="hidden" id="idcardClass" name="idcardClass">
          <input type="hidden" id="idcardSection" name="idcardSection">
          <input type="hidden" id="idcardBatch" name="idcardBatch">
          <input type="submit" name="submit" value="Idcard" class="btn btn-sm btn-info">
        </form>
        <form action="{{route('admin.student.allcertificate')}}" method="GET" class="d-inline-block">
          <input type="hidden" id="idcardShift" name="idcardShift">
          <input type="hidden" id="idcardClass" name="idcardClass">
          <input type="hidden" id="idcardSection" name="idcardSection">
          <input type="hidden" id="idcertificateBatch" name="certificateBatch">
          <input type="submit" name="submit" value="Certificate" class="btn btn-sm btn-info">
        </form>
        {{-- <a href="{{route('admin.student.upgrade')}}" class="btn btn-sm btn-info text-capitalize">upgrade batch</a> --}}
      </div>
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
            <select class="form-control" name="shift_id" id="shift_data">
              <option value="">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
                {{$shift->name}}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col">
            <select class="form-control" name="class_id" id="class_data">
              <option value="">Select Your Class</option>
            </select>
          </div>
          <div class="col">
            <select class="form-control" name="section_id" id="section_data">
              <option value="">Select Your Section</option>
            </select>
          </div>
          <div class="col-1">
            <div class="form-group" align="center">
                <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
            </div>
          </div>
        </div>
        <thead class="bg-dark text-center th-sticky-top">
          <tr>
            <th width="10">SN</th>
            <th class="text-left">Name</th>
            <th class="text-left">Info</th>
            <th class="text-left">Phone</th>
            <th width="100">Created By</th>
            <th width="10">Status</th>
            <th width="200">Action</th>
          </tr>
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
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
      $('#idcardShift').val(shift_id);
    // debugger;
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getClassList')}}",
      data:{
        _token: token,
        shift_id: shift_id
      },
      success: function(response){
        $('#class_data').html('');
        $('#class_data').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#class_data').append('<option value='+val.get_class.id+'>'+val.get_class.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
        Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    Pace.start();
    var class_id = $('#class_data').val(),
        shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
        $('#idcardClass').val(class_id);
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getSectionList')}}",
      data:{
        _token: token,
        class_id: class_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#section_data').html('');
        $('#section_data').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#section_data').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#section_data", function(event){
    var section_id = $('#section_data').val();
      $('#idcardSection').val(section_id);
  });
</script>
<script type="text/javascript">
  $("body").on("change","#batch_data", function(event){
    var batch_id = $('#batch_data').val();
    $("#idcardBatch").val(batch_id);
    $("#idcertificateBatch").val(batch_id);
    $("#idcardBatchh").val(batch_id);


  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    fill_datatable();
    function fill_datatable(shift_data = '', class_data = '', section_data = '', batch_data ='')
    {
        var dataTable = $('#student_ajax').DataTable({
        "processing": true,
        "language": {
          processing: '<span class="d-flex justify-content-center flex-row loader-with-text"><div class="book"><div class="inner"><div class="left"></div><div class="middle"></div><div class="right"></div></div><ul>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li></ul></div> <span class="h6 loader-text">Techware School System processing...</span></span> ',
          searchPlaceholder: "Search code or phone no"
        },
        "serverSide": true,
        "ajax":{
          "url": "{{route('admin.getAllStudent')}}",
          "dataType": "json",
          "type": "POST",
          "data":{ 
            _token: $('meta[name="csrf-token"]').attr('content'),
            data:{
              shift_data: shift_data, 
              class_data: class_data, 
              section_data: section_data,
              batch_data: batch_data,
            }
          }
        },
        "columns": [
          { "data": "id" },
          { "data": "first_name" },
          { "data": "info" , orderable: false, searchable: false},
          { "data": "phone_no" },
          { "data": "created_by" },
          { "data": "status", orderable: false, searchable: false },
          { "data": "action", orderable: false, searchable: false },
        ],
        "order": [
          [ 0 ,"desc" ]
        ]

      });
    }

    $('#shift_data, #class_data, #section_data, #batch_data').change(function(){
        var shift_data = $('#shift_data').val();
        var class_data = $('#class_data').val();
        var section_data = $('#section_data').val();
        var batch_data = $('#batch_data').val();
        if(batch_data != '')
        {
          $('#student_ajax').DataTable().destroy();
          fill_datatable(shift_data, class_data, section_data, batch_data);
        }
        else if(shift_data != '')
        {
          $('#student_ajax').DataTable().destroy();
          fill_datatable(shift_data, class_data, section_data, batch_data);
        }
        else
        {
          // alert('Please field !!!');
        }
    });

    $('#reset').click(function(){
        $('#shift_data').val('');
        $('#class_data').val('');
        $('#section_data').val('');
        $('#batch_data').val('');
        $('#idcardShift').val('');
        $('#idcardClass').val('');
        $('#idcardSection').val('');
        $('#student_ajax').DataTable().destroy();
        fill_datatable();
    });
  });

  
</script>
<script>
  function myFunction(el) {
    // alert("bitch");
    const url = $(el).attr('data_url');
      var token = $('meta[name="csrf-token"]').attr('content');
      // debugger;
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