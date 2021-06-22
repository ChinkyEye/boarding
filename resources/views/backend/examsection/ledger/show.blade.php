@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
@endpush
@section('content')
<section class="content-header"></section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <form action="{{route('admin.studenthasmark.ledger.show')}}" method="GET">
        @csrf
        <div class="row">
          <div class="col-md">
            <select class="form-control filter_shift" name="exam_id" id="filter_exam">
              <option value="">Select Your Exam</option>
              @foreach ($exams as $key => $exem_list)
              <option value="{{ $exem_list->id }}" {{ $exam_id == $exem_list->id ? 'selected' : ''}}> 
                {{$exem_list->name}}
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
        <!-- <input type="submit" name="submit" value="Export" class="btn btn-sm btn-info"> -->
      </form>
    </div>
    <div class="card-body">
        <button class="btn btn-xs btn-info rounded-0 float-right" onclick="PrintDiv('printTable')">Print me</button>
      <div class="w-100  table-responsive" id="printTable">
        <small>
          <div class="text-center mb-3">
            <h4 class="mb-0">{{$exams_name->name}}</h4>
            <h5 class="mb-0 text-uppercase">Marks Ledger Search results</h5> 
            @if($shifts_name)  <b>Shift:</b> {{$shifts_name->name}} | @endif 
            <b> Class:</b>{{$classes_name->name}} 
            @if($sections_name) | <b>Section:</b> {{$sections_name->name}}  @endif 
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
              {{$data->getStudentUser->name}} {{$data->getStudentUser->middle_name}} {{$data->getStudentUser->last_name}}({{$data->student_code}})

              <input type="hidden" name="user_id[]" value="{{$data->user_id}}" id="user_id_{{$key}}">
              <input type="hidden" name="student_id[]" value="{{$data->id}}" id="student_id_{{$key}}">
            </td>
            @php
              $check = $data->getStudentMark()->where('classexam_id',$classexam_id);
              $check_val = $data->getStudentMark()->where('classexam_id',$classexam_id)->get();
            @endphp
            @if($data->getStudentMark->count() >=1)
            @if ($check->count())
              @foreach ($check_val as $element)
              <td>
                <input type="text" class="border-0 w-50 bg-transparent text-center obtained_mark" name="obtained_mark[{{$element->classmark_id}}][{{$key}}]" id="obtained_mark_{{$element->classmark_id}}_{{$key}}" value="{{ $element->obtained_mark == 'a' ? 'Absent' : $element->obtained_mark}}" readonly>
              </td>
              @endforeach
            @else
              <td colspan="{{$classmarks->count()}}">Marks not updated. <a href="{{ route('admin.studenthasmark',[$data->slug,$exam->slug]) }}" target="_blank">Click to update</a> </td>
            @endif
            <?php
            $total = 0;
            ?>
            @foreach ($check_val as $element_total)
            <?php
            if ($element_total->obtained_mark != 'a' && $element_total->obtained_mark != '-'){
              $total += $element_total->obtained_mark;
            }
            ?>
            @endforeach
            <td>
              {{$total}}

            </td>
            <td>
             <?php $percentage = round((($total / $classmarks->sum('full_mark')) * 100) , 2 ); ?>
              @if($percentage > 75)
                Distinction
              @endif
              @if($percentage < 75 && $percentage >= 60)
                1st
              @endif
              @if($percentage < 60 && $percentage >= 50)
                2nd
              @endif
              @if($percentage < 50 && $percentage >= 40)
                3rd
              @endif
              @if($percentage < 40 && $percentage >= 0)
                F
              @endif
            </td>
            <td>
              {{ $percentage }} %
            </td>
            <td>
              @foreach ($grades as $value_grade) 
                @if ($value_grade->max >= $percentage)
                  @if ($value_grade->min <= $percentage)
                    {{$value_grade->name}}
                    @php $grade_point = $value_grade->grade_point @endphp
                  @endif
                @endif
              @endforeach
            </td>
            {{-- <td>
              <input type="text" name="position[{{$key}}]" id="position_{{$key}}" class="border-0 w-50 bg-transparent text-center" readonly>
            </td> --}}
            <td class="print-0">
              {{-- <a href="{{ url('/prnpriview') }}" class="btnprn btn">Print Preview</a></center> --}}
              <i class="fas fa-print my-auto"></i>
              <a target="_blank" href="{{ route('admin.studenthasmark.getMarkPrint',[$data->slug,$exam->slug]) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Print Mark">M</a>
              <a target="_blank" href="{{ route('admin.studenthasmark.getGradePrint',[$data->slug,$exam->slug]) }}" class="btn btn-xs btn-outline-success" data-toggle="tooltip" data-placement="top" title="Print Grade">G</a>
              <a target="_blank" href="{{ route('admin.studenthasmark.getBothPrint',[$data->slug,$exam->slug]) }}" class="btn btn-xs btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Print Marks & Grade">MG</a>
            </td>
          @else
            <td colspan="{{$k+6}}">Marks not updated. <a href="{{ route('admin.studenthasmark',[$data->slug,$exam->slug]) }}" target="_blank">Click to update</a></td>
          @endif           
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script>
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });
  $('#reset').click(function(){
    $('#filter_exam').val('');
    $('#filter_shift').val('');
    $('#filter_class').val('');
    $('#filter_section').val('');
    $('#submit').prop('disabled', true);
  });
</script>
{{-- search --}}
<script type="text/javascript">
  $(document).ready(function(event){
    Pace.start();
    var exam_id = $("#filter_exam").val();
       
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
        $('#submit').prop('disabled', true);
        $.each( response, function( i, val ) {
          if({{ $request->shift_id == null ? '0' : $request->shift_id }} == val.id){
            $('#filter_shift').append('<option value='+val.id+' selected>'+val.name+'</option>');
          }else{
            $('#filter_shift').append('<option value='+val.id+'>'+val.name+'</option>');
          }
        });
        var shift_id = $("#filter_shift").val();
       
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
            $('#submit').prop('disabled', true);
            $.each( response, function( i, val ) {
              if({{ $request->class_id == null ? '0' : $request->class_id }} == val.id){
                $('#filter_class').append('<option value='+val.id+' selected>'+val.name+'</option>');
              }else{
                $('#filter_class').append('<option value='+val.id+'>'+val.name+'</option>');

              }
            });

           var class_id = $("#filter_class").val();

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
                $('#submit').prop('disabled', false);
                $.each( response, function( i, val ) {
                  
                  if({{ $request->section_id == null ? '0' : $request->section_id }} == val.id){
                    $('#filter_section').append('<option value='+val.get_section.id+' selected>'+val.get_section.name+'</option>');
                  }else{
                    $('#filter_section').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');
                  }
                });
              },
              error: function(event){
                toastr.error("Sorry");
              }
            });
          },
          error: function(event){
            toastr.error("Sorry");
          }
        });
      },
      error: function(event){
        toastr.error("Sorry");
      }
    });
    Pace.stop();
  });

  $("body").on("change","#filter_exam", function(event){
    Pace.start();
    var data_id = $('#filter_exam').val();
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