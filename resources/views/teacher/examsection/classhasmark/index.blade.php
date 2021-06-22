@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
          <h1 class="text-capitalize">You are managing <b>{{ $exams }}</b> for class <b>{{ $classes }}</b></h1>
      </div>
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item text-capitalize"> {{$exams}} </li>
          <li class="breadcrumb-item active text-capitalize"> {{$classes}} </li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.classhasmark.store')}}"  class="validate" id="validate">
       {{ csrf_field() }}
      <table class="table table-bordered table-hover position-relative m-0">
        <input type="hidden" name="classexam_id" value="{{$classexam_id}}">
        <input type="hidden" name="class_id" value="{{$class_id}}">
        <thead class="bg-dark text-center th-sticky-top">
          <tr>
            <th width="10">Sn</th>
            <th class="text-left">Subject</th>
            <th class="text-left">Theory/Practical</th>
            <th class="text-left">Compulsory/Optional</th>
            <th class="text-left">Full Mark</th>
            <th class="text-left">Pass mark</th>
            <th class="text-left">Room</th>
          </tr>
        </thead>
        @foreach($subjects as $key=>$data)             
        <tr class="text-center">
          <td class="text-left">{{$key+1}} </td>
          <td> 
            {{ $check_data != 0 ? $data->getSubject->name : $data->name }}
            <input type="hidden" name="subject_id[{{$key}}]" value="{{ $check_data != 0 ? $data->subject_id : $data->id }}">
          </td>
          <td> 
            {{ $check_data != 0 ? $data->getSubject->theory_practical : $data->theory_practical }}
          </td>
          <td> 
            {{ $check_data != 0 ? $data->getSubject->compulsory_optional : $data->compulsory_optional }}
          </td>
          <td>
            <input type="text" class="w-100 border-0 bg-transparent" name="full_mark[{{$key}}]" placeholder="Enter here..." id="full_mark" value="{{ $check_data != 0 ? $data->full_mark : '' }}">
            @error('full_mark')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </td>
          <td>
            <input type="text" class="w-100 border-0 bg-transparent" name="pass_mark[{{$key}}]" placeholder="Enter here..." id="pass_mark" value="{{ $check_data != 0 ? $data->pass_mark : '' }}">
          </td>
          <td>
            <input type="text" class="w-100 border-0 bg-transparent" name="room[{{$key}}]" placeholder="Enter here..." id="room" value="{{ $check_data != 0 ? $data->room : '' }}">
          </td>
        </tr>
        @endforeach
      </table>
      <button type="submit" class="btn btn-block btn-info d-none" data-toggle="tooltip" data-placement="top" title="Save" id="submit">Save</button>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$().ready(function() {
  $("#validate").validate({
    rules: {
      full_mark: "required",
      pass_mark: "required",
      room: "required"
    },
    messages: {
      full_mark: " full_mark field is required **",
      pass_mark: " pass_mark field is required **",
      room: " room field is required **"
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
  $(document).ready(function(){
    $('#full_mark,#pass_mark, #room').keyup(function(){
      if($(this).val().length !=0)
        $("#submit").removeClass('d-none');
      else
        $("#submit").addClass('d-none');
    })
  });
</script>
<!-- <script>
  $(document).ready(function(){
    $('#submit').attr('disabled',true);
    $('#full_mark,#pass_mark, #room').keyup(function(){
      if($(this).val().length !=0)
        $('#submit').attr('disabled', false);            
      else
        $('#submit').attr('disabled',true);
    })
  });
</script> -->
@endpush