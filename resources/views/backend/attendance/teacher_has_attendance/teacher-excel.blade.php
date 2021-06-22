<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Teacher Attendance Download</title>
  </head>
  <body>
    <div>
      <p> {{Auth::user()->getSchool->school_name}}</p>
      <p>{{ Auth::user()->getSchool->address.", Phone: ".Auth::user()->getSchool->phone_no}}</p>
    </div>
    <table border="1">
      <thead>
        <tr>
          <th>Name</th>
          <th>Status</th>
          <th>Modified By</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($teacherattendances as $data)
        <tr>
            <td>{{ $data->getTeacherName->name }} {{ $data->getTeacherName->middle_name }} {{ $data->getTeacherName->last_name }}</td>
            <td>{{ $data->status }}</td>
            <td>{{ $data->getUser->name }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>