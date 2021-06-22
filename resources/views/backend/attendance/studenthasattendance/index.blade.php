@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<?php $page = "Student attendance"; ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }}</h1>
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
  <form action="{{route('admin.student-attendance.export')}}" method="GET" class="d-inline-block">
    <input type="hidden" id="excShift" name="excShift">
    <input type="hidden" id="excClass" name="excClass">
    <input type="hidden" id="excSection" name="excSection">
    <input type="hidden" id="excDate" name="excDate">
    <input type="submit" name="submit" value="Export" id="submit" class="btn btn-sm btn-info rounded-0" disabled>
  </form>
  <div class="card">
    <div class="card-header">
      <div class="row">
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
        {{-- <div class="col-md">
          <input type="date" name="date" class="form-control" id="filter_date" placeholder="YY-MM-DD">
        </div> --}}
        <div class="col-md">
          <input type="text" class="form-control" id="filter_date" name="date" autocomplete="off"  placeholder="YYYY-MM-DD">
        </div>
        <div class="col-md-2">
          <div class="btn-group btn-block">
            <button type="button" name="search" id="search" class="btn btn-primary">Search</button>
            <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
          </div>
        </div>
      </div>
    </div>
    <form role="form" method="POST" action="{{ route('admin.teacher-student-attendance.store')}}">
      @csrf
      <div id="replaceTable">

      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  $('#filter_date').val(currentDate);
  $('#excDate').val(currentDate);
  $('#filter_date').nepaliDatePicker({
    ndpMonth: true,
    disableAfter: currentDate,
    onChange: function(event) {
      var date = $('#filter_date').val();
      $('#excDate').val(date);
    }
    });
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
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#section_data", function(event){
    var section_id = $('#section_data').val();
      $('#excSection').val(section_id);
      $('#submit').prop('disabled', false);
  });
</script>
<script type="text/javascript">
  $('#reset').click(function(){
      var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
      $('#shift_data').val('');
      $('#class_data').val('');
      $('#section_data').val('');
      $('#filter_date').val(currentDate);
      $('#excClass').val('');
      $('#excShift').val('');
      $('#excSection').val('');
      $('#excDate').val(currentDate);
      $('#submit').prop('disabled', true);
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
  Pace.start();
    var searchParams = {};
    var base_url = "{{route('admin.getStudentAttendenceList')}}";

    searchParams['shift_id'] = $('#shift_data').val();
    searchParams['class_id'] = $('#class_data').val();
    searchParams['section_id'] = $('#section_data').val();
    searchParams['date'] = $('#filter_date').val();

    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        data: 'parameters= ' + JSON.stringify(searchParams) + '&_token=' + token,
        url: base_url,
        success: function (data) {
            $('#replaceTable').html(data);
        }
    });
    Pace.stop();
    return false;
});
</script>
@endpush