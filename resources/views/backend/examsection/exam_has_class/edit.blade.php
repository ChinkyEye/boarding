@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<?php $page = "Exam Edit" ?>
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
  <div class="card">
    @foreach($examhasclasses as $exam)
    <form role="form" method="POST" action="{{ route('admin.examhasclass.update',$exam->id)}}">
      <div class="card-body">
        @method('PATCH')
        @csrf
         <div class="row">
          <div class="form-group col-md my-auto border-left border-info">
            <label class="control-label d-block"> Shift : {{$exam->getShift->name}}</label>
            <label class="control-label d-block"> Class : {{$exam->getClass->name}}</label>
          </div>
          <div class="bootstrap-timepicker col-md">
            <div class="form-group">
              <label for="start_time">Start Time<span class="text-danger">*</span></label>
              <div class="input-group date" id="start_time" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#start_time" name="start_time"  value="{{ $exam->start_time }}">
                <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
              @error('start_time')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
          <div class="bootstrap-timepicker col-md">
            <div class="form-group">
              <label for="end_time">End Time<span class="text-danger">*</span></label>
              <div class="input-group date" id="end_time" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#end_time" name="end_time" value="{{ $exam->end_time }}">
                <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
                </div>
              </div>
              @error('end_time')
              <span class="text-danger font-italic" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>
          </div>
          <div class="form-group col-md">
            <label for="result_date">Result Date<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="result_date" name="result_date" autocomplete="off" value="{{ $exam->result_date }}">
            @error('result_date')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
    @endforeach
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/moment.min.js"></script>
<script src="{{URL::to('/')}}/backend/js/tempusdominus-bootstrap-4.js"></script>
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script>
    $('#start_time').datetimepicker({
      format: 'LT'
    })
</script>
<script>
    $('#end_time').datetimepicker({
      format: 'LT'
    })
</script>
<script type="text/javascript">
  $('#result_date').nepaliDatePicker({
    ndpYear: true,
    ndpMonth: true,
  });
</script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      shift_id: "required",
      class_id: "required",
      section_id: "required",
      start_time: "required",
      end_time: "required",
      result_date: "required",
    },
    messages: {
      shift_id: " shift_id field is required **",
      class_id: " class_id field is required **",
      section_id: " section_id field is required **",
      start_time: " start_time field is required **",
      end_time: " end_time field is required **",
      result_date: " result_date field is required **",
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