@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
@endpush
@section('content')
<section class="content-header"></section>
<section class="content">
  {{-- <input type="submit" name="submit" value="Export" class="btn btn-sm btn-info rounded-0" disabled> --}}
  <div class="card">
    <div class="card-header">
      <form action="{{route('admin.studenthasmark.ledgermark')}}" method="GET">
        @csrf
        <div class="row">
          <div class="col-md">
            <select class="form-control filter_shift" name="exam_id" id="filter_exam">
              <option value="">Select Your Exam</option>
              @foreach ($exams as $key => $exam)
              <option value="{{ $exam->id }}" {{ $request->exam_id == $exam->id ? 'selected' : ''}}> 
                {{$exam->name}}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-md">
            <select class="form-control filter_shift" name="shift_id" id="filter_shift">
              <option value="">Select Your Shift</option>
            </select>
          </div>
          <div class="col-md">
            <select class="form-control filter_class" name="class_id" id="filter_class">
              <option value="">Select Your Class</option>

            </select>
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
      </form>
    </div>
    <div class="card-body">
      <div class="float-right">
        <button type="button" class="btn btn-xs btn-info rounded-0" onclick="PrintDiv('printTable')">Print me</button>
      </div>
     <form role="form" method="POST" action="{{ route('admin.studenthasmark.storemark')}}" id="printTable">
      @csrf
      <div class="w-100  table-responsive">
        <small>
          <div class="text-center mb-3">
            <h4 class="mb-0">{{$exams_name}}</h4>
            <h5 class="mb-0 text-uppercase">Marks Ledger Search results</h5> 
            @if($shifts_name)  <b>Shift:</b> {{$shifts_name}} | @endif 
            <b> Class:</b>{{$classes_name}} 
            @if($sections_name) | <b>Section:</b> {{$sections_name}}  @endif 
          </div>
        </small>
        <p class="font-weight-bold mb-0"><span class="text-danger">*</span> If student is absent type small letter '<span class="text-danger">a</span>' and if subject has no marks type '<span class="text-danger">-</span>'.</p>
        <table class="table table-bordered table-hover position-relative m-0 table-sm">
          <input type="hidden" name="classexam_id" value="{{$classexam_id}}" id="classexam_id">
          <input type="hidden" name="exam_id" value="{{$exam_id}}" id="exam_id">
          <input type="hidden" name="class_id" value="{{$class_id}}">
          <thead class="bg-dark text-center th-sticky-top inverted">
            <tr>
              <th class="v-align-middle" width="100" rowspan="2">Roll no</th>
              <th class="v-align-middle text-left" rowspan="2">Student Name</th>
              @foreach($classmarks as $classmark)   
              <th width="150" class="v-align-middle" colspan="">
                <small>
                  Cr. {{$classmark->getSubject->credit_hour}} hr
                </small>
                <br>
                {{$classmark->getSubject->name}} {{$classmark->getSubject->compulsory_optional == 2 ? '(Opt)' : '' }} 
                <br> 
                <small>
                  ({{$classmark->pass_mark}}/{{$classmark->full_mark}})
                </small>
              </th>          
              @endforeach
              <th class="v-align-middle" width="10" rowspan="2">Save</th>
            </tr>
            <tr>
              @foreach($classmarks as $classmark) 
              <th width="150" class="v-align-middle">{{$classmark->type_id == 1 ? 'Th.' : 'P.' }}</th>
              @endforeach
            </tr>
          </thead>
          @foreach($datas as $key=>$data)             
          <tr class="text-center" key="{{$key}}">
            <td>{{$data->roll_no}}</td>
            <td class="text-left">
              {{$data->getStudentUser->name}} {{$data->getStudentUser->middle_name}} {{$data->getStudentUser->last_name}}({{$data->student_code}})

              <input type="hidden" name="user_id[]" value="{{$data->user_id}}" id="user_id_{{$key}}">
              <input type="hidden" name="student_id[]" value="{{$data->id}}" id="student_id_{{$key}}">
            </td>
            @php
            $check = $data->getStudentMark()->where('classexam_id',$classexam_id);
            $check_val = $data->getStudentMark()->where('classexam_id',$classexam_id)->get();
            @endphp
            @if ($check->count())
            @foreach ($check_val as $element)
            <td data-id="{{$element->classmark_id}}" id="subject_{{$key}}">
              <input type="text" class="border-0 w-50 bg-transparent text-center obtained_mark" name="obtained_mark[{{$element->classmark_id}}][{{$key}}]" id="obtained_mark_{{$element->classmark_id}}_{{$key}}" value="{{ $element->obtained_mark }}" {{ $element->id == 2 ? 'required="true"' : 'required="false"' }}>
              {{-- [{{$element->classmark_id}}][{{$key}}] --}}
              <input type="hidden" name="subject_id[{{$element->classmark_id}}]" id="subject_id_{{$element->classmark_id}}" value="{{$element->subject_id}}">
              <input type="hidden" name="type_id[{{$element->classmark_id}}][{{$key}}]" id="type_id_{{$element->classmark_id}}_{{$key}}" value="{{$element->type_id}}">
              <input type="hidden" name="full_mark[{{$element->classmark_id}}][{{$key}}]" id="full_mark_{{$element->classmark_id}}_{{$key}}" value="{{$element->getStudentMarkList->full_mark}}">
              <input type="hidden" name="classmark_id[{{$element->classmark_id}}][{{$key}}]" id="classmark_id_{{$element->classmark_id}}_{{$key}}" value="{{$element->classmark_id}}">
            </td>
            @endforeach
            @else
            @foreach($classmarks as $index=>$classmark)
            <td data-id="{{$classmark->id}}" id="subject_{{$key}}">
              <input type="text" name="obtained_mark[{{$classmark->id}}][{{$key}}]" id="obtained_mark_{{$classmark->id}}_{{$key}}" class="border-0 w-50 bg-transparent text-center obtained_mark_{{$key}}" {{ $classmark->getSubject->compulsory_optional != 2 ? 'required="true"' : 'required="false"' }} value="">
              <input type="hidden" name="subject_id[{{$classmark->id}}]" id="subject_id_{{$classmark->id}}" value="{{$classmark->getSubject->id}}">
              <input type="hidden" name="type_id[{{$classmark->id}}][{{$key}}]" id="type_id_{{$classmark->id}}_{{$key}}" value="{{$classmark->type_id}}">
              <input type="hidden" name="full_mark[{{$classmark->id}}][{{$key}}]" id="full_mark_{{$classmark->id}}_{{$key}}" value="{{$classmark->full_mark}}">
              <input type="hidden" name="classmark_id[{{$classmark->id}}][{{$key}}]" id="classmark_id_{{$classmark->id}}_{{$key}}" value="{{$classmark->id}}">
            </td>
            @endforeach
            @endif
            <td>
              <button type="button" class="btn btn-block btn-info rounded-0 btn-xs d-none" onclick="saveMark()" id="submit_{{$key}}">Save</button>
            </td>
          </tr>
          @endforeach
        </table>
        <button type="submit" class="btn btn-block btn-info rounded-0 d-none" id="all_submit_{{$classmark->id}}_{{$key}}">Save</button>
      </div>
    </form>
  </div>
  </div>
</section>
@endsection
@push('javascript')
<script>
  {{-- reset --}}
  $('#reset').click(function(){
    $('#filter_exam').val('');
    $('#filter_shift').val('');
    $('#filter_class').val('');
    $('#filter_section').val('');
    $('#submit').prop('disabled', true);
  });

  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });

  $('#all_submit_{{$classmark->id}}_{{$key}}').keyup(function(e) {
  // debugger;
    $("#all_submit_{{$classmark->id}}_{{$key}}").removeClass('d-none');
  });
</script>

<script>
  function checkRequired(e) {
    var required = e.attributes.getNamedItem("required").value,
        value = e.value;
        // if (required == true){
        //   alert('required');
        // }else{
        //   alert('optional');
        // }
    if (required == true) {
      if(value == "") {
      // alert('hello');
      // break;
        $("#submit_"+key).addClass('d-none');
      }
      else if(value != ""){
        $("#submit_"+key).removeClass('d-none');
      }
    } 
    
  }

  $("input[type='text']").on('change keyup paste', function (e) {
    var key = $(event.target).parents('tr').attr('key');
    var keyCode = event.which;
    var charCode = (event.charCode) ? event.charCode : ((event.keyCode) ? event.keyCode: ((event.which) ?   evt.which : 0));
    var char = String.fromCharCode(charCode);
    var re = new RegExp("[0-9]", "i");
    // var required = this.attributes.getNamedItem("required").value;
    var data_field = Array.from(document.getElementsByClassName("obtained_mark_"+key));
    data_field.forEach(checkRequired);

    

    // if (required == true) {
    //   $("#submit_"+key).addClass('d-none');
    // }else if (required == false){
    //   $("#submit_"+key).removeClass('d-none');
    // }
    
    if (re.test(char) || event.which == 65 || charCode == 0 || charCode == 109 || charCode == 189 || (event.which >= 96 && event.which <= 105))
    {
      // char 'a' = 65 , null , char '-', numpad '-' numpad 0-9
      $("#submit_"+key).removeClass('d-none');
    }
    else if(event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46){
      // tab 9 , left arrow, right arrow, delete
      // toastr.warning('Only number or "a" (for absent) or "-" (for no marks) character is allowed.');
    } else if(event.which == 8){
      // backspace 8, 
      $("#submit_"+key).addClass('d-none');
    }
    else{
      toastr.error('Only number or "a" (for absent) or "-" (for no marks) character is allowed.');
      $("#submit_"+key).addClass('d-none');
    }
  });
  
  function saveMark() {
    Pace.start();
    var key = $(event.target).parents('tr').attr('key'),
      user_id = new Array($('#user_id_'+key).val()),
      student_id = $('#student_id_'+key).val(),
      classexam_id = $('#classexam_id').val(),
      exam_id = $('#exam_id').val(),
          token = $('meta[name="csrf-token"]').attr('content');


    var classmark_id = new Array();
    $('td[id="subject_'+key+'"]').each(function (index, value) {
      var data = $(this).attr('data-id');
      classmark_id.push(data);
    });
    var obtained_mark = new Array(),
      subject_id = new Array(),
      type_id = new Array(),
      classmark = new Array(),
      full_mark = new Array();
    classmark_id.forEach(classMark);
    console.log(obtained_mark);

    if(obtained_mark == 'required'){

      // alert('rhello')
      // $('#grade_'+key).val('');
      // $('#grade_point_'+key).val('');
      // $('#percentage'+key).val('');
      // $('#obtained_mark_'+key).addClass('is-invalid');
      // $('#obtained_mark_'+key).removeClass('is-valid');
      // $("#submit").addClass('d-none');
    }

    function classMark(data_id) {
      var obtain = $('#obtained_mark_'+data_id+'_'+key).val();
      obtained_mark.push(new Array(obtain));

      var subject = $('#subject_id_'+data_id).val();
      subject_id.push(subject);

      var type = $('#type_id_'+data_id+'_'+key).val();
      type_id.push(new Array(type));

      var full_m = $('#full_mark_'+data_id+'_'+key).val();
      full_mark.push(new Array(full_m));

      classmark.push(new Array(data_id));
    }

    $.ajax({
        type:"POST",
        dataType:"JSON",
        url:"{{route('admin.studenthasmark.storemark')}}",
        data:{
          _token: token,
          user_id: user_id,
          student_id: student_id,
          classexam_id: classexam_id,
          exam_id: exam_id,
          obtained_mark: obtained_mark,
          subject_id: subject_id,
          type_id: type_id,
          full_mark: full_mark,
          classmark_id:classmark,
        },
        success: function(response){
          if(response.status == 'failure'){
            toastr.error(response.msg);
          }
          else{
            toastr.success(response.msg);
            // location.reload();
          }
        },
        error: function(response){
          toastr.error(" Sorry, something is missing! Try again.");
        }
      });
      Pace.stop();

  }
</script>
<script type="text/javascript">
  $(document).ready(function(event){

  // $("body").on("change","#filter_exam", function(event){
    Pace.start();
    var exam_id = $("#filter_exam").val();
       console.log(exam_id);
       
    $('#excExam').val(exam_id);
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getExamShiftList')}}",
      data:{
        _token: token,
        data_id: exam_id
      },
      success: function(response){
        $('#filter_shift').html('');
        $('#filter_shift').append('<option value="">--Choose Shift--</option>');
        $.each( response, function( i, val ) {
          if({{ $request->shift_id == null ? '0' : $request->shift_id }} == val.id){
            $('#filter_shift').append('<option value='+val.id+' selected>'+val.name+'</option>');
          }else{
            $('#filter_shift').append('<option value='+val.id+'>'+val.name+'</option>');
          }
        });
        var shift_id = $("#filter_shift").val();
        console.log($("#filter_shift").val());
       
        $.ajax({
          type:"POST",
          dataType:"JSON",
          url:"{{route('admin.getExamClassList')}}",
          data:{
            _token: token,
            data_id: shift_id,
            exam_id: exam_id
          },
          success: function(response){
            $('#filter_class').html('');
            $('#filter_class').append('<option value="">--Choose Class--</option>');
            $.each( response, function( i, val ) {
              if({{ $request->class_id == null ? '0' : $request->class_id }} == val.id){
                $('#filter_class').append('<option value='+val.id+' selected>'+val.name+'</option>');
              }else{
                $('#filter_class').append('<option value='+val.id+'>'+val.name+'</option>');

              }
            });

           var class_id = $("#filter_class").val();
           console.log($("#filter_class").val());

            $.ajax({
              type:"POST",
              dataType:"JSON",
              url:"{{route('admin.getSectionList')}}",
              data:{
                _token: token,
                class_id: class_id,
                exam_id: exam_id,
                shift_id: shift_id
              },
              success: function(response){
                $('#filter_section').html('');
                $('#filter_section').append('<option value="">--Choose Section--</option>');
                $.each( response, function( i, val ) {
                  // debugger;
                  if({{ $request->section_id == null ? '0' : $request->section_id }} == val.id){
                    $('#filter_section').append('<option value='+val.get_section.id+' selected>'+val.get_section.name+'</option>');
                  }else{
                    $('#filter_section').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');
                  }
                });
                $('#submit').prop('disabled', false);
              },
              error: function(event){
                alert("Sorry");
                Pace.stop();
              }
            });
          },
          error: function(event){
            alert("Sorry");
            Pace.stop();
          }
        });
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });

  $("body").on("change","#filter_exam", function(event){
    Pace.start();
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
    var data_id = $(event.target).val();
    var exam_id = $('#filter_exam').val();
     $('#excShift').val(data_id);
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
    var class_id = $('#filter_class').val();
    var shift_id = $('#filter_shift').val();
    $('#excClass').val(class_id);
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getSectionList')}}",
      data:{
        _token: token,
        class_id: class_id,
        shift_id: shift_id
      },
      success: function(response){
        $('#filter_section').html('');
        $('#filter_section').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#filter_section').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');
        });
        $('#submit').prop('disabled', false);
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
</script>
@endpush