@extends('teacher.main.app')
@section('content')
<?php $page = request()->segment(count(request()->segments())) ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{route('teacher.home')}}">Home</a></li>
          <li class="breadcrumb-item active text-capitalize">{{ $page }}</li>
        </ol>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col">
          <select class="form-control filter_shift" name="exam_id" id="filter_exam">
            <option value="">Select Your Exam</option>
            @foreach ($exams as $key => $exam)
            <option value="{{ $exam->id }}" {{ old('exam_id') == $exam->id ? 'selected' : ''}}> 
              {{$exam->name}}
            </option>
            @endforeach
          </select>
        </div>
        <div class="col">
          <select class="form-control filter_shift" name="shift_id" id="filter_shift">
            <option value="">Select Your Shift</option>
          </select>
        </div>
        <div class="col">
          <select class="form-control filter_class" name="class_id" id="filter_class">
            <option value="">Select Your Class</option>

          </select>
        </div>
        <div class="col">
          <select class="form-control filter_section" name="section_id" id="filter_section">
            <option value="">Select Your Section</option>

          </select>
        </div>
        <div class="col-1">
          <div class="btn-group btn-block" align="center">
            <button type="button" name="search" id="search" class="btn btn-default">Go</button>
            <!-- <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button> -->
          </div>
        </div>
      </div>
    </div>
      <form role="form" method="POST" action="{{ route('teacher.teacher-attendance')}}">
        <div class="card-body">
          @csrf
          <div id="replaceTable">
            
          </div>
        </div>
      </form>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript">
  $("body").on("change","#filter_exam", function(event){
    Pace.start();
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.getExamShiftList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
         Pace.restart();
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
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.getExamClassList')}}",
      data:{
        _token: token,
        data_id: data_id
      },
      success: function(response){
         Pace.restart();
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
    var data_id = $(event.target).val(),
    shift_id = $('#filter_shift').val(),
    exam_id = $('#filter_exam').val(),
    token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('teacher.getSectionList')}}",
      data:{
        _token: token,
        data_id: data_id,
        shift_id:shift_id,
        exam_id:exam_id
      },
      success: function(response){
         Pace.restart();
        $('#filter_section').html('');
        $('#filter_section').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#filter_section').append('<option value='+val.id+'>'+val.name+'</option>');
        });
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
<script>
document.addEventListener('keypress', function(event) {
                 if (event.keyCode == 13) {
                     event.preventDefault();
                     $("#search").click();
                 }
             });
$('#search').click(function () {
    var searchParams = {};
    var base_url = "{{route('teacher.getExamList')}}";

    searchParams['shift_id']=$('#filter_shift').val();
    searchParams['class_id']=$('#filter_class').val();
    searchParams['section_id']=$('#filter_section').val();
    searchParams['exam_id']=$('#filter_exam').val();
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        data: 'parameters= ' + JSON.stringify(searchParams) + '&_token=' + token,
        url: base_url,
        success: function (data) {
            $('#replaceTable').html(data);
        }
    });
    return false;
});

</script>
@endpush