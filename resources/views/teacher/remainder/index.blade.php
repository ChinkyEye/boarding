@extends('teacher.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<?php $page = request()->segment(count(request()->segments())) ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('teacher.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('teacher.remainder.create')}}" class="btn btn-sm btn-info text-capitalize">Add {{ $page }} </a>
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
            <option value="{{ $shift->shift_id }}" {{ old('shift_id') == $shift->shift_id ? 'selected' : ''}}> 
              {{$shift->shift->name}}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col-md">
          <select class="form-control class_data" name="class_id" id="class_data">
            <option value="">Select Your Class</option>

          </select>
        </div>
        <div class="col-md">
          <select class="form-control section_data" name="section_id" id="section_data">
            <option value="">Select Your Section</option>

          </select>
        </div>
        <div class="col-md">
          <input type="text" name="date" class="form-control" id="date_data" placeholder="YYYY-MM-DD" >
        </div>
        <div class="col-md-1">
          <div class="form-group" align="center">
            <button type="button" class="btn btn-info" id="remainderSearch">Search</button>
          </div>
        </div>
      </div>
      <div class="table-responsive" id="replaceTable">
        <table class="table table-bordered table-hover position-relative w-100 m-0">
          <thead class="bg-dark">
            <tr class="text-center">
              <th width="10">SN</th>
              <th class="text-left">Title</th>
              <th>Description</th>
              <th width="250px" class="text-left">Class</th>
              <th width="100">Action</th>
            </tr>
          </thead>              
          <tbody>
            @foreach ($remainders as $key => $element)
              <tr class="text-center">
                <td>{{$key+1}}</td>
                <td class="text-left">{{$element->title}}</td>
                <td class="text-left">{!! Str::limit(strip_tags($element->description),20) !!}</td>
                <td class="text-left">{{$element->getShift->name}} | {{$element->getClass->name}} | {{$element->getSection->name}}</td>
                <td>
                  <a href="{{route('teacher.remainder.show',$element->id)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a>
                  {{-- <a href="{{route('teacher.remainder.edit',$element->id)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top"title="Update"><i class="fas fa-edit"></i></a>  --}}
                  <form action="{{route('teacher.remainder.destroy',$element->id)}}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
  $('#date_data').val(currentDate);
  $('#date_data').nepaliDatePicker({
    disableAfter: currentDate,
    });
  });
</script>
<script type="text/javascript">
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var data_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.tgetClassList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
        $('#class_data').html('');
        $('#class_data').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#class_data').append('<option value='+val.sclass.id+'>'+val.sclass.name+'</option>');
        });
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    Pace.start();
    var data_id = $('#class_data').val(),
        shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.tgetSectionList')}}",
      data:{
        _token: token,
        data_id: data_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#section_data').html('');
        $('#section_data').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#section_data').append('<option value='+val.section.id+'>'+val.section.name+'</option>');
        });
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("click", "#remainderSearch", function(event){
      Pace.start();
      var token = $('meta[name="csrf-token"]').attr('content'),
      shift_data = $('#shift_data').val(),
      class_data = $('#class_data').val(),
      section_data = $('#section_data').val(),
      date_data = $('#date_data').val();
      $.ajax({
        type:"POST",
        dataType:"html",
        url: "{{URL::route('teacher.getRemainderList')}}",
        data: {
          _token: token,
          shift_data: shift_data,
          class_data: class_data,
          section_data: section_data,
          date_data: date_data,
        },
        success:function(response){
          $('#replaceTable').html(response);
        },
        error: function (e) {
          alert('Sorry! we cannot load data this time');
          return false;
        }
      });
      Pace.stop();
  });
</script>
@endpush
