<nav>
  <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
    <a class="nav-item nav-link {{$page == 'profile' ? 'active' : ''}}" href="{{route('admin.report.teacher.profile')}}">Profile</a>
    <a class="nav-item nav-link {{$page == 'subjectclasslist' ? 'active' : ''}}" href="{{route('admin.report.teacher.SubjectClassList')}}">Class & Subject</a>
    <a class="nav-item nav-link {{$page == 'attendance' ? 'active' : ''}}"  href="{{route('admin.report.teacher.attendance')}}" >Attendance</a>
    <a class="nav-item nav-link {{$page == 'salary' ? 'active' : ''}}" href="{{route('admin.report.teacher.salary')}}">Salary</a>
  </div>
</nav>