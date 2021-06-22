<style type="text/css">
	.nav-item > .active:after{
	  /*border: 1px 1px solid red;*/
	  bottom: 0;
	  content: "";
	  display: block;
	  height: 2px;
	  left: 0;
	  position: absolute;
	  background: var(--blue);
	  width: 100%;
	}
	.hover-nav {
	  letter-spacing: 0.1em;
	  position: relative;
	}
	.hover-nav:after {    
	  background: none repeat scroll 0 0 transparent;
	  bottom: 0;
	  content: "";
	  display: block;
	  height: 2px;
	  left: 50%;
	  position: absolute;
	  background: #fff;
	  transition: width 0.4s ease 0s, left 0.3s ease 0s;
	  width: 0;
	}
	.hover-nav:hover:after { 
	  width: 100%; 
	  left: 0; 
	}
</style>
<section class="content-header pt-0 px-0">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<!--  Show this only on mobile to medium screens  -->
		<a class="navbar-brand d-lg-none" href="{{ route('main.school.info.index',$school_info->slug) }}">{{$school_info->school_name}}</a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<!--  Use flexbox utility classes to change how the child elements are justified  -->
		<div class="collapse navbar-collapse justify-content-between" id="navbarToggle">

			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link hover-nav {{ (request()->is('*'.$school_info->slug.'/teacher*')) ? 'active' : '' }}" href="{{ route('main.teacher.index',$school_info->slug) }}">Teacher</a>
				</li>
				<li class="nav-item">
					<a class="nav-link hover-nav {{ (request()->is('*'.$school_info->slug.'/report/allteacher-report*')) ? 'active' : '' }}" href="{{ route('main.school.teacher.report.profile',$school_info->slug) }}">Teacher Report</a>
				</li>
				<li class="nav-item">
					<a class="nav-link hover-nav {{ (request()->is('*'.$school_info->slug.'/attendance*')) ? 'active' : '' }}" href="{{ route('main.teacher-attendance.index',$school_info->slug) }}">Teacher Attendance</a>
				</li>
				<li class="nav-item">
					<a class="nav-link hover-nav {{ (request()->is('*'.$school_info->slug.'/library*')) ? 'active' : '' }}" href="{{ route('main.book.index',$school_info->slug) }}">Library</a>
				</li>
			</ul>
			<!--   Show this only lg screens and up   -->
			<a class="navbar-brand d-none d-lg-block" href="{{ route('main.school.info.index',$school_info->slug) }}">{{$school_info->school_name}}</a>
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link hover-nav {{ (request()->is('*'.$school_info->slug.'/student/*')) ? 'active' : '' }}" href="{{ route('main.student.index',$school_info->slug) }}">Student</a>
				</li>
				<li class="nav-item">
					<a class="nav-link hover-nav {{ (request()->is('*'.$school_info->slug.'/report/allstudent-report*')) ? 'active' : '' }}" href="{{ route('main.school.student.report.profile',$school_info->slug) }}">Student Report</a>
				</li>
				<li class="nav-item">
					<a class="nav-link hover-nav {{ (request()->is('*'.$school_info->slug.'/student-attendance*')) ? 'active' : '' }}" href="{{ route('main.student-attendance.index',$school_info->slug) }}">Student Attendance</a>
				</li>
				<li class="nav-item">
					<a class="nav-link hover-nav {{ (request()->is('*'.$school_info->slug.'/exam*')) ? 'active' : '' }}" href="{{ route('main.studenthasmark.ledger.view',$school_info->slug) }}">Exam</a>
				</li>
				<li class="nav-item">
					<a href="javascript:void(0);" class="nav-link" data-card-widget="maximize"><i class="fas fa-expand"></i></a>
				</li>
			</ul>
		</div>
	</nav>
</section>