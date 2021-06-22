@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
@endpush
@section('content')
<section class="content-header"></section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <form action="{{route('admin.studenthasmark.ledgermark')}}" method="GET">
        @csrf
        <div class="row">
          <div class="col">
            <select class="form-control filter_shift" name="exam_id" id="filter_exam">
              <option value="">Select Your Exam</option>
              @foreach ($exams as $key => $exam)
              <option value="{{ $exam->id }}" {{ $request->exam_id == $exam->id ? 'selected' : ''}}> 
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
            <div class="form-group" align="center">
              <button type="submit" class="btn btn-default">Go</button>
              <!-- <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button> -->
            </div>
          </div>
        </div>
        <!-- <input type="submit" name="submit" value="Export" class="btn btn-sm btn-info"> -->
      </form>
    </div>
    <div class="card-body">
     <form role="form" method="POST" action="{{ route('admin.studenthasmark.storemark')}}">
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
              {{$data->first_name}} {{$data->middle_name}} {{$data->last_name}}

              <input type="hidden" name="user_id[]" value="{{$data->user_id}}" id="user_id_{{$key}}">
              <input type="hidden" name="student_id[]" value="{{$data->id}}" id="student_id_{{$key}}">
            </td>
            @php
            $check = $data->getStudentMark();
            $check_val = $data->getStudentMark()->get();
              // $check_val->collapse()->toArray();
            @endphp
            {{-- {{dump($check->get())}} --}}
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
        {{-- <table class="table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">Object</th>
                    <th colspan="2">Object</th>
                    
                    <th rowspan="2">Volume</th>
                </tr>
                <tr>
                    <th>sub header</th>
                    <th>sub header</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>row header</th>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                </tr>
            </tbody>
        </table> --}}
        <button type="submit" class="btn btn-block btn-info rounded-0 d-none" id="all_submit_{{$classmark->id}}_{{$key}}">Save</button>
      </div>
    </form>
  </div>
  </div>
</section>
@endsection
@push('javascript')
<script>
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
    // console.log(required);
    // console.log(e.value);
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
    
    // console.log(e.a/zttributes);
    // debugger;
  }

  $("input[type='text']").on('change keyup paste', function (e) {
    var key = $(event.target).parents('tr').attr('key');
    var keyCode = event.which;
    var charCode = (event.charCode) ? event.charCode : ((event.keyCode) ? event.keyCode: ((event.which) ?   evt.which : 0));
    var char = String.fromCharCode(charCode);
    var re = new RegExp("[0-9]", "i");
    console.log($(this));
    console.log(this);
    // var required = this.attributes.getNamedItem("required").value;
    var data_field = Array.from(document.getElementsByClassName("obtained_mark_"+key));
    // console.log(data_field);
    data_field.forEach(checkRequired);

    

    // console.log($(this).length);
    // console.log($(this).data('required'));
    // if (required == true) {
    //   $("#submit_"+key).addClass('d-none');
    // }else if (required == false){
    //   $("#submit_"+key).removeClass('d-none');
    // }
      // console.log(this.attributes.getNamedItem("required").value);
    
    if (re.test(char) || event.which == 65 || charCode == 0) 
    {
      $("#submit_"+key).removeClass('d-none');
      // toastr.success('hello');

      // debugger;
    }
    else if(event.which == 9){
      // toastr.warning('Only number or "a" character is allowed.');
    }
    else{
      // debugger;
      toastr.error('Only number or a character is allowed.');
      $("#submit_"+key).addClass('d-none');
       // return false;
    }
    // console.log(keyCode);
    // console.log(charCode);
    // console.log(char);
    // console.log(re);
    // console.log(event.which);
    console.log('-------------');
    // console.log(re.test(char) || event.which == 65 || event.which == 9);
    // console.log(event.which);
    // debugger;
    // if( e.which == 98 || e.which == 99 || e.which == 110 || e.which == 111 || e.which == 65 || e.which == 66 || e.which == 67 || e.which == 78 || e.which == 79 || e.which == 49 || e.which == 50 || e.which == 51 || e.which == 52 ||  e.which == 53 ||  e.which == 54 || e.which == 55 || e.which == 56|| e.which == 57 || e.which == 58 || e.which == 59 || e.which == 48){
    //  toastr.success('Now Select Shift');
    // $("#submit_"+key).removeClass('d-none');
    // } else {
    //   alert('hello');
    //  // toastr.error('hello');
    //   return false;
    // }
     // toastr.error('hello');
      // ApplyFilter();
  });

  // function ApplyFilter() {
  //     var key = $(event.target).parents('tr').attr('key');
  //     debugger;
  //      toastr.error('hello');

  //     // if( e.which == 98 || e.which == 99 || e.which == 110 || e.which == 111 || e.which == 65 || e.which == 66 || e.which == 67 || e.which == 78 || e.which == 79 || e.which == 49 || e.which == 50 || e.which == 51 || e.which == 52 || e.which == 53){
  //     //  toastr.success('Now Select Shift');
  //     // } else {
  //     //  toastr.error('hello');
  //     //   return false;
  //     // }
  //     // $("#submit_"+key).removeClass('d-none');
  //     // console.log(key);
  // }
  
  function saveMark() {
    Pace.start();
    var key = $(event.target).parents('tr').attr('key'),
      user_id = new Array($('#user_id_'+key).val()),
      student_id = $('#student_id_'+key).val(),
      classexam_id = $('#classexam_id').val(),
      exam_id = $('#exam_id').val(),
          token = $('meta[name="csrf-token"]').attr('content');
    // debugger;


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
    // console.log(classmark_id);
    console.log(obtained_mark);
    // console.log(subject_id);
    // console.log(type_id);
    // console.log(full_mark);

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
<script>
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });
</script>
<script type="text/javascript">
  $(document).ready(function(event){

  // $("body").on("change","#filter_exam", function(event){
    Pace.start();
    var exam_id = $("#filter_exam").val();
       console.log(exam_id);
       
    // debugger;
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
                data_id: class_id,
                exam_id: exam_id,
                shift_id: shift_id
              },
              success: function(response){
                $('#filter_section').html('');
                $('#filter_section').append('<option value="">--Choose Section--</option>');
                $.each( response, function( i, val ) {
                  // debugger;
                  if({{ $request->section_id == null ? '0' : $request->section_id }} == val.id){
                    $('#filter_section').append('<option value='+val.id+' selected>'+val.name+'</option>');
                  }else{
                    $('#filter_section').append('<option value='+val.id+'>'+val.name+'</option>');
                  }
                });
                console.log($("#filter_section").val());
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
    var data_id = $(event.target).val();
    var shift_id = $('#filter_shift').val();
    $('#excClass').val(data_id);
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.getSectionList')}}",
      data:{
        _token: token,
        data_id: data_id,
        shift_id: shift_id
      },
      success: function(response){
        $('#filter_section').html('');
        $('#filter_section').append('<option value="">--Choose Class--</option>');
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
@endpush





