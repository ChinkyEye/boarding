<template>
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- <li class="nav-item">
      <router-link to="/" class="nav-link">
        <i class="nav-icon fas fa-user"></i>
        <p>
          Profile
        </p>
      </router-link>
    </li> -->
    <li class="nav-item">
      <router-link to="/attendance" class="nav-link">
        <i class="nav-icon fas fa-chalkboard-teacher"></i>
        <p>
          Attendance
        </p>
      </router-link>
    </li>
    <li class="nav-item">
      <router-link to="/homework" class="nav-link">
        <i class="nav-icon fas fa-tasks"></i>
        <p>
          Homework
        </p>
      </router-link>
    </li>
    <li class="nav-item">
      <router-link to="/book" class="nav-link">
        <i class="nav-icon fas fa-book"></i>
        <p>
          Book
        </p>
      </router-link>
    </li>
    <li class="nav-item">
      <router-link to="/exam" class="nav-link">
        <i class="nav-icon fas fa-poll-h"></i>
        <p>
          Exam
        </p>
      </router-link>
    </li>
    <li class="nav-item">
      <router-link to="/account" class="nav-link">
        <i class="nav-icon fas fa-poll-h"></i>
        <p>
          Account
        </p>
      </router-link>
    </li>
    <li class="nav-item">
      <router-link to="/notice" class="nav-link">
        <i class="nav-icon fas fa-flag-checkered"></i>
        <p>
          Notice
        </p>
      </router-link>
    </li>
    <li class="nav-item">
      <router-link to="/event" class="nav-link">
        <i class="nav-icon fas fa-calendar-alt"></i>
        <p>
          Event
        </p>
      </router-link>
    </li>
    <li class="nav-item">
      <router-link to="/changepassword" class="nav-link">
        <i class="nav-icon fas fa-key"></i>
        <p>
          Change Password
        </p>
      </router-link>
    </li>

  </ul>
</template>