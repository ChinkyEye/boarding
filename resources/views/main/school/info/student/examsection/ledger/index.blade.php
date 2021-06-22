@extends('main.school.include.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
@endpush
@section('school-content')
<?php $page = substr((Route::currentRouteName()), 6, strpos(str_replace('admin.','',Route::currentRouteName()), ".")); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize"></h1>
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
    <div class="card-header">
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="{{request()->url()}}" data-source-selector="#card-refresh-content"><i class="fas fa-sync-alt"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
      </div>
    </div>
    <div class="card-body">
      <form action="{{route('admin.studenthasmark.ledgermark')}}" method="GET">
        <div class="row pb-2">
          <div class="col">
            <select class="form-control filter_shift" name="exam_id" id="filter_exam">
              <option value="">Select Your Exam</option>
              @foreach ($exams as $key => $exam)
              <option value="{{ $exam->id }}" {{ old('exam_id') == $exam->id ? 'selected' : ''}}> 
                {{$exam->name}}
              </option>
              @endforeach
            </select>
            @error('exam_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="col">
            <select class="form-control filter_shift" name="shift_id" id="filter_shift">
              <option value="">Select Your Shift</option>
            </select>
            @error('shift_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="col">
            <select class="form-control filter_class" name="class_id" id="filter_class">
              <option value="">Select Your Class</option>
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="col">
            <select class="form-control filter_section" name="section_id" id="filter_section">
              <option value="">Select Your Section</option>

            </select>
          </div>
          <div class="col-1">
            <div class="form-group" align="center">
              <button type="submit" class="btn btn-default" id="submit">Go</button>
              <!-- <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button> -->
            </div>
          </div>
        </div>
        <!-- <input type="submit" name="submit" value="Export" class="btn btn-sm btn-info"> -->
      </form>
        <!-- <div class="w-100" id="replaceTable">
        </div> -->
        {{-- <input type="text" class="try">
        <input type="text" class="textbox" id="TEXTBOX"> --}}

    </div>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{url('/')}}/backend/js/jquery.dataTables.js"></script>
<script src="{{url('/')}}/backend/js/dataTables.bootstrap4.js"></script>
<script>
  $('#TEXTBOX').on("keydown", function(event){
      var keyCode = event.which;
      var charCode = (event.charCode) ? event.charCode : ((event.keyCode) ? event.keyCode: ((event.which) ?   evt.which : 0));
      var char = String.fromCharCode(charCode);
      var re = new RegExp("[0-9]", "i");
      if (re.test(char) ||  event.which == 65) 
      {
        // alert('laxmi');
           // event.preventDefault(); 
      }
      else{
        toastr.error('hello');
         return false;
        // alert('this is not allowed');
           // event.preventDefault(); 

      }
  });

</script>
<script>
 $(function(){
   $('.try').keypress(function(e){
     if( e.which == 98 || e.which == 99 || e.which == 110 || e.which == 111 || e.which == 65 || e.which == 66 || e.which == 67 || e.which == 78 || e.which == 79 || e.which == 49 || e.which == 50 || e.which == 51 || e.which == 52 ||  e.which == 53 ||  e.which == 54 || e.which == 55 || e.which == 56|| e.which == 57 || e.which == 58 || e.which == 59 || e.which == 48){
      toastr.success('Now Select Shift');
     } else {
      toastr.error('hello');
       return false;
     }
   });
 });
</script>
<script type="text/javascript">
  $('#submit').prop('disabled', true);
  $("body").on("change","#filter_exam", function(event){
    Pace.start();
    toastr.success('Now Select Shift');
    var data_id = $(event.target).val();
    $('#excExam').val(data_id);
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getExamShiftList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
        $('#filter_shift').html('');
        $('#filter_shift').append('<option value="">--Choose Shift--</option>');
        $.each( response, function( i, val ) {
          $('#filter_shift').append('<option value='+val.id+'>'+val.name+'</option>');
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
  $("body").on("change","#filter_shift", function(event){
    Pace.start();
    // debugger;
    toastr.success('Now Select Class');
    var data_id = $(event.target).val();
    var exam_id = $("#filter_exam").val();
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getExamClassList')}}",
      data:{
        _token: token,
        data_id: data_id,
        exam_id: exam_id
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
    Pace.start();
    $('#submit').prop('disabled', false);
    var data_id = $(event.target).val();
      $('#excClass').val(data_id);
      $('#idcardClass').val(data_id);
       var shift_id = $('#filter_shift').val();
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
        alertify.alert("Sorry");
      }
    });
  });
</script>
@endpush


