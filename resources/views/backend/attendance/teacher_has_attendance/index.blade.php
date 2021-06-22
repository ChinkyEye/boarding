@extends('backend.main.app')
@push('style')
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
  <link rel="stylesheet" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css">
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
  <form action="{{route('admin.teacher-attendance.export')}}" method="GET" class="d-inline-block">
    <input type="hidden" id="excShift" name="excShift" value="{{$shifts[0]->id}}">
    <input type="hidden" id="excDate" name="excDate">
    <input type="submit" name="submit" value="Export" id="submit" class="btn btn-info btn-sm rounded-0">
  </form>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md">
          <select class="form-control" name="shift_id" id="filter_shift">
            @foreach ($shifts as $key => $shift)
            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
              {{$shift->name}}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-md">
          <input type="text" class="form-control ndp-nepali-calendar" id="filter_date" name="date" autocomplete="off" value="{{ old('date') }}" placeholder="YYYY-MM-DD">
        </div>
        <div class="col-md-2">
          <div class="btn-group btn-block">
            <button type="button" name="search" id="searchTeacherAttend" class="btn btn-primary">Search</button>
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
<script>
  document.addEventListener('keypress', function(event) {
   if (event.keyCode == 13) {
     event.preventDefault();
     $("#searchTeacherAttend").click();
   }
 });
  $('#searchTeacherAttend').click(function (event) {
    Pace.start();
    var searchParams = {};
    var base_url = "{{route('admin.getTeacherList')}}";
    searchParams['shift_id']=$('#filter_shift').val();
    searchParams['date']=$('#filter_date').val();
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type: 'POST',
      data: 'parameters= ' + JSON.stringify(searchParams) + '&_token=' + token,
      url: base_url,
      success: function (data) {
        $('#replaceTable').html("");
        $('#replaceTable').html(data);
      }
    });
    Pace.stop();
  });
  // export excel
  $("body").on("change","#filter_shift", function(event){
    var shift_id = $('#filter_shift').val();
    $('#excShift').val(shift_id);
    $('#submit').prop('disabled', false);
  });

  $('#reset').click(function(event){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#filter_shift').val('');
    $('#filter_date').val(currentDate);
    $('#excShift').val('');
    $('#excDate').val(currentDate);
    $('#submit').prop('disabled', true);
  });
</script>
@endpush