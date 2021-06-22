@extends('backend.main.app')
@push('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="text-capitalize">{{ $page }}  Page</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}  Page</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('admin.teacherhasperiod.store')}}"  class="validate" id="validate">
     {{ csrf_field() }}
     <input type="hidden" name="teacher_id" value="{{$teacher_id}}">
     <div class="modal-body" >
      <div class="row">
        <div class="form-group m-0 col-sm-4">
          <!-- <label for="multiple" class="control-label"> Select Shift</label> -->
          <select class="form-control" name="shift_id" id="filter_shift">
            <option value=" ">Select Your Shift</option>
            @foreach ($teacher_shift as $key => $shift)
            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
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
        <div class="form-group m-0 col-sm-4">
          <!-- <label for="multiple" class="control-label"> Select Class</label> -->
          <select class="form-control" name="class_id" id="filter_class">
            <option value=" ">Select Your Class</option>
          </select>
          @error('class_id')
          <span class="text-danger font-italic" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="form-group m-0 col-sm-3">
          <!-- <label for="multiple" class="control-label"> Select Section</label> -->
          <select class="form-control" name="section_id" id="filter_section">
            <option value=" ">Select Your Section</option>
            
          </select>
          @error('section_id')
          <span class="text-danger font-italic" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>
        <div class="col-sm-1">
          <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Save">Save</button>
        </div>
      </div>
  </div>
</form>
  </div>
   <div class="table-responsive">
     <table class="table table-striped table-bordered table-hover">
       <thead class="bg-dark text-center">
         <tr>
           <th width="10">SN</th>
           <th>Class</th>
           <th width="100">Shift</th>
           <th width="100">Section</th>
           <th width="200">Created By</th>
           <th width="10">Status</th>
           <th width="10">Sort</th>
           <th width="100">Action</th>
         </tr>
       </thead>
       @foreach($teacherhasperiods as $key=>$teacherhasperiod)
       <tr class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $teacherhasperiod->is_active == '1' ? 'This data is published':' This data is not published'}}" style="background-color: {{$teacherhasperiod->is_active == '1' ? '#cde0ba' : '#eccdcd'}}">
         <td>{{$key+1}}</td>
         <td>{{$teacherhasperiod->sclass->name}}</td>
         <td>{{$teacherhasperiod->shift->name}}</td>
         <td>{{$teacherhasperiod->section->name}}</td>
         <td>{{$teacherhasperiod->getUser->name}}</td>
         <td>
           <a href="{{ route('admin.teacherhasperiod.active',$teacherhasperiod->id) }}" data-toggle="tooltip" data-placement="top" title="{{ $teacherhasperiod->is_active == '1' ? 'Click to deactivate' : 'Click to activate' }}">
             <i class="fa {{ $teacherhasperiod->is_active == '1' ? 'fa-check check-css':'fa-times cross-css'}}"></i>
           </a>
         </td>
         <td>
           <p id="main{{$teacherhasperiod->id}}" ids="{{$teacherhasperiod->id}}" class="text-center sort mb-0" page="teacherhasperiod" contenteditable="plaintext-only" url="{{route('admin.teacherhasperiod.sort',$teacherhasperiod->id) }}">{{$teacherhasperiod->sort_id}}</p>
         </td>
         <td class="text-center">
           <a href="{{ route('admin.teacherhasperiod.edit',$teacherhasperiod->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update Subject"><i class="fas fa-edit"></i></a>
           <form action="{{ route('admin.teacherhasperiod.destroy',$teacherhasperiod->id) }}" method="post" class="d-inline-block delete-confirm" data-toggle="tooltip" data-placement="top" title="Permanent Delete">
             @csrf
             @method('DELETE')
             <button class="btn btn-xs btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
           </form>
        </td>
      </tr>
      @endforeach              
    </table>
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
<script>
  $(document).ready(function() {
      $('.js-multiple').select2();
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endpush