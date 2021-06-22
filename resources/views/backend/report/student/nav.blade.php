<nav>
  <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
    <a class="nav-item nav-link {{$page == 'profile' ? 'active' : ''}}" href="{{route('admin.report.student.profile')}}">Profile</a>
    <a class="nav-item nav-link {{$page == 'attendance' ? 'active' : ''}}"  href="{{route('admin.report.student.attendance')}}" >Attendance</a>
  </div>
</nav>