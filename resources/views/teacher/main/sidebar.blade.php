<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="{{route('teacher.home')}}" class="brand-link">
      <img src="{{URL::to('/')}}/backend/images/logo.png" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
       @php 
       preg_match_all('/(?<=\s|^)[a-z]/i', Auth::user()->getSchool->school_name, $schools); 
       @endphp
      <span class="brand-text font-weight-light">{{strtoupper(implode('', $schools[0]))}} @if(Auth::user()->getBatch)
      ({{Auth::user()->getBatch->name}})@endif</span>
    </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image pt-1">
        @php 
        $auth_name = Auth::user()->name.' '.Auth::user()->last_name; 
        preg_match_all('/(?<=\s|^)[a-z]/i', $auth_name, $matches); 
        @endphp
        <span class="border border-light rounded-circle py-1 {{count($matches[0]) == 1 ? 'px-2' : 'px-'.(3-count($matches[0]))}} bg-success text-light text-capitalize elevation-3">{{strtoupper(implode('', $matches[0]))}}</span>
      </div>
      <div class="info">
        <a href="#" class="d-block">{{Auth::user()->name.' '.Auth::user()->middle_name.' '.Auth::user()->last_name}}</a>
      </div>
    </div>
    <nav class="mt-2">
       <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ route('teacher.student-attendance.index')}}" class="nav-link {{ (request()->is('teacher/student-attendance*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Student Attendance</p>
          </a>
        </li>
        <li class="nav-item has-treeview {{ (request()->is('teacher/daily-record*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('teacher/daily-record*')) ? 'active' : '' }}">
            <i class="nav-icon far fa-plus-square"></i>
            <p>
              Daily Record
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('teacher.routine.index')}}" class="nav-link {{ (request()->is('teacher/daily-record/routine*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Routine</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('teacher.homework.index')}}" class="nav-link {{ (request()->is('teacher/daily-record/homework*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Homework</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ (request()->is('teacher/exam-section/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('teacher/exam-section/*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Exam Section
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('teacher.studenthasmark.index')}}" class="nav-link {{ (request()->is('teacher/exam-section/studenthasmark*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Mark</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('teacher.studenthasmark.ledger')}}" class="nav-link {{ (request()->is('teacher/exam-section/student/mark/ledger*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p> Ledger Mark</p>
              </a>
            </li>
          </ul>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('teacher.studenthasmark.ledger.view')}}" class="nav-link {{ (request()->is('teacher/exam-section/student/mark/view/ledger/mark*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Ledger View</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="{{ route('teacher.teacher-event')}}" class="nav-link {{ (request()->is('teacher/event*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Event</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('teacher.notice.index')}}" class="nav-link {{ (request()->is('teacher/notice*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Notice</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('teacher.remainder.index')}}" class="nav-link {{ (request()->is('teacher/remainder*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Remainder</p>
          </a>
        </li>
         
        <!-- <li class="nav-item">
          <a href="{{ route('student.student-attendance')}}" class="nav-link {{ (request()->is('student/attendance*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>Your Attendance</p>
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
        </li> -->
      </ul>
    </nav>
  </div>
</aside>