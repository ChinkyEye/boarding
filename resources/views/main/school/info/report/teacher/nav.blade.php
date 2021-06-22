<nav>
  <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
    <a class="nav-item nav-link {{$page == 'profile' ? 'active' : ''}}" href="{{route('main.school.teacher.report.profile',$school_info->slug)}}">Profile</a>
    <a class="nav-item nav-link {{$page == 'subjectclasslist' ? 'active' : ''}}" href="{{route('main.school.teacher.report.subjectclass',$school_info->slug)}}">Class & Subject</a>
    <a class="nav-item nav-link {{$page == 'attendance' ? 'active' : ''}}"  href="{{route('main.school.teacher.report.attendance',$school_info->slug)}}">Attendance</a>
    <a class="nav-item nav-link {{$page == 'salary' ? 'active' : ''}}" href="{{route('main.school.teacher.report.salary',$school_info->slug)}}">Salary</a>
  </div>
</nav>

