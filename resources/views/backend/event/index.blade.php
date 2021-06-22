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
        <h1 class="text-capitalize">{{ $page }}</h1>
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
      <a href="{{ route('admin.event.create')}}" class="btn btn-sm btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Issue Book">Add {{ $page }} </a>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <div class="row pb-3">
        <div class="col-md">
          <input type="text" name="date" class="form-control" id="date_data" placeholder="YYYY-MM-DD">
        </div>
        <div class="col-md-1">
          <div class="form-group" align="center">
            <button type="button" class="btn btn-info" id="remainderSearch">Search</button>
          </div>
        </div>
      </div>
      <div class="table-responsive" id="replaceTable">
        <table class="table table-bordered table-hover position-relative w-100 m-0" id="">
          <thead class="bg-dark">
            <tr class="text-center">
              <th width="10">SN</th>
              <th class="text-left">Title</th>
              <th class="text-left">Starting Date</th>
              <th class="text-left">Ending Date</th>
              <th class="text-left">Time</th>
              <th width="100">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($events as $key => $post)
              <tr class="text-center">
                <td>{{$key+1}}</td>
                <td class="text-left">{{$post->title}}</td>
                <td class="text-left">{{$post->start_date}}</td>
                <td class="text-left">{{$post->end_date}}</td>
                <td class="text-left">{{$post->start_time}}</td>
                <td>
                  <a href="{{route('admin.event.show',$post->id)}}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="View Detail"><i class="fa fa-eye"></i></a>
                  <a href="{{route('admin.event.edit',$post->id)}}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top"title="Update"><i class="fas fa-edit"></i></a> 
                  <form action="{{route('admin.event.destroy',$post->id)}}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
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
  });
</script>
<script type="text/javascript">
  $("body").on("click", "#remainderSearch", function(event){
      Pace.start();
      var token = $('meta[name="csrf-token"]').attr('content'),
      date_data = $('#date_data').val();
      $.ajax({
        type:"POST",
        dataType:"html",
        url: "{{URL::route('admin.getEventList')}}",
        data: {
          _token: token,
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