@extends('backend.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/bootstrap-colorpicker.min.css">
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/tempusdominus-bootstrap-4.min.css">
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
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
    @foreach($events as $event)
    <form role="form" method="POST" action="{{ route('admin.event.update',$event->id)}}" class="signup" id="signup">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md-6">
            <label for="title">Title<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" name="title" autocomplete="off" value="{{$event->title}}">
            @error('title')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label>Color picker:</label>
            <input type="text" name="color" class="form-control my-colorpicker1 colorpicker-element" data-colorpicker-id="1" data-original-title="" value="{{$event->color}}">
            @error('color')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="start_date">Starting Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="{{$event->start_date}}">
            @error('start_date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="end_date">Ending Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="{{$event->end_date}}">
            @error('end_date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="start_time">Start Time <span class="text-danger">*</span></label>
            <div class="input-group date" id="start_time" data-target-input="nearest">
              <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="far fa-clock"></i></div>
              </div>
              <input type="text" class="form-control datetimepicker-input" data-target="#start_time" name="start_time" value="{{ $event->start_time }}">
            </div>
           {{--  @error('start_time')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror --}}
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Update Shift">Update</button>
      </div>
    </form>
    @endforeach
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script src="{{URL::to('/')}}/backend/js/bootstrap-colorpicker.min.js"></script>
<script src="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v3.2.min.js" type="text/javascript"></script>
<script src="{{URL::to('/')}}/backend/js/moment.min.js"></script>
<script src="{{URL::to('/')}}/backend/js/tempusdominus-bootstrap-4.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    // $('#start_date').val(currentDate);
    $('#start_date').nepaliDatePicker({
      ndpMonth: true,
    });
    // $('#end_date').val(currentDate);
    $('#end_date').nepaliDatePicker({
      ndpMonth: true,
    });
  });
</script>
<script>
    $('#start_time').datetimepicker({
      format: 'LT'
    })
</script>
<script>
  $('.my-colorpicker1').colorpicker();
</script>
<script>
$().ready(function() {
  $("#signup").validate({
    rules: {
      title: "required"
      start_date: "required"
      end_date: "required"
    },
    messages: {
      title: " title is required **"
      start_date: " starting date is required **"
      end_date: " ending date is required **"
    },
    highlight: function(element) {
     $(element).css('background', '#ffdddd');
     $(element).css('border-color', 'red');
    },
    unhighlight: function(element) {
     $(element).css('background', '#ffffff');
     $(element).css('border-color', 'green');
    }
  });
});
</script>
@endpush