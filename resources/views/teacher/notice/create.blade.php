@extends('teacher.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
@endpush
@section('content')
<?php $page = "Notice"; ?>
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
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('teacher.notice.store')}}" class="validate" id="validate">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-md-12">
            <label for="title">Title<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" name="title" autocomplete="off" value="{{ old('title') }}">
            @error('title')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="start_date_np">Start Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="start_date_np" name="start_date_np" autocomplete="off" value="{{ old('start_date_np') }}">
            @error('start_date_np')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-6">
            <label for="end_date_np"> End Date <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="end_date_np" name="end_date_np" autocomplete="off" value="{{ old('end_date_np') }}">
            @error('end_date_np')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md row">
            @foreach ($period_class as $element)
              <div class="form-check col-md-2 mx-2">
                <input type="checkbox" class="form-check-input" id="class{{$element->class_id}}" name="class_id[]" value="{{$element->class_id}}">
                <label class="form-check-label" for="class{{$element->class_id}}">{{$element->getClass->name}}({{$element->shift->name}})</label>
              </div>
            @endforeach
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-12">
            <label for="name">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
            @error('description')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>
      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize">PROCEED TO SAVE</button><br>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
{{-- <script src="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v3.2.min.js" type="text/javascript"></script> --}}
<script>
  $('#start_date_np').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
  $('#end_date_np').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 10
  });
</script>
<script>
  $(function () {
    CKEDITOR.replace('description');
    CKEDITOR.config.autoParagraph = true;
    CKEDITOR.config.removeButtons = 'Paste';
    CKEDITOR.config.removePlugins = 'format,stylescombo,link,sourcearea,maximize,image,about';
  });
</script>
{{-- <script>
  $().ready(function() {
    $("#validate").validate({
      rules: {
        title : "required",
        description : "required",
      },
      messages: {
        title: " title is required **",
        description: " description is required **",
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
</script> --}}
@endpush