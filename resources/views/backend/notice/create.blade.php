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
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.notice.store')}}" class="validate" id="validate">
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
<script type="text/javascript">
$(document).ready(function(){
  var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#start_date_np').val(currentDate);
    $('#start_date_np').nepaliDatePicker({
      ndpMonth: true,
    });
    $('#end_date_np').val(currentDate);
    $('#end_date_np').nepaliDatePicker({
      ndpMonth: true,
    });
  });
</script>
<script>
  $(function () {
    CKEDITOR.replace('description');
    CKEDITOR.config.autoParagraph = false;
    CKEDITOR.config.removeButtons = 'Paste';
    CKEDITOR.config.removePlugins = 'format,stylescombo,link,sourcearea,maximize,image,about,tabletools';
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