<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Download</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  </head>
  <body>
    <div>
      <p> {{Auth::user()->getSchool->school_name}}</p>
      <p>{{ Auth::user()->getSchool->address.", Phone: ".Auth::user()->getSchool->phone_no}}</p>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="bg-dark">
          <tr class="text-center">
            <th>SN</th>
            <th>Teacher</th>
            <th>Status</th>
            <th>Date</th>
            <th>Created By</th>
          </tr>
        </thead>  

        @foreach($attendances_list as $key=>$attendance)             
        <tr class="text-center">
          <td>{{$key+1}}</td>
          <td>{{$attendance->getTeacherUser->name}} {{$attendance->getTeacherUser->middle_name}} {{$attendance->getTeacherUser->last_name}}</td>
           @if ($attendance->getTeacherAttendance)
           <td>
               {{$attendance->getTeacherAttendance->status == '1' ? 'P' : 'A'}}
           </td>
           <td>{{$attendance->getTeacherAttendance->date}}</td>
           <td>{{$attendance->getUser->name}}</td>
           @else
           <td>-</td>
           <td>-</td>
           @endif
        </tr>
        @endforeach
      </table>
    </div>
  </body>
</html>