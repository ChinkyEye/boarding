<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Salary Download</title>
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
            <th>Income</th>
            <th>Grade</th>
            <th>Mahangi Vatta</th>
            <th>Durgam Vatta</th>
            <th>Citizen Investment Deduction</th>
            <th>Loan Deduction</th>
            <th>Cloth Amount</th>
            <th>Remarks</th>
            <th>Month</th>
            <th>Created By</th>
          </tr>
        </thead>              
        @foreach($teachers_list as $key=>$teacher)             
        <tr class="text-center">
          <td>{{$key+1}}</td>
          <td>{{$teacher->getTeacherUser->name}} {{$teacher->getTeacherUser->middle_name}} {{$teacher->getTeacherUser->last_name}}</td>
          @if ($teacher->getTeacherIncome)
            <td>{{$teacher->getTeacherIncome->amount}}</td>
            <td>{{$teacher->getTeacherIncome->grade}}</td>
            <td>{{$teacher->getTeacherIncome->mahangi_vatta}}</td>
            <td>{{$teacher->getTeacherIncome->durgam_vatta}}</td>
            <td>{{$teacher->getTeacherIncome->citizen_investment_deduction}}</td>
            <td>{{$teacher->getTeacherIncome->loan_deduction}}</td>
            <td>{{$teacher->getTeacherIncome->cloth_amount}}</td>
            <td>{{$teacher->getTeacherIncome->remark}}</td>
            @if($teacher->getTeacherIncome->month == 1)
            <td>Baisakh</td>
            @elseif($teacher->getTeacherIncome->month == 2)
            <td>Jestha</td>
            @elseif($teacher->getTeacherIncome->month == 3)
            <td>Asar</td>
            @elseif($teacher->getTeacherIncome->month == 4)
            <td>Shrawan</td>
            @elseif($teacher->getTeacherIncome->month == 5)
            <td>Bhandra</td>
            @elseif($teacher->getTeacherIncome->month == 6)
            <td>Ashoj</td>
            @elseif($teacher->getTeacherIncome->month == 7)
            <td>Kartik</td>
            @elseif($teacher->getTeacherIncome->month == 8)
            <td>Mangsir</td>
            @elseif($teacher->getTeacherIncome->month == 9)
            <td>Poush</td>
            @elseif($teacher->getTeacherIncome->month == 10)
            <td>Magh</td>
            @elseif($teacher->getTeacherIncome->month == 11)
            <td>Falgun</td>
            @elseif($teacher->getTeacherIncome->month == 12)
            <td>Chaitra</td>
            @endif
            <td>{{$teacher->getTeacherIncome->getUser->name}}</td>
          @else
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          <td>-</td>
          @endif
        </tr>
        @endforeach
      </table>
    </div>
  </body>
</html>