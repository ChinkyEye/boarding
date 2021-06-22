<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="{{route('main.home')}}" class="brand-link">
    <img src="{{URL::to('/')}}/backend/images/logo.png" alt=" AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">School</span>
  </a>
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{URL::to('/')}}/backend/images/user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="{{route('main.home')}}" class="d-block">{{Auth::user()->name}}</a>
      </div>
    </div>
    <nav class="mt-2">
       <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach ($settings as $element)
          <li class="nav-item">
            <a href="{{ route('main.school.info.index',$element->slug) }}" class="nav-link {{ (request()->is('*school/info/'.$element->slug.'*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-book"></i>
              <p>{{$element->school_name}}</p>
            </a>
          </li>
        @endforeach
      </ul>
    </nav>
  </div>
</aside>