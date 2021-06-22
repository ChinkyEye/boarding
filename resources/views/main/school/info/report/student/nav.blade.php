<nav>
  <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
    <a class="nav-item nav-link {{$page == 'profile' ? 'active' : ''}}" href="{{route('main.school.student.report.profile',$school_info->slug)}}">Profile</a>
    <a class="nav-item nav-link {{$page == 'attendance' ? 'active' : ''}}"  href="{{route('main.school.student.report.attendance',$school_info->slug)}}">Attendance</a>
  </div>
</nav>

