@extends('backend.main.app')
@push('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 0, strpos((Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page}} Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{URL::to('/')}}/home">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page}} Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    @foreach($teacherhasperiods as $teacherhasperiod)
    <form role="form" method="POST" action="{{route('admin.teacherhasperiod.update',$teacherhasperiod->id)}}">
      <div class="card-body">
        @csrf
        @method('PATCH')
        <div class="row">
          <div class="form-group col-sm-4">
            <label for="multiple" class="control-label"> Select Shift</label>
            <select class="form-control" name="shift_id" id="filter_shift">
              <option value=" ">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" {{ $shift->id == $teacherhasperiod->shift_id ? 'selected' : ''}}> 
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
          <div class="form-group col-sm-4">
            <label for="multiple" class="control-label"> Select Class</label>
            <select class="form-control" name="class_id" id="filter_class">
              <option value=" ">Select Your Class</option>
              @foreach($shifts as $shift_data)
              @foreach($shift_data->getShiftList()->get() as $class_shift)
              <option value="{{$class_shift->id}}" {{ $class_shift->class_id == $teacherhasperiod->class_id ? 'selected' : ''}}>{{$class_shift->getClass->name}}</option>
              @endforeach
              @endforeach
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group col-sm-4">
            <label for="multiple" class="control-label"> Select Section</label>
            <select class="form-control" name="section_id" id="filter_section">
              <option value=" ">Select Your Section</option>
              @foreach($shifts as $shift_sec)
                @foreach($shift_sec->getShiftList()->where('class_id',$teacherhasperiod->class_id )->get() as $class_shift_sec)
                  @foreach($class_shift_sec->getClassSection()->get() as $class_sec)
                  <option value="{{$class_sec->section_id}}" {{ $class_sec->section_id == $teacherhasperiod->section_id ? 'selected' : ''}}>{{$class_sec->getSection->name}}</option>
                  @endforeach
                @endforeach
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
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
    @endforeach
  </div>
</section>
@endsection
@push('javascript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      name: "required",
      subject_code: "required",
      theory_practical: "required",
      compulsory_optional: "required"
    },
    messages: {
      name: " name field is required **",
      subject_code: " subject_code field is required **",
      theory_practical: " theory_practical field is required **",
      compulsory_optional: " compulsory_optional field is required **"
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
<script>
  $(document).ready(function() {
      $('.js-multiple').select2();
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script type="text/javascript">
  $("body").on("change","#filter_shift", function(event){
    Pace.start();
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getClassList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
        $('#filter_class').html('');
        $('#filter_class').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#filter_class').append('<option value='+val.id+'>'+val.name+'</option>');
        });
        $('#filter_section').val('');
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
<script type="text/javascript">
  $("body").on("change","#filter_class", function(event){
    var data_id = $(event.target).val(),
        shift_id = $('#filter_shift').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getSectionList')}}",
      data:{
        _token: token,
        data_id: data_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#filter_section').html('');
        $('#filter_section').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#filter_section').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
  });
</script>
@endpush