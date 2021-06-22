<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher Attendance Detail Download</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  </head>
  <body>
    <div class="table-responsive">
      <div>
        <p>{{Auth::user()->getSchool->school_name}}</p>
        <p>{{ Auth::user()->getSchool->address.", Phone: ".Auth::user()->getSchool->phone_no}}</p>
        @if($current_month == 1)
        <h1>Month : Baisakh </h1>
        @elseif($current_month == 2)
        <h1>Month : Jestha </h1>
        @elseif($current_month == 3)
        <h1>Month : Asar </h1>
        @elseif($current_month == 4)
        <h1>Month : Shrawan </h1>
        @elseif($current_month == 5)
        <h1>Month : Bhadra </h1>
        @elseif($current_month == 6)
        <h1>Month : Ashoj </h1>
        @elseif($current_month == 7)
        <h1>Month : Kartik </h1>
        @elseif($current_month == 8)
        <h1>Month : Mangsir </h1>
        @elseif($current_month == 9)
        <h1>Month : Poush </h1>
        @elseif($current_month == 10)
        <h1>Month : Magh </h1>
        @elseif($current_month == 11)
        <h1>Month : Falgun </h1>
        @elseif($current_month == 12)
        <h1>Month : Chaitra </h1>
        @endif
        <p>Teacher Name : {{$teacher_info->getTeacherUser->name}} {{$teacher_info->getTeacherUser->middle_name}} {{$teacher_info->getTeacherUser->last_name}}</p>
      </div>
        
      <table class="table table-bordered table-hover">
        <thead class="bg-dark">
          <tr class="text-center">
            <th>Day</th>
            <th>Status</th>
            <th>Created By</th>
          </tr>
        </thead>  
        @for ($i = 1; $i <= $current_days; $i++)
        <tr>
          @php
            $loop_date = $current_year.'-'.sprintf("%02d", $current_month).'-'.sprintf("%02d", $i);
            $query[$i] = $attendances;
            $main[$i] = $query[$i]->where('date','=',$loop_date);
          @endphp

          <td>{{$loop_date}}</td>
          @forelse ($main[$i] as $attendance)
            <td>
              {{$attendance->status == '1' ? 'P' : 'A'}}
            </td>
            <td>
              {{$attendance->getUser->name}}
            </td>
          @empty
            <td>-</td>
            <td>-</td>
          @endforelse
        </tr>
        @endfor            
      </table>
    </div>
  </body>
</html>