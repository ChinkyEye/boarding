<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="{{route('student.home')}}" class="brand-link">
    <img src="{{URL::to('/')}}/backend/images/logo.png" alt=" AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">School</span>
  </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{URL::to('/')}}/backend/images/user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{Auth::user()->name}}</a>
      </div>
    </div>
    <nav class="mt-2">
       <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ route('student.student-detail')}}" class="nav-link {{ (request()->is('student/about-us*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Profile</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('student.student-attendance')}}" class="nav-link {{ (request()->is('student/attendance*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Your Attendance</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('student.student-homework')}}" class="nav-link {{ (request()->is('student/homework*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Homework</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('student.student-book')}}" class="nav-link {{ (request()->is('student/library/book*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Book</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link {{ (request()->is('student/exam-section/exam*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Exam</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link {{ (request()->is('home/notice*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Notice</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('student.student-event')}}" class="nav-link {{ (request()->is('student/event*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Event</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{URL::to('/')}}/home" class="nav-link">
            <i class="far fa-eye nav-icon"></i>
            <p>View Calender</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>