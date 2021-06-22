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
      <a href="{{ route('admin.notice.create')}}" class="btn btn-sm btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Issue Book">Add {{ $page }} </a>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
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
        <div class="col">
          <input type="text" name="date" class="form-control" id="date_data" value="{{date("Y-m-d")}}" placeholder="YYYY-MM-DD" max="{{date("Y-m-d")}}">
        </div>
        <div class="col-1">
          <div class="form-group" align="center">
            <button type="button" class="btn btn-info" id="noticeSearch">Search</button>
          </div>
        </div>
      </div>
      <div class="table-responsive" id="replaceTable">
        <table class="table table-bordered table-hover position-relative w-100 m-0">
          <thead class="bg-dark">
            <tr class="text-center">
              <th width="10">SN</th>
              <th class="text-left">Title</th>
              <th class="text-left">Description</th>
              <th width="200px">Class</th>
              <th width="150">Action</th>
            </tr>
          </thead> 
          <tbody>
            @foreach ($notices as $index => $post)
            <tr class="text-center">
              <td>{{$index+1}}</td>
              <td class="text-left">{{$post->title}}</td>
              <td class="text-left">{!! Str::limit(strip_tags($post->description),20) !!}</td>
              <td>
                @foreach ($post->getNoticeList()->get() as $class_list)
                <span class="badge badge-primary">{{$class_list->getClassOne->name}}</span>
                @endforeach
              </td>
              <td>
                <a  href="{{route('admin.addnotice',$post->slug)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Add Class">{{$post->getNoticeList()->count()}} <i class="fa fa-plus"></i></a>
                <a href="{{route('admin.notice.show',$post->id)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a>
                <a href="{{route('admin.notice.edit',$post->id)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top"title="Update"><i class="fas fa-edit"></i></a> 
                <form action="{{route('admin.notice.destroy',$post->id)}}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
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
      ndpMonth: true,
      disableAfter: currentDate,
    });
    $('#return_date').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
    });
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
</script>
<script type="text/javascript">
  $("body").on("click", "#noticeSearch", function(event){
      Pace.start();
      var token = $('meta[name="csrf-token"]').attr('content'),
      shift_data = $('#shift_data').val(),
      class_data = $('#class_data').val(),
      section_data = $('#section_data').val(),
      date_data = $('#date_data').val();
      $.ajax({
        type:"POST",
        dataType:"html",
        url: "{{URL::route('admin.getNoticeList')}}",
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