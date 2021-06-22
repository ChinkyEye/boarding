@extends('teacher.main.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
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
          <li class="breadcrumb-item"><a href="{{route('teacher.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    @foreach($remainders as $remainder)
    <form role="form" method="POST" action="{{ route('teacher.remainder.update',$remainder->id)}}" class="signup" id="signup">
      <div class="card-body">
        @method('PATCH')
        @csrf
        <div class="row">
          <div class="form-group col-md-12">
            <label for="title">Title<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" name="title" autocomplete="off" value="{{$remainder->title}}">
            @error('title')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Shift</label>
            <select class="form-control" name="shift_id" id="shift_data" >
              <option value=" ">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" @foreach($remainders as $key => $remainder) {{$remainder->shift_id == $shift->id ? 'selected' : ''}} @endforeach> 
                {{$shift->name}}
              </option>
              @endforeach
            </select>
            @error('shift_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror    
          </div>
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Class</label>
            <select class="form-control" name="class_id" id="class_data">
              <option value=" ">Select Your Class</option>
              @foreach ($classes as $key => $class)
              <option value="{{ $class->id }}" @foreach($remainders as $key => $remainder) {{$remainder->class_id == $class->id ? 'selected' : ''}} @endforeach> 
                {{$class->name}}
              </option>
              @endforeach
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror    
          </div>
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label">Section</label>
            <select class="form-control" name="section_id" id="section_data">
              <option value=" ">Select Your Section</option>
              @foreach ($sections as $key => $section)
              <option  value="{{ $section->id }}" @foreach($remainders as $key => $remainder) {{$remainder->section_id == $section->id ? 'selected' : ''}} @endforeach> 
                {{$section->name}} 
              </option>
              @endforeach
            </select>
            @error('section_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror    
          </div>
          <div class="form-group col-md-12">
            <label for="name">Description</label>
            <textarea class="form-control" id="description" name="description">{!! $remainder->description !!}</textarea>
            @error('description')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
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
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script>
  $(function () {
    CKEDITOR.replace('description');
    CKEDITOR.config.autoParagraph = false;
    CKEDITOR.config.removeButtons = 'Anchor';
    CKEDITOR.config.removePlugins = 'format,stylescombo,link,sourcearea,maximize,image,about,tabletools,scayt';
  });
</script>
<script>
  $("#signup").validate({
    rules: {
      class_id : "required",
      section_id : "required",
      shift_id : "required",
      subject_id : "required",
      description: "required",
    },
    messages: {
      class_id: " class field is required **"
      section_id: " Section field is required **"
      shift_id: " shift field is required **"
      subject_id: " subject field is required **"
      description: " description field is required **"
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
</script>
<script type="text/javascript">
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.getClassList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
        $('#class_data').html('');
        $('#class_data').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#class_data').append('<option value='+val.id+'>'+val.name+'</option>');
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
    var data_id = $(event.target).val(),
        shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.getSectionSubjectList')}}",
      data:{
        _token: token,
        data_id: data_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#section_data').html('');
        $('#section_data').append('<option value="">--Choose Section--</option>');
        $('#subject_data').html('');
        $('#subject_data').append('<option value="">--Choose Subject--</option>');
        
        $.each(response, function(name) {
            $.each(this, function(i, val) {
              $('#'+name+'_data').append('<option value='+val.id+'>'+val.name+'</option>');
            });
        });
      },
      error: function(event){
        alert("Please reload the page.");
      }
    });
    Pace.stop();
  });
</script>
@endpush