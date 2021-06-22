@extends('main.school.include.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/dataTables.bootstrap4.css">
<link rel="stylesheet" href="{{URL::to('/')}}/backend/css/nepali.datepicker.v2.2.min.css">
@endpush
@section('school-content')
{{-- <section class="content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6 pl-1">
        <h1 class="text-capitalize">{{ $page }}</h1>
      </div>
    </div>
  </div>
</section> --}}
<section class="content">
  <div class="card">
    <div class="card-body">
      <div class="row pb-2">
        <div class="col">
          <select class="form-control filter_shift" name="shift_id" id="filter_shift">
            {{-- <option value="">Select Your Shift</option> --}}
            @foreach ($shifts as $key => $shift)
            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : ''}}> 
              {{$shift->name}}
            </option>
            @endforeach
          </select>
        </div>
        
        {{-- <div class="col">
          <input type="date" name="date" class="form-control" id="filter_date" placeholder="YY-MM-DD">
        </div> --}}
        <div class="col">
          <input type="text" class="form-control ndp-nepali-calendar" id="filter_date" name="filter_date" autocomplete="off" value="{{ old('date') }}" placeholder="YYYY-MM-DD">
        </div>

        <div class="col-1">
          <div class="form-group" align="center">
            <button type="button" name="search" id="search" class="btn btn-default">Go</button>
            <!-- <button type="button" name="reset" id="reset" class="btn btn-default">Reset</button> -->
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
<script src="{{url('/')}}/backend/js/jquery.dataTables.js"></script>
<script src="{{url('/')}}/backend/js/dataTables.bootstrap4.js"></script>
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
  $('#reset').click(function(){
    var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");
    $('#shift_data').val('');
    $('#class_data').val('');
    $('#section_data').val('');
    $('#filter_date').val(currentDate);
  });
</script>
<script type="text/javascript">
  $("body").on("change","#filter_date", function(event){
    Pace.start();
    var data_id = $(event.target).val(),
        token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      type:"POST",
      dataType:"JSON",
      url:"{{route('main.getClassList',$school_info->slug)}}",
      data:{
        _token: token,
        data_id: data_id
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
<script>
document.addEventListener('keypress', function(event) {
                 if (event.keyCode == 13) {
                     event.preventDefault();
                     $("#search").click();
                 }
             });
$('#search').click(function () {
    var searchParams = {};
    var base_url = "{{route('main.getTeacherList',$school_info->slug)}}";

    searchParams['shift_id']=$('#filter_shift').val();
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




