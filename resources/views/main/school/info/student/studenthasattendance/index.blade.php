@extends('main.school.include.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/print.main.css">
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css">
@endpush
@section('school-content')
<section class="content">
  <div class="card">
      <div class="card-body">
        <div class="row pb-2">
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
          <div class="col">
            <input type="text" class="form-control ndp-nepali-calendar" id="filter_date" name="filter_date" autocomplete="off" value="{{ old('date') }}" placeholder="YYYY-MM-DD">
          </div>
          <div class="col-1">
            <div class="form-group" align="center">
              <button type="button" name="search" id="search" class="btn btn-default">Go</button>
            </div>
          </div>
        </div>
        @csrf
        <div class="w-100" id="replaceTable">
          
        </div>
      </div>
  </div>
</section>
@endsection
@push('javascript')
<script type="text/javascript" src="{{URL::to('/')}}/backend/js/nepali.datepicker.v2.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(event){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD"),
        currentYear = NepaliFunctions.GetCurrentBsYear(),
        currentMonth = NepaliFunctions.GetCurrentBsMonth(),
        number_of_days = NepaliFunctions.GetDaysInBsMonth(currentYear, currentMonth);
    // for show
    $('#filter_date').val(currentDate);
    $('#filter_date').nepaliDatePicker({
      ndpMonth: true,
      disableAfter: currentDate,
      onChange: function(event) {
        var date = $('#filter_date').val();
        var year = event.object.year;
        var month = event.object.month;
        var number_of_days = NepaliFunctions.GetDaysInBsMonth(year, month);
        // debugger;
      }
    });
  });
  
</script>
<script type="text/javascript">
  $("body").on("change","#filter_shift", function(event){
    Pace.start();
    var shift_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('main.getClassList',$school_info->slug)}}",
      data:{
        _token: token,
        shift_id: shift_id
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
    var class_id = $(event.target).val(),
        shift_id = $('#filter_shift').val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('main.getSectionList',$school_info->slug)}}",
      data:{
        _token: token,
        class_id: class_id,
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
<script>
document.addEventListener('keypress', function(event) {
                 if (event.keyCode == 13) {
                     event.preventDefault();
                     $("#search").click();
                 }
             });
$('#search').click(function () {
    var searchParams = {};
    var base_url = "{{route('main.getStudentList',$school_info->slug)}}";

    searchParams['shift_id']=$('#filter_shift').val();
    searchParams['class_id']=$('#filter_class').val();
    searchParams['section_id']=$('#filter_section').val();
    searchParams['date']=$('#filter_date').val();

    var token = $('meta[name="csrf-token"]').attr('content');
    // debugger;
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




