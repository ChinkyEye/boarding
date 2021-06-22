@extends('backend.main.app')
@push('style')
@endpush
@section('content')
<section class="content-header"></section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <form action="{{route('admin.studenthasmark.getmark')}}" method="GET">
      	@csrf
        <div class="row">
          <div class="col-md">
            <select class="form-control filter_shift" name="exam_id" id="filter_exam">
              <option value="">Select Your Exam</option>
              @foreach ($exams as $key => $exem)
              <option value="{{ $exem->id }}" {{ $exam_id == $exem->id ? 'selected' : ''}}> 
                {{$exem->name}}
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
              <button type="submit" class="btn btn-info">Search</button>
              <button type="button" name="reset" id="reset" class="btn btn-warning">Reset</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-hover position-relative m-0">
        <thead class="bg-dark text-center th-sticky-top">
          <tr>
            <th width="100">Roll no</th>
            <th class="text-left">Student Name</th>
            <th class="text-left">Student Info</th>
            <th width="20">Email</th>
            <th width="20">Marksheet</th>
            <th width="20">Observation</th>
            <th width="100">Action</th>
            <th width="100">Publish</th>
          </tr>
        </thead>
            @php $pub = ""; @endphp
        @foreach($datas as $key=>$data)    
        @foreach($data->getMarkPubUnpub()->get() as $markpu)
        <form action="{{route('admin.studentmarkpublish.active')}}" method="POST">
          @csrf()
          <input type="hidden" name="mid[]" value="{{$markpu->id}}">
        </form>
        @endforeach            
        <tr class="text-center">
          <td>{{$data->roll_no}}</td>
          <td class="text-left">{{$data->getStudentUser->name}} {{$data->getStudentUser->middle_name}} {{$data->getStudentUser->last_name}}({{$data->student_code}} )</td>
          <td class="text-left">{{$data->getShift->name}} | {{$data->getClass->name}} | {{$data->getSection->name}}</td>
          <td>{{$data->getStudentUser->email}}</td>
          <td class="text-center">
            @if($data->getStudentUser->getStudentMarkStatus()->where('exam_id',$exam_id)->count())
            <i class="fa fa-check text-success"></i>
            @else
            <i class="fa fa-times text-danger"></i>
            @endif
          </td>
          <td class="text-center">
            @if($data->getStudentUser->getStudentMarkStatus()->where('exam_id',$exam_id)->count())
              @if($data->getStudentUser->getStudentMarkStatus()->where('exam_id',$exam_id)->where('observation_id','0')->count())
                <a target="_blank" href="{{ route('admin.observation.mark',[$data->slug,$exam]) }}" data-toggle="tooltip" data-placement="top" title="Show Observation Detail"><i class="fa fa-times text-danger"></i></a>
              @else
                <a target="_blank" href="{{ route('admin.observation.mark',[$data->slug,$exam]) }}" data-toggle="tooltip" data-placement="top" title="Show Observation Detail"><i class="fa fa-check text-success"></i></a>
              @endif
            @else
              <i class="fa fa-times text-danger"></i>
            @endif
          </td>
          <td class="text-center">
            <a target="_blank" href="{{ route('admin.studenthasmark',[$data->slug,$exam]) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="Add Marksheet"><i class="fas fa-plus"></i></a>
            <a target="_blank" href="{{ route('admin.studenthasmarksheet',[$data->slug,$exam]) }}" class="btn btn-xs btn-outline-info" data-toggle="tooltip" data-placement="top" title="View Marksheet"><i class="fas fa-print"></i></a>
         
          </td>
          <td>
            @if($data->getStudentUser->getStudentMarkStatus()->where('exam_id',$exam_id)->count())
            @if($data->getStudentUser->getStudentMarkStatus()->where('exam_id',$exam_id)->value('is_published') == '1')
            <a href="{{route('admin.singlestudentmarkpublish.active',[$data->getStudentUser->getStudentMarkStatus()->where('exam_id',$exam_id)->value('invoicemark_id')])}}" class="btn btn-xs btn-outline-success publishalert" data-toggle="tooltip" data-placement="top" title="Click to Unpublish">
             <i class="fa fa-check"></i> 
           </a>
           @else
           <a href="{{route('admin.singlestudentmarkpublish.active',[$data->getStudentUser->getStudentMarkStatus()->where('exam_id',$exam_id)->value('invoicemark_id')])}}" class="btn btn-xs btn-outline-danger publishalert" data-toggle="tooltip" data-placement="top" title="Click to Publish">
             <i class="fa fa-times"></i>
           </a>
           @endif
           @endif
         </td>
        </tr>
        @endforeach
      </table>
      <input type="submit" value="publish all" id="publishall" class="btn btn-xs btn-outline-success" onClick="document.location.reload(true)">
      <input type="submit" value="Unpublish all" id="unpublishall" class="btn btn-xs btn-outline-danger" onClick="document.location.reload(true)">
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript">
  $("body").on("click","#publishall", function(event){
    Pace.start();
    // debugger;
    event.preventDefault();
    //sabai value ko array ho
        var data_id = new Array();
        $('input[name="mid[]"]').each(function () {
          data_id.push(this.value);
        });
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.studentmarkpublish.active')}}",
      data:{
        _token: token,
        mid: data_id,
      },
      success: function(response){
          toastr.success(response.msg);
      },
      error: function(event){
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
<script type="text/javascript">
  $("body").on("click","#unpublishall", function(event){
    Pace.start();
    // debugger;
    event.preventDefault();
    //sabai value ko array ho
        var data_id = new Array();
        $('input[name="mid[]"]').each(function () {
          data_id.push(this.value);
        });
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('admin.studentmarkunpublish.active')}}",
      data:{
        _token: token,
        mid: data_id,
      },
      success: function(response){
          toastr.error(response.msg);
      },
      error: function(event){
        alert("Sorry");
      }
    });
    Pace.stop();
  });
</script>
{{-- <script>
  $('.publishalert').on('click', function (e) {
    event.preventDefault();
    const url = $(this).attr('href');
    // debugger;
    var token = $('meta[name="csrf-token"]').attr('content');
    swal({
      title: 'Are you sure?',
      text: 'The status of the marksheet will be changed!',
      icon: 'warning',
      buttons: ["Cancel", "Yes!"],
      dangerMode: true,
      closeOnClickOutside: false,
    }).then(function(value) {
      if(value == true){
        // debugger;
        $.ajax({
          url: url,
          type: "GET",
          data: {
            _token: token,
            '_method': 'GET',
          },
          success: function (data) {
            swal({
              title: "Success!",
              type: "success",
              text: data.message+"\n Click OK",
              icon: "success",
              showConfirmButton: false,
            }).then(location.reload(true));
            
          },
          error: function (data) {
            swal({
              title: 'Opps...',
              text: data.message+"\n Please refresh your page",
              type: 'error',
              timer: '1500'
            });
          }
        });
      }else{
        swal({
          title: 'Cancel',
          text: "Data is safe.",
          icon: "success",
          type: 'info',
          timer: '1500'
        });
      }
    });
  });
</script> --}}
<script>
  $(document).ready(function() {
    // sidebar collapse
    $("#main-body").addClass('sidebar-collapse');
  });
</script>
<script type="text/javascript">
  $(document).ready(function(event){
    Pace.start();
    var exam_id = $("#filter_exam").val(),
        token = $('meta[name="csrf-token"]').attr('content');
       
    $('#excExam').val(exam_id);
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
      },
      error: function(event){
        alert("Sorry");
        Pace.stop();
      }
    });
  });
  $('#reset').click(function(){
    $('#filter_exam').val('');
    $('#filter_shift').val('');
    $('#filter_class').val('');
    $('#filter_section').val('');
  });
</script>
@endpush