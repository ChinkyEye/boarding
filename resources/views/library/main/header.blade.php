<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{url()->previous()}}" class="nav-link">Back</a>
    </li>
  </ul>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link"  href="{{ route('teacher.password.index') }}">
        <i class="fas fa-key fa-lg"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('logout') }}"  class="nav-link bg-danger float-right"
      onclick="event.preventDefault();
      document.getElementById('logout-form').submit();">
      <i class="fa fa-power-off"></i>
      </a>
     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
     </form>
    </li>
  </ul>
</nav>