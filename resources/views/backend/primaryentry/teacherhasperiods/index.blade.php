@extends('backend.main.app')
@section('content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <div class="info-box mb-3 bg-info">
          <span class="info-box-icon"><i class="far fa-comment"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Please Assign the class for Teacher</span>
            <span class="info-box-number">{{$teacher->name}} {{$teacher->middle_name}} {{$teacher->last_name}}</span>
          </div>
        </div>
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
      @csrf
      <input type="hidden" name="teacher_id" value="{{$teacher_id}}" id="teacher_data">
      <div class="card-body">
        <div class="row">
          <div class="col-md">
            <select class="form-control" name="shift_id" id="shift_data">
              <option value="">Select Your Shift</option>
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
          <div class="col-md">
            <select class="form-control" name="class_id" id="class_data">
              <option value="">Select Your Class</option>
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="col-md">
            <select class="form-control" name="section_id" id="section_data">
              <option value="">Select Your Section</option>
            </select>
            @error('section_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>

          <div class="col-md-2">
            <div class="text-right">
              <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
              <button type="submit" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Save" id="data_save" disabled>Save</button>
            </div>
          </div>
          <div class="col-md-12 row mt-3" id="subject_data">
            @error('subject')
            <div class="text-danger font-italic text-center" role="alert">
              <strong>{{ $message }}</strong>
            </div>
            @enderror
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
          <th class="100">Class</th>
          <th width="text-left">Subject</th>
          <th width="200">Created By</th>
          <th width="10">Status</th>
          <th width="10">Sort</th>
          <th width="100">Action</th>
        </tr>
      </thead>
      @foreach($teacherhasperiods as $key=>$teacherhasperiod)
      <tr class="text-center" data-toggle="tooltip" data-placement="top" title="{{ $teacherhasperiod->is_active == '1' ? 'This data is published':' This data is not published'}}" style="background-color: {{$teacherhasperiod->is_active == '1' ? '#cde0ba' : '#eccdcd'}}">
        <td>{{$key+1}}</td>
        <td class="text-left">{{$teacherhasperiod->shift->name}} | {{$teacherhasperiod->sclass->name}} | {{$teacherhasperiod->section->name}}</td>
        <td>
          @foreach($teacherhasperiod->getTeacherSubject()->get() as $subject)
          <span class="badge badge-info">{{$subject->getSubject->name}} ({{$subject->getSubject->theory_practical == 1 ? 'Th' : 'Pr'}})</span>
          @endforeach
        </td>
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
          {{-- <a href="{{ route('admin.teacherhasperiod.edit',$teacherhasperiod->id) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Update Subject"><i class="fas fa-edit"></i></a> --}}
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
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$(document).ready(function() {
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
  $("body").on("change","#shift_data", function(event){
    Pace.start();
    var shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getClassList')}}",
      data:{
        _token: token,
        shift_id: shift_id
      },
      success: function(response){
        $('#class_data').html('');
        $('#class_data').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#class_data').append('<option value='+val.get_class.id+'>'+val.get_class.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
        Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    Pace.start();
    var class_id = $('#class_data').val(),
        shift_id = $('#shift_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getSectionList')}}",
      data:{
        _token: token,
        class_id: class_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#section_data').html('');
        $('#section_data').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#section_data').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#class_data", function(event){
    var class_id = $('#class_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getSubjectList')}}",
      data:{
        _token: token,
        class_id: class_id,
      },
      success: function(response){
        $('#subject_data').html('');
        $.each(response, function( p, bal ) {
          if(bal.theory_practical == 1){
            this.th_pr = "Th";
          } else if (bal.theory_practical == 2){
            this.th_pr = "Pr";
          } else {
            this.th_pr = "Both";
          }
          $('#subject_data').append('<div class="form-check col-md-2 mx-2"><input type="checkbox" class="form-check-input" id="subject'+bal.id+'" name="subject[]" value='+bal.id+'><label class="form-check-label" for="subject'+bal.id+'">'+bal.name+'('+this.th_pr+')</label></div>');
        });
      },
      error: function(event){
        alert("Sorry");
      }
    });
  });
  $('#reset').click(function(){
    $('#shift_data').val('');
    $('#class_data').val('');
    $('#section_data').val('');
    $('#subject_data').val('');
    $("#data_save").prop('disabled', false);
  });
</script>
<script>
  $("body").on("change","#section_data", function(event){
    Pace.start();
    var class_id = $('#class_data').val(),
        shift_id = $('#shift_data').val(),
        section_id = $('#section_data').val(),
        teacher_id = $('#teacher_data').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getTeacherRoutineClassList')}}",
      data:{
        _token: token,
        class_id: class_id,
        shift_id: shift_id,
        section_id: section_id,
        teacher_id: teacher_id,
      },
      success: function(response){
        if(response.data == 0){
          $("#data_save").prop('disabled', false);
        }else{
          $("#data_save").prop('disabled', true);
          toastr.error(response.msg);
        }
      },
      error: function(event){
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
@endpush