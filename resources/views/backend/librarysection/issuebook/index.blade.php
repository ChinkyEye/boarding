@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
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
      <a href="{{ route('admin.issuebook.create')}}" class="btn btn-sm btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Issue Book">Add {{ $page }} </a>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <div class="row pb-3">
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
        <div class="col-md">
          <select class="form-control" name="student_id" id="student_data">
            <option value="">Select Student Name</option>
          </select> 
        </div>
        <div class="col-md">
          <input type="text" class="form-control" id="date_data" name="date" autocomplete="off" value="{{ old('date') }}" placeholder="YYYY-MM-DD">
        </div>
        <div class="col-md-2">
          <div class="btn-group btn-block">
            <button type="button" class="btn btn-info" id="issuebookSearch">Search</button>
            <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
          </div>
        </div>
      </div>
      <div class="table-responsive" id="replaceTable">
        <table class="table table-bordered table-hover position-relative w-100 m-0">
          <thead class="bg-dark">
            <tr class="text-center">
              <th width="10">SN</th>
              <th class="text-left">Student</th>
              <th class="text-left">Book</th>
              <th>Info</th>
              <th>Issue Date</th>
              <th>Return Date</th>
              <th width="100">Action</th>
            </tr>
          </thead> 
          <tbody>
            @foreach($issuebooks as $index=>$issuebook)
            <tr class="text-center">
              <td>{{$index+1}}</td>
              <td class="text-left">{{$issuebook->getStudent->getStudentUser->name}} {{$issuebook->getStudent->getStudentUser->middle_name}} {{$issuebook->getStudent->getStudentUser->last_name}} {{"(".$issuebook->getStudent->student_code.")"}}</td>
              <td class="text-left">{{$issuebook->getBook->name}} <span class="badge badge-info">{{$issuebook->getBook->book_no}}</span></td>
              <td>{{$issuebook->getStudent->getShift->name}} | {{$issuebook->getStudent->getClass->name}} | {{$issuebook->getStudent->getSection->name}}</td>
              <td>{{$issuebook->issue_date}}</td>
              <td align="center">
                @if($issuebook->return_date == null)
              {{--   <button class="btn btn-sm btn-info text-capitalize" data-toggle="modal" data-target="#modal-default" id="returndate" data-id="{{$issuebook->id}}" ><i class="fas fa-book"></i></button> --}}
              {{--   <a href="#modal-default" class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail' data-toggle="modal" data-target="#modal-default" id="returndate" data-id="{{$issuebook->id}}"><i class='fa fa-eye'></i></a> --}}
              <a data-target="#modal-default" data-toggle="modal" class="btn btn-xs btn-outline-success" id="returndate" 
              href="#modal-default" data-id="{{$issuebook->id}}"><i class='fa fa-book'></i></a>
                @else
                {{$issuebook->return_date}}
                @endif
              </td>
              <td>
                @if($issuebook->return_date != null)
                <a href="{{route('admin.issuebook.show',$issuebook->id)}}" class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-eye'></i></a>
                @else
                <a href="{{route('admin.issuebook.edit',$issuebook->id)}}" class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Update' data-original-title='Update.'><i class='fas fa-edit'></i></a> 
                <form action="{{route('admin.issuebook.destroy',$issuebook->id)}}" method='issuebook' class='d-inline-block delete-c' data-toggle='tooltip' data-placement='top' title='Permanent Delete'>
                  <input type='hidden' name='_token' value='".csrf_token()."'>
                  <input name='_method' type='hidden' value='DELETE'>
                  <button class='btn btn-xs btn-outline-danger' type='submit'><i class='fa fa-trash'></i></button>
                </form>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>             
        </table>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="modal-default" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog"  role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title text-capitalize">Return Date of Book</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form role="form" method="POST" action="{{ route('admin.issuebook.getReturnDateSave')}}" class="categoryform" id="signup">
        {{ csrf_field() }}
        <div class="modal-body" >
          <div class="form-group">
            <label for="return_date">Return Date</label>
            <input type="text"  class="form-control max" id="return_date" placeholder="Select Date" name="return_date" required="true"  autocomplete="off">
            <input type="hidden" name="data_id" id="datereturns">
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="submit" class="btn btn-info text-capitalize">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#date_data').val(currentDate);
    $('#date_data').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
    });
    $('#return_date').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
    });
  });
</script>
<script>
  $("body").on("click","#returndate", function(event){
    var data = $(this).attr("data-id");
    $('#datereturns').val(data);
  });
</script>
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
        toastr.error("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
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
        toastr.error("Sorry");
      }
    });
  });
</script>
<script type="text/javascript">
  $("body").on("change","#section_data", function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
        class_id = $('#class_data').val(),
        section_id = $('#section_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
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
          
          $('#student_data').append('<option value='+val.id+'>'+val.get_student_user.name +' '+val.get_student_user.middle_name +' '+val.get_student_user.last_name+' ('+val.student_code+')'+'</option>');
        });
      },
      error: function(event){
        toastr.error("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("click", "#issuebookSearch", function(event){
      Pace.start();
      var token = $('meta[name="csrf-token"]').attr('content'),
      shift_data = $('#shift_data').val(),
      class_data = $('#class_data').val(),
      section_data = $('#section_data').val(),
      student_data = $('#student_data').val(),
      date_data = $('#date_data').val();
      $.ajax({
        type:"POST",
        dataType:"html",
        url: "{{URL::route('admin.getIssuebookRecord')}}",
        data: {
          _token: token,
          shift_data: shift_data,
          class_data: class_data,
          section_data: section_data,
          student_data: student_data,
          date_data: date_data,
        },
        success:function(response){
          $('#replaceTable').html("");
          $('#replaceTable').html(response);
        },
        error: function (e) {
          toastr.error('Sorry! we cannot load data this time');
          return false;
        }
      });
      Pace.stop();
  });
  $('#reset').click(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#shift_data').val('');
    $('#class_data').val('');
    $('#section_data').val('');
    $('#student_data').val('');
    $('#date_data').val(currentDate);
  });
</script>
<script>
    $('.delete-c').on('click', function (e) {
      event.preventDefault();
      var url = route('admin.issuebook.destroy') ;
      var token = $('meta[name="csrf-token"]').attr('content');
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
                text: data.message+"\n Click OK",
                icon: "success",
                showConfirmButton: false,
              }).then(location.reload(true));
              
            },
            error: function (data) {
              swal({
                title: 'Opps...',
                text: data.message+"\n Please refresh your page",
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
    });
  </script> 
@endpush