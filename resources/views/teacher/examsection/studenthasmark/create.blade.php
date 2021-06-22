@extends('teacher.main.app')
@section('content')
<section class="content-header">
  <small>
    <div class="text-center mb-3">
      <h4 class="mb-0">{{$exam_name->name}}</h4>
      <h5 class="mb-0 text-uppercase">Marks Ledger Search results</h5> 
      @if($shift_name)  <b>Shift:</b> {{$shift_name->name}} | @endif 
      <b> Class:</b>{{$class_name->name}} 
      @if($section_name) | <b>Section:</b> {{$section_name->name}}  @endif 
      <h5>{{$student_info->getStudentUser->name}} {{$student_info->getStudentUser->middle_name}} {{$student_info->getStudentUser->last_name}} ({{$student_info->student_code}}) / Roll no: {{$student_info->roll_no}}</h5>
    </div>
  </small>
</section>
<section class="content">
  <p class="font-weight-bold mb-0"><span class="text-danger">*</span> If student is absent type small letter '<span class="text-danger">a</span>' and if subject has no marks type '<span class="text-danger">-</span>'.</p>
  <div class="card card-info">
    <form role="form" method="POST" action="{{route('teacher.studenthasmark.store')}}"  class="validate" id="validate">
       {{ csrf_field() }}
      <table class="table table-bordered table-hover position-relative m-0">
        <input type="hidden" name="student_id" value="{{$student_id}}">
        <input type="hidden" name="classexam_id" value="{{$classexam_id}}">
        <input type="hidden" name="exam_id" value="{{$exam_id}}">
        <input type="hidden" name="class_id" value="{{$class_id}}">
        <thead class="bg-dark text-center th-sticky-top">
          <tr>
            <th width="10">Sn</th>
            <th width="10">Subject</th>
            <th width="10">Full</th>
            <th width="10">Pass</th>
            <th width="10">Theory/Practical</th>
            <th width="100">Compulsory/Optional</th>
            <th width="10">Obtained Mark</th>
            <th width="10">%</th>
            <th width="10">Grade</th>
            <th width="10">Grade Point</th>
          </tr>
        </thead>
        @foreach($subjects as $key=>$data)             
        <tr class="text-center">
          <td class="text-left">{{$key+1}} </td>
          <td class="text-left"> 
            {{ $check_data != 0 ? $data->getSubject->name : $data->getSubject->name }}
            <input type="hidden" id="full_mark{{$key}}" value="{{  $check_data != 0 ?  $data->getStudentMarkList->full_mark : $data->full_mark }}">
            <input type="hidden" name="subject_id[{{$key}}]"value="{{ $data->subject_id }}">
            <input type="hidden" name="type[{{$key}}]"value="{{ $data->type_id }}">
            <input type="hidden" name="classmark_id[{{$key}}]"value="{{  $check_data != 0 ?  $data->classmark_id : $data->id }}">
          </td>
          <td>{{  $check_data != 0 ?  $data->getStudentMarkList->full_mark : $data->full_mark }}</td>
          <td> {{  $check_data != 0 ?  $data->getStudentMarkList->pass_mark : $data->pass_mark }}</td>
         {{--  <td> {{ $check_data != 0 ? ($data->getSubject->theory_practical === 3 ? "Both" : ($data->getSubject->theory_practical ===1 ? "Theory" : "Practical")) : ($data->getSubject->theory_practical === 3 ? "Both" : ($data->getSubject->theory_practical ===1 ? "Theory" : "Practical")) }}</td> --}}
          <td> {{ $check_data != 0 ? ($data->type_id === 1 ? "Theory" : "Practical") :($data->type_id === 1 ? "Theory" : "Practical") }}</td>
         <td> {{$check_data != 0 ? ($data->getSubject->compulsory_optional == 1 ? 'Compulsory' : 'Optional') :($data->getSubject->compulsory_optional == 1 ? 'Compulsory' : 'Optional') }}</td>
          <td>
            <input type="text" class="form-control p-0 h-75 w-100 border-0 bg-transparent text-center" name="obtained_mark[]" placeholder="Marks" value="{{ $data->obtained_mark ? $data->obtained_mark : '' }}" onkeyup="percentage()" keys="{{$key}}" id="obtained_mark_{{$key}}" >
            @error('obtained_mark')
            <span class="text-danger font-italic" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </td>
          <td>
            <input type="text" class="w-100 border-0 bg-transparent text-center" name="percentage[]" id="percentage{{$key}}" value="{{$check_data != 0  ? $data->percentage : '' }}" readonly>
          </td>
          <td>
            <input type="text" class="w-100 border-0 bg-transparent text-center" name="grade[]" id="grade_{{$key}}" value="{{ $check_data != 0  ? $data->grade : '' }}" readonly>
          </td>
          <td>
            <input type="text" class="w-100 border-0 bg-transparent text-center" name="grade_point[]" id="grade_point_{{$key}}" value="{{ $check_data != 0  ? $data->grade_point : '' }}" readonly>
          </td>
        </tr>
        @endforeach
      </table>

      <button type="submit" class="btn btn-block btn-info rounded-0 d-none" data-toggle="tooltip" data-placement="top" title="Save" id="submit">Save</button>
    </form>
  </div>
</section>
@endsection
@push('javascript')
<script>
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });
</script>
<script>
  function percentage(){
    Pace.start();
    var key = $(event.target).attr('keys'),
        marks = document.getElementById('obtained_mark_'+key).value,
        full_mark = document.getElementById('full_mark'+key).value;
    var percentage = ((parseInt(marks) / full_mark) * 100);
    var token = $('meta[name="csrf-token"]').attr('content');
            // debugger;
    // validation
    var keyCode = event.which;
    var charCode = (event.charCode) ? event.charCode : ((event.keyCode) ? event.keyCode: ((event.which) ?   evt.which : 0));
    var char = String.fromCharCode(charCode);
    var re = new RegExp("[0-9]", "i");

    if (re.test(char) || event.which == 65 || charCode == 0 || charCode == 109 || charCode == 189 || (event.which >= 96 && event.which <= 105))
    {
      // char 'a' = 65 , null , char '-', numpad '-' numpad 0-9
      $("#submit").removeClass('d-none');
      $('#grade_'+key).val('');
      $('#grade_point_'+key).val('');
      $('#percentage'+key).val('');
      $('#obtained_mark_'+key).addClass('is-valid');
      $('#obtained_mark_'+key).removeClass('is-invalid');
      // return false;
    }
    else if(event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46){
      // tab 9 , left arrow, right arrow, delete
      // toastr.warning('Only number or "a" (for absent) or "-" (for no marks) character is allowed.');
    } else if(event.which == 8){
      // backspace 8, 
      $("#submit").addClass('d-none');
      $('#obtained_mark_'+key).addClass('is-invalid');
      return false;
    }
    else{
      toastr.error('Only number or "a" (for absent) or "-" (for no marks) character is allowed.');
      $("#submit").addClass('d-none');
      $('#obtained_mark_'+key).addClass('is-invalid');
      return false;
    }
    // 
    if(marks == ''){
      $('#grade_'+key).val('');
      $('#grade_point_'+key).val('');
      $('#percentage'+key).val('');
      $('#obtained_mark_'+key).addClass('is-invalid');
      $('#obtained_mark_'+key).removeClass('is-valid');
      $("#submit").addClass('d-none');
    }
    // else if(marks == '-' || marks == 'a' ){
    //    $('#grade_'+key).val('');
    //   $('#grade_point_'+key).val('');
    //   $('#percentage'+key).val('');
    //   $('#obtained_mark_'+key).addClass('is-valid');
    // }
    if (!isNaN (percentage) ) {
      document.getElementById('percentage'+key).value = percentage.toFixed(2);
      $.ajax({
        type:"POST",
        url:"{{route('teacher.getStudentExamGrade')}}",
        data:{
          _token: token,
          percentage: percentage
        },
       
        success: function(response){
          $('#grade_'+key).val(response.value);
          $('#grade_point_'+key).val(response.data);
          if(response.status == 'failure'){
            toastr.error(response.msg);
            // debugger;
            $('#grade_'+key).val('');
            $('#grade_point_'+key).val('');
            $('#percentage'+key).val('');
            $('#obtained_mark_'+key).val('');
            $('#obtained_mark_'+key).addClass('is-invalid');
            $('#obtained_mark_'+key).removeClass('is-valid');
          }
          else{
            toastr.success(response.msg);
            $('#obtained_mark_'+key).addClass('is-valid');
            $('#obtained_mark_'+key).removeClass('is-invalid');
            // $("#submit").addClass('d-none');
          }
        },
        error: function(response){
          toastr.error(" Sorry, data is incorrect! Try again.");
        }
      });
    }
    Pace.stop();
    $("#submit").removeClass('d-none');
  }
</script>
<script src="{{URL::to('/')}}/backend/js/jquery.validate.js"></script>
<script>
$('body').ready(function() {
  $("#validate").validate({
    rules: {
      obtained_mark: "required"
    },
    messages: {
      obtained_mark: " obtained_mark field is required **"
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
@endpush