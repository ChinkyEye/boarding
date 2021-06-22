@extends('backend.main.app')
@section('content')
<?php $page = 'Student Marks ledger'; ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize"></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Homea</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
    {{-- <input type="submit" name="submit" value="Export" class="btn btn-sm btn-info rounded-0" disabled> --}}
  <div class="card">
    <form action="{{route('admin.studenthasmark.ledgermark')}}" method="GET">
      @csrf
      <div class="card-header">
        <div class="row">
          <div class="col-md">
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
          <div class="col-md">
            <select class="form-control filter_shift" name="shift_id" id="filter_shift">
              <option value="">Select Your Shift</option>
            </select>
            @error('shift_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="col-md">
            <select class="form-control filter_class" name="class_id" id="filter_class">
              <option value="">Select Your Class</option>
            </select>
            @error('class_id')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="col-md">
            <select class="form-control filter_section" name="section_id" id="filter_section">
              <option value="">Select Your Section</option>

            </select>
          </div>
          <div class="col-md-2">
            <div class="btn-group btn-block">
              <button type="submit" class="btn btn-primary" id="submit" disabled="true">Go</button>
              <button type="button" id="reset" class="btn btn-warning">Reset</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript">
  $('#reset').click(function(){
    $('#filter_exam').val('');
    $('#filter_shift').val('');
    $('#filter_class').val('');
    $('#filter_section').val('');
    $('#submit').prop('disabled', true);
  });

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
         Pace.restart();
        $('#filter_shift').html('');
        $('#filter_shift').append('<option value="">--Choose Shift--</option>');
        $('#submit').prop('disabled', true);
        $.each( response, function( i, val ) {
          $('#filter_shift').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        toastr.error("Sorry");
      }
    });
    Pace.stop();
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
         Pace.restart();
        $('#filter_class').html('');
        $('#filter_class').append('<option value="">--Choose Class--</option>');
        $('#submit').prop('disabled', true);
        $.each( response, function( i, val ) {
          $('#filter_class').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        toastr.error("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("change","#filter_class", function(event){
    Pace.start();
    var class_id = $('#filter_class').val();
      $('#excClass').val(class_id);
      $('#idcardClass').val(class_id);
       var shift_id = $('#filter_shift').val();
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
         Pace.restart();
        $('#filter_section').html('');
        $('#filter_section').append('<option value="">--Choose Section--</option>');
        $('#submit').prop('disabled', true);
        $.each( response, function( i, val ) {
          $('#filter_section').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');
        });
      },
      error: function(event){
        toastr.error("Sorry");
      }
    });
    Pace.stop();
  });
  $("body").on("change","#filter_section", function(event){
    $('#submit').prop('disabled', false);
  });
</script>
@endpush