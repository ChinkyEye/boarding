@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize"></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{route('admin.fee.index')}}"> Fee</a></li>
          <li class="breadcrumb-item active">Report</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <form action="{{route('admin.fee-report.export')}}" method="GET" class="d-inline-block">
    <input type="hidden" id="excShift" name="excShift">
    <input type="hidden" id="excClass" name="excClass">
    <input type="hidden" id="excSection" name="excSection">
    <input type="hidden" id="excStudent" name="excStudent">
    <input type="hidden" id="excDateFrom" name="excDateFrom">
    <input type="hidden" id="excDateTo" name="excDateTo">
    <input type="hidden" id="excType" name="excType" value="2">
    <input type="submit" name="submit" value="Export" id="submit" class="btn btn-info btn-sm rounded-0">
  </form>
  <div class="card">
    <form>
    <div class="card-header">
      <div class="row">
        <div class="col-md row">
          <div class="col-md-3">
            <select class="form-control" name="shift_id" id="shift_data">
              <option value="">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
                {{$shift->name}}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-control" name="class_id" id="class_data">
              <option value="">Select Your Class</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-control" name="section_id" id="section_data">
              <option value="">Select Your Section</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-control" name="student_id" id="student_data">
              <option value="">Select Student Name</option>
            </select> 
          </div>
          <div class="col-md input-group mt-3">
            <input type="text" class="form-control" id="filter_date_to" name="from_date" autocomplete="off"  placeholder="YYYY-MM-DD (From)">
            <div class="input-group-prepend">
              <label class="input-group-text">From</label>
            </div>
          </div>
          <div class="col-md input-group mt-3">
            <input type="text" class="form-control" id="filter_date_from" name="to_date" autocomplete="off"  placeholder="YYYY-MM-DD (To)">
            <div class="input-group-prepend">
              <label class="input-group-text">To</label>
            </div>
          </div>
          <div class="col-md input-group mt-3">
            <select class="form-control" id="type_data" name="type">
              <option value="2">Collect</option>
              <option value="1">School Bill</option>
              <option value="3">Unpaid</option>
            </select>
            <div class="input-group-append">
              <label class="input-group-text" for="type_data">Type</label>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="btn-group btn-block h-100">
            <button type="button" name="search" id="feeSearch" class="btn btn-primary">Search</button>
            <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
          </div>
        </div>
      </div>
    </div>
    </form>
    <div id="replaceTable">

    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#filter_date_from').val(currentDate);
    $('#excDateFrom').val(currentDate);
    $('#filter_date_from').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
      onChange: function(event) {
        var date = $('#filter_date_from').val();
        // debugger;
        $('#excDateFrom').val(date);
      }
    });
    $('#filter_date_to').val(currentDate);
     $('#excDateTo').val(currentDate);
    $('#filter_date_to').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
      onChange: function(event) {
        var date = $('#filter_date_to').val();
        $('#excDateTo').val(date);
      }
    });
  });
  $('#reset').click(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#student_data').val('');
    $('#shift_data').val('');
    $('#class_data').val('');
    $('#section_data').val('');
    $('#type_data').val('2');
    $('#filter_date_from').val(currentDate);
    $('#filter_date_to').val(currentDate);
  });
</script>
<script type="text/javascript">
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
        $('#excShift').val(shift_id);
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
        toastr.error("Sorry");
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
        $('#excClass').val(class_id);
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
        toastr.error("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#section_data", function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
        class_id = $('#class_data').val(),
        section_id = $('#section_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
        $('#excSection').val(section_id);
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getStudentList')}}",
      data:{
        _token: token,
        shift_id: shift_id,
        class_id: class_id,
        section_id: section_id
      },
      success: function(response){
        $('#student_data').html('');
        $('#student_data').append('<option value="">--Choose Student--</option>');
        $.each( response, function( i, val ) {
          if (val.get_student_user.middle_name == null) { 
            val.get_student_user.middle_name = '';
          } 
          $('#student_data').append('<option value='+val.id+'>'+val.get_student_user.name +' '+val.get_student_user.middle_name +' '+val.get_student_user.last_name+'</option>');
        });
      },
      error: function(event){
        toastr.error("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#student_data", function(event){
    var section_id = $('#student_data').val();
      $('#excStudent').val(section_id);
  });
</script>
<script type="text/javascript">
  $("body").on("change","#type_data", function(event){
    var type_data = $('#type_data').val();
      $('#excType').val(type_data);
  });
</script>
<script type="text/javascript">
  $("body").on("click", "#feeSearch", function(event){
      Pace.start();
      var token = $('meta[name="csrf-token"]').attr('content'),
      shift_data = $('#shift_data').val(),
      class_data = $('#class_data').val(),
      section_data = $('#section_data').val(),
      student_data = $('#student_data').val(),
      filter_date_to = $('#filter_date_to').val(),
      filter_date_from = $('#filter_date_from').val(),
      type_data = $('#type_data').val();
      // $('#excStudent').val(student_data);
      // $('#excDateFrom').val(filter_date_from);
      // $('#excDateTo').val(filter_date_to);
      // $('#excType').val(type_data);
      $.ajax({
        type:"GET",
        dataType:"html",
        url: "{{ route('admin.getReportFee') }}",
        data: {
          _token: token,
          shift_data: shift_data,
          class_data: class_data,
          section_data: section_data,
          student_data: student_data,
          filter_date_to: filter_date_to,
          filter_date_from: filter_date_from,
          type_data: type_data,
        },
        success:function(response){
          $('#replaceTable').html("");
          $('#replaceTable').html(response);
        },
        error: function (e) {
          alert('Sorry! we cannot load data this time');
          return false;
        }
      });
      Pace.stop();
  });
  $("body").on("click", "#replaceTable a.page-link", function(event){
    $.get($(this).attr('href'),function(data){
      $('#replaceTable').html("");
      $('#replaceTable').html(data);
    })
    return false;
  });
</script>
@endpush