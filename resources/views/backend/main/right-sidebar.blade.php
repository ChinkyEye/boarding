<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
	<div class="py-3 control-sidebar-content">
    {{-- <h5>Customize AdminLTE</h5><hr class="mb-2"/> --}}
    <ul class="nav nav-sidebar flex-column">
      <li class="nav-item">
        <a href="{{ route('admin.fee-report.index')}}" class="nav-link {{ (request()->is('home/account-section/report/fee-report*')) ? 'active' : '' }}">
          {{-- <i class="far fa-circle nav-icon"></i> --}}
          <i class="fas fa-file-alt nav-icon"></i>
          <span>Fee Report</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.report.teacher.profile')}}" class="nav-link {{ (request()->is('home/report/teacher*')) ? 'active' : '' }}">
          <i class="fas fa-file-signature nav-icon"></i>
          <span>Teacher Report</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.report.student.profile')}}" class="nav-link {{ (request()->is('home/report/student*')) ? 'active' : '' }}">
          <i class="fas fa-file-signature nav-icon"></i>
          <span>Student Report</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.teacherattendance-report.index')}}" class="nav-link {{ (request()->is('home/attendance/report/teacherattendance-report*')) ? 'active' : '' }}">
          <i class="fas fa-file-signature nav-icon"></i>
          <span>Teacher Attendance Report</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.studentattendance-report.index')}}" class="nav-link {{ (request()->is('home/attendance/report/studentattendance-report*')) ? 'active' : '' }}">
          <i class="fas fa-file-signature nav-icon"></i>
          <span>Student Attendance Report</span>
        </a>
      </li>
    </ul>
  </div>
</aside>