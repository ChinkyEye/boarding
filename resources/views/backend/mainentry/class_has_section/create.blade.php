@extends('backend.main.app')
@section('content')
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
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.c_has_section.store')}}" class="signup" id="signup">
      <div class="card-body">
        @csrf
        <div class="row">
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label col-md-3">Class</label>
            <select class="form-control" name="class_id" id="class_id">
              <option value="">Select Your Class</option>
              @foreach ($classes as $key => $data)
              <option value="{{ $data->id }}" {{ old('class_id') == $data->id ? 'selected' : ''}}> 
                {{$data->name}}
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
            <label for="multiple" class="control-label col-md-3">Shift</label>
            <select class="form-control" name="shift_id" id="shift_id" >
              <option value="">Select Your Shift</option>
                
            </select>
            @error('shift_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="multiple" class="control-label col-md-3">Section</label>
            <select class="form-control" name="section_id">
              <option value="">Select Your Section</option>
              @foreach ($sections as $key => $section)
              <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : ''}}> 
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
        </div>
      </div>
      <div class="card-footer justify-content-between">
        <button type="submit" class="btn btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Save Shift">Save</button>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#signup").validate({
    rules: {
      class_id: "required"
      section_id: "required"
      shift_id: "required"
    },
    messages: {
      class_id: " class field is required **"
      section_id: " section field is required **"
      shift_id: " shift field is required **"
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
<script type="text/javascript">
  $("body").on("change","#class_id", function(event){
    Pace.start();
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getShiftList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
        $('#shift_id').html('');
        $('#shift_id').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#shift_id').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
@endpush