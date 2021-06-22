<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="{{route('library.home')}}" class="brand-link">
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
        <li class="nav-item has-treeview {{ (request()->is('library/*')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ (request()->is('library/*')) ? 'active' : '' }}">
            <i class="nav-icon fas fa-book"></i>
            <p>
              Library
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('library.book.index')}}" class="nav-link {{ (request()->is('library/book*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Book</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('library.issuebook.index')}}" class="nav-link {{ (request()->is('library/issuebook*')) ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Issue Book</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>