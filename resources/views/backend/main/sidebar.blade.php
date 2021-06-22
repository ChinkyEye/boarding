<aside class="main-sidebar sidebar-dark-primary elevation-4 inverted">
  <a href="{{route('admin.home')}}" class="brand-link">
    <img src="{{URL::to('/')}}/backend/images/logo.png" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
     @php preg_match_all('/(?<=\s|^)[a-z]/i', Auth::user()->getSchool->school_name, $schools); @endphp
    <span class="brand-text font-weight-light">{{strtoupper(implode('', $schools[0]))}} @if(Auth::user()->getBatch)
      ({{Auth::user()->getBatch->name}})
    @endif
  </span>
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
        <a href="#" class="d-block">{{Auth::user()->name}}</a>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview {{ (request()->is('home/main-entry/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('home/main-entry/*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-star"></i>
            <p>
              Main Entry
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ">
            <li class="nav-item">
              <a href="{{ route('admin.batch.index')}}" class="nav-link {{ (request()->is('home/main-entry/batch*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Batch</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.nationality.index')}}" class="nav-link {{ (request()->is('home/main-entry/nationality*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Nationality</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.shift.index')}}" class="nav-link {{ (request()->is('home/main-entry/shift*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Shift</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.section.index')}}" class="nav-link {{ (request()->is('home/main-entry/section*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Section</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.class.index')}}" class="nav-link {{ (request()->is('home/main-entry/class*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Class</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.period.index')}}" class="nav-link {{ (request()->is('home/main-entry/period*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Period</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ (request()->is('home/primary-entry/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('home/primary-entry/*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-plus"></i>
            <p>
              Primary Entry
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.student.index')}}" class="nav-link {{ (request()->is('home/primary-entry/student*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Student</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.teacher.index')}}" class="nav-link {{ (request()->is('home/primary-entry/teacher*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Teacher</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ (request()->is('home/daily-record/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('home/daily-record/*')) ? 'active' : '' }}">
            <i class="nav-icon far fa-plus-square"></i>
            <p>
              Daily Record
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.routine.index')}}" class="nav-link {{ (request()->is('home/daily-record/routine*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Routine</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.homework.index')}}" class="nav-link {{ (request()->is('home/daily-record/homework*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Homework</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ (request()->is('home/attendance/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('home/attendance/*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-edit"></i>
            <p>
              Attendance
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.student-attendance.index')}}" class="nav-link {{ (request()->is('home/attendance/student-attendance*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Student</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.teacher-attendance.index')}}" class="nav-link {{ (request()->is('home/attendance/teacher-attendance*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Teacher</p>
              </a>
            </li>
          </ul>
        </li>
        
        <li class="nav-item has-treeview {{ (request()->is('home/exam-section/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('home/exam-section/*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-poll-h"></i>
            <p>
              Exam Section
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.grade.index')}}" class="nav-link {{ (request()->is('home/exam-section/grade*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Grade</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.observation.index')}}" class="nav-link {{ (request()->is('home/exam-section/observation*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Observation</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.exam.index')}}" class="nav-link {{ (request()->is('home/exam-section/exam*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Exam</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.studenthasmark.index')}}" class="nav-link {{ (request()->is('home/exam-section/studenthasmark*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Mark</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.studenthasmark.ledger')}}" class="nav-link {{ (request()->is('home/exam-section/student/mark/ledger*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Marks Ledger</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.studenthasmark.ledger.view')}}" class="nav-link {{ (request()->is('home/exam-section/student/ledger/mark/view*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>View Marks Ledger</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ (request()->is('home/library/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('home/library/*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Library
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.book.index')}}" class="nav-link {{ (request()->is('home/library/book*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Book</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.issuebook.index')}}" class="nav-link {{ (request()->is('home/library/issuebook*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Issue Book</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview {{ (request()->is('home/account-section/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-rupee-sign"></i>
            <p>
              Account Section
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.topic.index')}}" class="nav-link {{ (request()->is('home/account-section/topic*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Topic</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.bill.index')}}" class="nav-link {{ (request()->is('home/account-section/bill*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Bill</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.fee.index')}}" class="nav-link {{ (request()->is('home/account-section/fee*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Fee</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.salary.index')}}" class="nav-link {{ (request()->is('home/account-section/salary*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Teacher Salary</p>
              </a>
            </li>
          </ul>
        </li>
        
        <li class="nav-item">
          <a href="{{ route('admin.notice.index')}}" class="nav-link {{ (request()->is('home/notice*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Notice</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.event.index')}}" class="nav-link {{ (request()->is('home/event*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-calendar-check"></i>
            <p>Add Event</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.calender.index')}}" class="nav-link {{ (request()->is('home/calender*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-calendar-alt"></i>
            <p>
              Calender
            </p>
          </a>
        </li>
        {{-- <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Role
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{URL::to('/')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Role</p>
              </a>
            </li>
          </ul>
        </li> --}}
      </ul>
    </nav>
  </div>
</aside>