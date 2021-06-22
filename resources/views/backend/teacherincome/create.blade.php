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
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md">
          <input type="text" class="form-control ndp-nepali-calendar" id="filter_date" name="date" autocomplete="off" value="{{ old('date') }}" placeholder="YYYY-MM-DD">
        </div>
        <div class="col-md input-group">
          <select class="form-control" id="teacher_id" name="teacher_id">
            <option>Select Teacher</option>
            @foreach ($teachers as $key => $teacher)
            <option value="{{ $teacher->id }}"> 
              {{$teacher->getTeacherUser->name}} {{$teacher->getTeacherUser->middle_name}} {{$teacher->getTeacherUser->last_name}}({{$teacher->teacher_code}})
            </option>
            @endforeach
          </select>
          <div class="input-group-append">
            <label class="input-group-text" for="teacher_id">Teacher</label>
          </div>
        </div>
        <div class="col-md input-group">
          <select class="form-control" id="filter_month" name="month">
            <option value="1">Baisakh</option>
            <option value="2">Jestha</option>
            <option value="3">Asar</option>
            <option value="4">Shrawan</option>
            <option value="5">Bhadra</option>
            <option value="6">Ashoj</option>
            <option value="7">Kartik</option>
            <option value="8">Mangsir</option>
            <option value="9">Poush</option>
            <option value="10">Magh</option>
            <option value="11">Falgun</option>
            <option value="12">Chaitra</option>
          </select>
          <div class="input-group-append">
            <label class="input-group-text" for="filter_month">Month</label>
          </div>
        </div>
        
        <div id="replaceTable">

        </div>
        
      </div>
    </div>
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
  $("body").on("change","#teacher_id", function(event){
    Pace.start();
    var teacher_id = $("#teacher_id").val(),
        date = $("#filter_date").val(),
        month = $("#filter_month").val(),
        token = $('meta[name="csrf-token"]').attr('content');
        // debugger;
    $.ajax({
      type:"POST",
      dataType:"html",
      url:"{{route('admin.getTeacherInfoList')}}",
      data:{
        _token: token,
        teacher_id: teacher_id,
        date:date,
        month:month
      },
      success: function(response){
        $('#replaceTable').html(response);
      },
      error: function(event){
        console.log(this);
        alert("Sorry");
      }
    });
    Pace.stop();
  });

  $("body").on("change","#filter_month", function(event){
    Pace.start();
    var teacher_id = $("#teacher_id").val(),
        date = $("#filter_date").val(),
        month = $("#filter_month").val(),
        token = $('meta[name="csrf-token"]').attr('content');
        // debugger;
    $.ajax({
      type:"POST",
      dataType:"html",
      url:"{{route('admin.getTeacherInfoList')}}",
      data:{
        _token: token,
        teacher_id: teacher_id,
        date:date,
        month:month
      },
      success: function(response){
        $('#replaceTable').html(response);
      },
      error: function(event){
        console.log(this);
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script>
  $("body").on("change","#teacher_id", function(event){
    Pace.start();
    var date = $('#filter_date').val(),
        month = $("#filter_month").val(),
        teacher_id = $('#teacher_id').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getTeacherDetailList')}}",
      data:{
        _token: token,
        date: date,
        month: month,
        teacher_id: teacher_id,
      },
      success: function(response){
        if(response.data == 0){
          $("#submit").prop('disabled', false);
        }else{
          $("#submit").prop('disabled', true);
          toastr.error(response.msg);
        }
      },
      error: function(event){
        alert("Sorry");
      }
    });
    Pace.stop();
  });

  $("body").on("change","#filter_month", function(event){
    Pace.start();
    var date = $('#filter_date').val(),
        month = $("#filter_month").val(),
        teacher_id = $('#teacher_id').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getTeacherDetailList')}}",
      data:{
        _token: token,
        date: date,
        month: month,
        teacher_id: teacher_id,
      },
      success: function(response){
        if(response.data == 0){
          $("#submit").prop('disabled', false);
        }else{
          $("#submit").prop('disabled', true);
          toastr.error(response.msg);
        }
      },
      error: function(event){
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
@endpush