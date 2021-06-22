@extends('main.school.include.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/loader.main.css">
@endpush
@section('school-content')
<section class="content mx-2">
  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover w-100 m-0" id="teacher_ajax">
        <div class="row pb-3">
          <div class="col">
            <select class="form-control filter_shift" name="shift_id" id="filter_shift">
              <option value="">Select Your Shift</option>
              @foreach ($shifts as $key => $shift)
              <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
                {{$shift->name}}
              </option>
              @endforeach
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
            <button type="button" name="reset" id="reset" class="btn btn-block btn-default">Reset</button>
          </div>
        </div>
        <thead class="bg-dark text-center">
          <th width="10">SN</th>
          <th class="text-left">First Name</th>
          <th class="text-left">Code</th>
          <th class="text-left">Email</th>
          <th width="150">Created By</th>
          <th width="10">Status</th>
          <th width="100">Action</th>
        </thead>    

      </table>
    </div>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{url('/')}}/backend/js/jquery.dataTables.js"></script>
<script src="{{url('/')}}/backend/js/dataTables.bootstrap4.js"></script>
<script type="text/javascript">
  $("body").on("change","#filter_shift", function(event){
    Pace.start();
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('main.getClassList',$school_info->slug)}}",
      data:{
        _token: token,
        shift_id: data_id
      },
      success: function(response){
        $('#filter_class').html('');
        $('#filter_class').append('<option value="">--Choose Class--</option>');
        $.each( response, function( i, val ) {
          $('#filter_class').append('<option value='+val.get_class.id+'>'+val.get_class.name+'</option>');
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
    var data_id = $(event.target).val(),
        shift_id = $('#filter_shift').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('main.getSectionList',$school_info->slug)}}",
      data:{
        _token: token,
        class_id: data_id,
        shift_id: shift_id,
      },
      success: function(response){
        $('#filter_section').html('');
        $('#filter_section').append('<option value="">--Choose Section--</option>');
        $.each( response, function( i, val ) {
          $('#filter_section').append('<option value='+val.get_section.id+'>'+val.get_section.name+'</option>');
        });
      },
      error: function(event){
        alertify.alert("Sorry");
      }
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
    fill_datatable();
    function fill_datatable(filter_shift = '', filter_class = '', filter_section = '')
    {
      var dataTable = $('#teacher_ajax').DataTable({
        "processing": true,
        "language": {
          processing: '<span class="d-flex justify-content-center flex-row loader-with-text"><div class="book"><div class="inner"><div class="left"></div><div class="middle"></div><div class="right"></div></div><ul>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li>  <li></li></ul></div> <span class="h6 loader-text">Techware School System processing...</span></span> ',
          searchPlaceholder: "Search name or code or phone no"
        },
        "serverSide": true,
        "ajax":{
          "url": "{{route('main.getAllTeacher',$school_info->slug)}}",
          "dataType": "json",
          "type": "POST",
          "data":{ 
            _token: $('meta[name="csrf-token"]').attr('content'),
            data:{
              filter_shift: filter_shift, 
              filter_class: filter_class, 
              filter_section: filter_section
            }
          }
        },
        "columns": [
        { "data": "id" },
        { "data": "f_name" },
        { "data": "teacher_code" },
        { "data": "email" },
        { "data": "created_by" },
        { "data": "status", orderable: false, searchable: false },
        { "data": "action", orderable: false, searchable: false},
        ],
        "order": [
        [ 0 ,"desc" ]
        ]

      });
    }

    $('#filter_shift, #filter_class, #filter_section').change(function(){
        var filter_shift = $('#filter_shift').val();
        var filter_class = $('#filter_class').val();
        var filter_section = $('#filter_section').val();
        if(filter_shift != '')
        {
          $('#teacher_ajax').DataTable().destroy();
          fill_datatable(filter_shift, filter_class, filter_section);
        }
        else
        {
          alert('Please field !!!');
        }
    });

    $('#reset').click(function(){
        $('#filter_shift').val('');
        $('#filter_class').val('');
        $('#filter_section').val('');
        $('#teacher_ajax').DataTable().destroy();
        fill_datatable();
    });
  });
</script>
@endpush




