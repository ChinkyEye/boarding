@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
@endpush
@section('content')
<section class="content-header"></section>
<section class="content">
  <div class="card">
    <div class="card-header pb-0">
      <form action="{{route('admin.studenthasmark.ledger.show')}}" method="GET">
        @csrf
        <div class="row">
          <div class="col">
            <select class="form-control filter_shift" name="exam_id" id="filter_exam">
              <option value="">Select Your Exam</option>
              @foreach ($exams as $key => $exem)
              <option value="{{ $exem->id }}" {{ $exam_id == $exem->id ? 'selected' : ''}}> 
                {{$exem->name}}
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
        <button class="btn btn-xs btn-info rounded-0 float-right" onclick="PrintDiv('printTable')">Print me</button>
      <div class="w-100  table-responsive" id="printTable">
        <small>
          <div class="text-center">
            <h4>{{$exams_name}}</h4>
            <h5 class="text-uppercase">Marks Ledger Search results</h5> 
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
              <th class="v-align-middle" width="100">Roll no</th>
              <th class="v-align-middle text-left" width="200">Student Name</th>
              @foreach($classmarks as $k=>$classmark)   
              <th width="150" class="v-align-middle">
                  <small>
                    Cr. {{$classmark->getSubject->credit_hour}} hr
                  </small><br>
                  {{$classmark->type_id == 1 ? 'Th.' : 'P.' }} {{$classmark->getSubject->name}} {{$classmark->getSubject->compulsory_optional == 2 ? '(Opt)' : '' }} <br> 
                  <small>
                    ({{$classmark->pass_mark}}/{{$classmark->full_mark}})
                  </small>
                </th>          
              @endforeach
              <th class="v-align-middle" width="200">Total Mark</th>
              <th class="v-align-middle" width="10">Division</th>
              <th class="v-align-middle" width="10">Percentage</th>
              <th class="v-align-middle" width="10">Grade</th>
              {{-- <th class="v-align-middle" width="10">Position</th> --}}
              <th class="v-align-middle print-0" width="150">Print</th>
            </tr>
          </thead>
          @foreach($datas as $key=>$data)
          {{-- {{$data->getStudentMark->count() >=1 ? 'data':'nodata'}} --}}
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
            @endphp
            @if($data->getStudentMark->count() >=1)
            @if ($check->count())
              @foreach ($check_val as $element)
              <td data-id="{{$element->classmark_id}}" id="subject_{{$key}}">
                <input type="text" class="border-0 w-50 bg-transparent text-center obtained_mark" name="obtained_mark[{{$element->classmark_id}}][{{$key}}]" id="obtained_mark_{{$element->classmark_id}}_{{$key}}" value="{{ $element->obtained_mark }}" readonly>
                <input type="hidden" name="subject_id[{{$element->classmark_id}}]" id="subject_id_{{$element->classmark_id}}" value="{{$element->subject_id}}">
                <input type="hidden" name="type_id[{{$element->classmark_id}}][{{$key}}]" id="type_id_{{$element->classmark_id}}_{{$key}}" value="{{$element->type_id}}">
                <input type="hidden" name="full_mark[{{$element->classmark_id}}][{{$key}}]" id="full_mark_{{$element->classmark_id}}_{{$key}}" value="{{$element->getStudentMarkList->full_mark}}">
                <input type="hidden" name="classmark_id[{{$element->classmark_id}}][{{$key}}]" id="classmark_id_{{$element->classmark_id}}_{{$key}}" value="{{$element->classmark_id}}">
              </td>
              @endforeach
            @else
              {{-- @foreach($classmarks as $index=>$classmark)
                <td data-id="{{$classmark->id}}" id="subject_{{$key}}">
                  <input type="text" name="obtained_mark[{{$classmark->id}}][{{$key}}]" id="obtained_mark_{{$classmark->id}}_{{$key}}" value="" class="border-0 w-50 bg-transparent text-center obtained_mark" readonly>
                  <input type="hidden" name="subject_id[{{$classmark->id}}]" id="subject_id_{{$classmark->id}}" value="{{$classmark->getSubject->id}}">
                  <input type="hidden" name="type_id[{{$classmark->id}}][{{$key}}]" id="type_id_{{$classmark->id}}_{{$key}}" value="{{$classmark->type_id}}">
                  <input type="hidden" name="full_mark[{{$classmark->id}}][{{$key}}]" id="full_mark_{{$classmark->id}}_{{$key}}" value="{{$classmark->full_mark}}">
                  <input type="hidden" name="classmark_id[{{$classmark->id}}][{{$key}}]" id="classmark_id_{{$classmark->id}}_{{$key}}" value="{{$classmark->id}}">
                </td>
              @endforeach --}}
            @endif
            <td>
              <span name="my_row" key="{{$key}}"></span>
              <input type="text" name="total[{{$key}}]" id="total_{{$key}}" class="border-0 w-50 bg-transparent text-center" readonly>
            </td>
            <td>
              <input type="text" name="division[{{$key}}]" id="division_{{$key}}" class="border-0 w-50 bg-transparent text-center" readonly>
            </td>
            <td>
              <input type="text" name="percentage[{{$key}}]" id="percentage_{{$key}}" class="border-0 w-50 bg-transparent text-center" readonly>
              }
            </td>
            <td>
              <input type="text" name="grade[{{$key}}]" id="grade_{{$key}}" class="border-0 w-50 bg-transparent text-center" readonly>
            </td>
            {{-- <td>
              <input type="text" name="position[{{$key}}]" id="position_{{$key}}" class="border-0 w-50 bg-transparent text-center" readonly>
            </td> --}}
            <td class="print-0">
              {{-- <a href="{{ url('/prnpriview') }}" class="btnprn btn">Print Preview</a></center> --}}
              <a target="_blank" href="{{ route('admin.studenthasmark.getMarkPrint',[$data->slug,$exam]) }}" class="btn btn-xs btn-outline-info btnprn" data-toggle="tooltip" data-placement="top" title="Print Mark"><i class="fas fa-print"></i></a>
              <a target="_blank" href="{{ route('admin.studenthasmark.getGradePrint',[$data->slug,$exam]) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Print Grade"><i class="fas fa-print"></i></a>
              <a target="_blank" href="{{ route('admin.studenthasmark.getBothPrint',[$data->slug,$exam]) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Print Marks & Grade"><i class="fas fa-print"></i></a>

             {{--  <button type="button" ids="print_mark" data-id="" onclick="printResult()" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Print Marks & Grade"><i class="fas fa-print"></i></button>
              <button type="button" id="print_grade" onclick="printResult()" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Print Marks & Grade"><i class="fas fa-print"></i></button>
              <button type="button" id="print_both" onclick="printResult()" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Print Marks & Grade"><i class="fas fa-print"></i></button> --}}
            </td>
          @else
            <td colspan="{{$k+6}}">Marks not updated</td>
          @endif           
          </tr>
          
          {{-- dh{{$data->getStudentMark->count()}} --}}
          
          @endforeach
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')
{{-- web --}}
<script>
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });
</script>
{{-- main --}}
<script type="text/javascript">
  // ------------------------ //
  $("input[type='text']").on('change keyup paste', function (e) {
    var key = $(event.target).parents('tr').attr('key');
    ApplyFilter(key);
  });

  // ------------------------ //
  function ApplyFilter(key) {
       // toastr.error('hello');

      $("#submit_"+key).removeClass('d-none');
      // console.log(key);
  }

  // ------------------------ //
  $(document).ready(function() {
    var rows = document.getElementsByName("my_row");
    // console.log(rows);
    rows.forEach(getMark);
  });

  function getMark(e) {
    Pace.start();
    // console.log(e.attributess);
    // console.log(e.attributes.getNamedItem("key").value);
    var key = e.attributes.getNamedItem("key").value,
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
      obtain_mark_item = 0,
      subject_id = new Array(),
      type_id = new Array(),
      classmark = new Array(),
      full_mark_total = 0,
      percentage = 0,
      full_mark = new Array();
    classmark_id.forEach(classMark);

    var obtain_total = 0;
    function classMark(data_id) {
      var obtain = $('#obtained_mark_'+data_id+'_'+key).val();
      obtained_mark.push(new Array(obtain));


      var subject = $('#subject_id_'+data_id).val();
      subject_id.push(subject);

      var type = $('#type_id_'+data_id+'_'+key).val();
      type_id.push(new Array(type));

      var full_m = $('#full_mark_'+data_id+'_'+key).val();
      full_mark.push(new Array(full_m));

      // sum
      obtain_mark_item = obtain_mark_item + parseFloat(obtain);
      percentage += (parseFloat(obtain) / parseFloat(full_m)) * 100;
      // debugger;
      full_mark_total = full_mark_total + parseFloat(full_m);

      classmark.push(new Array(data_id));
    }

    // total
    $("#total_"+key).val(obtain_mark_item);
    // percentage
    percentage = percentage.toFixed(2)/obtained_mark.length;
    $("#percentage_"+key).val(percentage);

    // division
    if(percentage > 75){
      $("#division_"+key).val('Distinction');
    }
    // 60<>75
    if(percentage < 75 && percentage >= 60){
      $("#division_"+key).val('1st');
    }
    // 50<>60
    if(percentage < 60 && percentage >= 50){
      $("#division_"+key).val('2nd');
    }
    // 40<>50
    if(percentage < 50 && percentage >= 40){
      $("#division_"+key).val('3rd');
    }
    // 0<>40
    if(percentage < 40 && percentage >= 0){
      $("#division_"+key).val('F');
    }

    // grade
    @foreach ($grades as $value_grade) 
      if ({{$value_grade->max}} >= percentage) {
        if ({{$value_grade->min}} <= percentage) {
          var data_value = "{{$value_grade->name}}";
          var grade_point = "{{$value_grade->grade_point}}";
        }
      }
    @endforeach
      $("#grade_"+key).val(data_value);
      $("#position_"+key).val(key+1);
      // console.log(obtain_mark_item);
      // console.log(full_mark_total);
      // console.log(obtained_mark.length);
      // console.log(percentage.toFixed(2));
      // console.log(percentage.toFixed(2)/obtained_mark.length);
      // console.log((obtain_mark_item*100)/full_mark_total);
    // 70.08%
    Pace.stop();
  }

</script>
{{-- search --}}

<script type="text/javascript">
  $(document).ready(function(event){

  // $("body").on("change","#filter_exam", function(event){
    Pace.start();
    var exam_id = $("#filter_exam").val();
       // console.log(exam_id);
       
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
        // console.log($("#filter_shift").val());
       
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
           // console.log($("#filter_class").val());

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
                // console.log($("#filter_section").val());
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
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/jquery.printPage.js"></script>
{{-- <script type="text/javascript">
$(document).ready(function(){
$('.btnprn').printPage();
});
</script> --}}

{{-- <script type="text/javascript">
  function printResult(){
    var data = $(event.target);
    console.log(data);
  }
</script> --}}
<script type="text/javascript">
  function PrintDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}
</script>
@endpush





