@extends('backend.main.app')
@push('style')
<link rel="stylesheet" href="{{URL::to('/')}}/backend/plugins/calender/main.css">
@endpush
@section('content')
<section class="content-header"></section>
<section class="content">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('admin.event.create')}}" class="btn btn-xs btn-info text-capitalize" data-toggle="tooltip" data-placement="top" title="Add Event">Add Event </a>
    </div>

    <div id='calendar2'></div>
  </div>
</section>
@endsection
@push('javascript')
<script src="{{url('/')}}/backend/plugins/calender/main.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar2');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      initialDate: '{{date("Y-m-d")}}',
      navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      editable: true,
      selectable: true,
      events: [
        @for ($i = 0; $i < count($mydata); $i++)
        {
          title: '{{ $mydata[$i]['title'] }}',
          start: '{{ $mydata[$i]['eng_start_date'] }}',
          @if($mydata[$i]['eng_end_date'])
          end : '{{$mydata[$i]['eng_end_date']}}',
          @endif
          color: '{{$mydata[$i]['color']}}',
          // description: 'test',
          // url: 'http://google.com/',
        },
        @endfor
      ]
    });
    calendar.render();
  });
</script>

@endpush