<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Download</title>
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
          <tr>
            <th>SN</th>
            <th>Code</th>
            <th>Student</th>
            <th>Father Name</th>
            <th>Mother Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Address</th>
            <th>Info</th>
            <th>Gender</th>
            <th>Register Id</th>
            <th>Register Date</th>
            <th>Created By</th>
          </tr>
        </thead>              
        @foreach($students_list as $key=>$student)             
        <tr>
          <td>{{$key+1}}</td>
          <td>{{$student->student_code}}</td>
          <td><span class="badge badge-info">{{$student->getStudentUser->name}} {{$student->getStudentUser->middle_name}} {{$student->getStudentUser->last_name}}</span></td>
          <td>{{$student->Student_has_parent->father_name}}</td>
          <td>{{$student->Student_has_parent->mother_name}}</td>
          <td>{{$student->phone_no}}</td>
          <td>{{$student->getStudentUser->email}}</td>
          <td>{{$student->Student_has_parent->address}}</td>
          <td>{{$student->getShift->name}}/{{$student->getClass->name}}/{{$student->getSection->name}}</td>
          <td>{{$student->gender}}</td>
          <td>{{$student->register_id}}</td>
          <td>{{$student->register_date}}</td>
          <td>{{$student->getUser->name}} {{$student->getUser->middle_name}} {{$student->getUser->last_name}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </body>
</html>