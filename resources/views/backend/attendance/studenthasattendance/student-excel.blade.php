<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Atttendance of Student Download</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  </head>
  <body>
    <div>
      <p> {{Auth::user()->getSchool->school_name}}</p>
      <p>{{ Auth::user()->getSchool->address.", Phone: ".Auth::user()->getSchool->phone_no}}</p>
      <p>{{$shift->name}} / {{$class->name}} / {{$section->name}} / {{$date}}</p>
    </div>
    <table class="table table-bordered">
    <thead>
      <tr class="table-danger">
        <td>Name</td>
        <td>Status</td>
        <td>Date</td>
        <td>Created By</td>
      </tr>
      </thead>
      <tbody>
        @foreach ($studentattendances as $data)
        <tr>
            <td>{{ $data->getStudentName->name }}{{ $data->getStudentName->middle_name }}{{ $data->getStudentName->last_name }}</td>
            <td>{{ $data->status }}</td>
            <td>{{ $data->date }}</td>
            <td>{{ $data->getUser->name }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>