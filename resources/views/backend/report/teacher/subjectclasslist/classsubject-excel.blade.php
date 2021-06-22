<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subject Class List Download</title>
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
            <th>Name</th>
            <th>Class / Subject</th>
            <th>Created By</th>
          </tr>
        </thead>              
        @foreach($teacherhasperiods as $key=>$teacherhasperiod) 
        <tr class="text-center">
          <td>{{$key+1}}</td>
          <td>{{$teacherhasperiod->getTeacherUser->name}} {{$teacherhasperiod->getTeacherUser->middle_name}} {{$teacherhasperiod->getTeacherUser->last_name}}</td>
          @if($teacherhasperiod->getTeacherPeriod()->count())
            @foreach($teacherhasperiod->getTeacherPeriod()->get() as $index=>$teacherclass)
              @if ($index != 0)
              <tr class="text-center">
                <td colspan="2"></td>
                <td>
                  <span class="badge badge-info">
                    {{$teacherclass->getClass->name}} 
                    <span class="badge badge-warning">{{$teacherclass->getTeacherSubject()->count()}}</span>
                  </span>
                  @foreach ($teacherclass->getTeacherSubject()->get() as $element)
                  <span class="badge badge-success">
                      {{$element->getSubject->compulsory_optional == '2' ? 'Opt. ' : ''}} 
                      {{$element->getSubject->name}} 
                      {{$element->getSubject->theory_practical == '1' ? ' (Th)' : ' (Pr)'}}
                  </span>
                  @endforeach
                </td>
                <td>{{$teacherclass->getUser->name}} {{$teacherclass->getUser->middle_name}} {{$teacherclass->getUser->last_name}}</td>
              </tr>
              @else
                <td>
                  <span class="badge badge-info">
                    {{$teacherclass->getClass->name}} 
                    <span class="badge badge-warning">{{$teacherclass->getTeacherSubject()->count()}}</span>
                  </span>
                  @foreach ($teacherclass->getTeacherSubject()->get() as $element)
                  <span class="badge badge-success">
                      {{$element->getSubject->compulsory_optional == '2' ? 'Opt. ' : ''}} 
                      {{$element->getSubject->name}} 
                      {{$element->getSubject->theory_practical == '1' ? ' (Th)' : ' (Pr)'}}
                  </span>
                  @endforeach
                </td>
                <td>{{$teacherclass->getUser->name}} {{$teacherclass->getUser->middle_name}} {{$teacherclass->getUser->last_name}}</td>
              @endif
            @endforeach
          @else
            <td>-</td>
            <td>-</td>
          </tr>
          @endif
        @endforeach
      </table>
    </div>
  </body>
</html>