<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">
  <vue-progress-bar></vue-progress-bar>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      {{-- <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url()->previous()}}" class="nav-link">Back</a>
      </li> --}}
    </ul>
    <ul class="navbar-nav ml-auto">
      {{-- <li class="nav-item">
        <a class="nav-link"  href="{{ route('admin.changePassword') }}">
          <i class="fas fa-key fa-lg"></i>
        </a>
      </li> --}}
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('logout') }}" class="nav-link bg-danger float-right"
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
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('student.student-detail')}}" class="brand-link">
      <img src="{{URL::to('/')}}/backend/images/logo.png" alt=" AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">School</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{URL::to('/')}}/backend/images/user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <router-link :to="{ name: 'dashboard' }" class="d-block">
              {{Auth::user()->name}}
            </router-link>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <sidebar></sidebar> <!-- component -->
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <student-main></student-main>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  {{-- <aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside> --}}
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer text-center">
    <strong>Copyright © 2020 <a href="http://techware.com.np">Techware</a>.</strong>
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->
</body>
<script src="{{ asset('js/app.js') }}" defer></script>
</html>
