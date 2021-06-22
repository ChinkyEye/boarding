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
          <tr class="text-center v-align-middle">
            <th>SN</th>
            <th>Code</th>
            <th>Teacher</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Address</th>
            <th>Type</th>
            <th>Gender</th>
            <th>Marital Status</th>
            <th>Education</th>
            <th>Teacher Designation</th>
            <th>Designation</th>
            <th>Training</th>
            <th>Religion</th>
            <th>Joining Date</th>
            <th>Created By</th>
          </tr>
        </thead>              
        @foreach($teachers_list as $key=>$teacher)             
        <tr class="text-center">
          <td>{{$key+1}}</td>
          <td>{{$teacher->teacher_code}}</td>
          <td>{{$teacher->getTeacherUser->name}} {{$teacher->getTeacherUser->middle_name}} {{$teacher->getTeacherUser->last_name}}</td>
          <td>{{$teacher->phone}}</td>
          <td>{{$teacher->getTeacherUser->email}}</td>
          <td>{{$teacher->address}}</td>
          <td>{{$teacher->uppertype}}</td>
          <td>{{$teacher->gender}}</td>
          <td>{{$teacher->marital_status}}</td>
          <td>{{$teacher->qualification}}</td>
          <td>{{$teacher->t_designation}}</td>
          <td>{{$teacher->designation}}</td>
          <td>{{$teacher->training}}</td>
          <td>{{$teacher->religion}}</td>
          <td>{{$teacher->j_date}}</td>
          <td>{{$teacher->getUser->name}} {{$teacher->getUser->middle_name}} {{$teacher->getUser->last_name}}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </body>
</html>