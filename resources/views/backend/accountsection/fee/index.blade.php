@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
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
      <div class="row">
        <div class="col-md">
          <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Invoice Number">
        </div>
        <div class="col-md">
          <select class="form-control" name="shift_id" id="shift_data">
            <option value="">Select Your Shift</option>
            @foreach ($shifts as $key => $shift)
            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
              {{$shift->name}}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-md">
          <select class="form-control" name="class_id" id="class_data">
            <option value="">Select Your Class</option>
          </select>
        </div>
        <div class="col-md">
          <select class="form-control" name="section_id" id="section_data">
            <option value="">Select Your Section</option>
          </select>
        </div>
        <div class="col-md-2">
          <div class="btn-group btn-block">
            <button type="button" name="search" id="search" class="btn btn-primary">Search</button>
            <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
          </div>
        </div>
      </div>
    </div>
    <form role="form" method="POST" action="{{ route('admin.teacher-attendance.store')}}">
    @csrf
      <div id="replaceTable">
        
      </div>
    </form>
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
  $('#reset').click(function(){
      $('#invoice_id').val('');
      $('#shift_data').val('');
      $('#class_data').val('');
      $('#section_data').val('');
      $('#shift_data,#class_data,#section_data,#search').prop('disabled', false);
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

<script>
  document.addEventListener('keypress', function(event) {
   if (event.keyCode == 13) {
     event.preventDefault();
     $("#search").click();
   }
 });
  $('#search').click(function () {
    var searchParams = {};
    var base_url = "{{route('admin.getStudentFeeList')}}";
    searchParams['shift_id']=$('#shift_data').val();
    searchParams['class_id']=$('#class_data').val();
    searchParams['section_id']=$('#section_data').val();
    searchParams['invoice_id']=$('#invoice_id').val();
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type: 'POST',
      data: 'parameters= ' + JSON.stringify(searchParams) + '&_token=' + token,
      url: base_url,
      success: function (data) {
        $('#replaceTable').html(data);
      }
    });
    return false;
  });
</script>
<script type="text/javascript">
  $('#invoice_id').keyup(function(event){
    Pace.start();
    event.preventDefault();
    $('#shift_data,#class_data,#section_data,#search').prop('disabled', true);
  });
</script>
<script>
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>
@endpush




