  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
      <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Mint Cleaning</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <!-- Avatar Circle with the First Character of User's Name -->
            <div class="avatar-circle" style="background-color: #f39c12; color: white; width: 35px; height: 35px; display: flex; justify-content: center; align-items: center; border-radius: 50%; font-size: 1.5rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
        <div class="info">
            <a href="/profile" class="d-block">{{ auth()->user()->name }}</a>
        </div>
    </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="/dashboard" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : ''}}">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          @if (auth()->user()->admin)   
            <li class="nav-item">
              <a href="/jobs" class="nav-link {{ request()->routeIs('jobs') ? 'active' : ''}}">
                <i class="nav-icon fas fa-briefcase"></i>
                <p>
                  Jobs
                </p>
              </a>
            </li>
          @endif
          <li class="nav-item">
            <a href="/schedule" class="nav-link {{ request()->routeIs('schedule') ? 'active' : ''}}">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                Schedule
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/job-list" class="nav-link {{ request()->routeIs('job_list') ? 'active' : ''}}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                Submissions
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/tickets" class="nav-link {{ request()->routeIs('tickets') ? 'active' : ''}}">
              <i class="nav-icon fas fa-ticket-alt"></i>
              <p>
                Tickets
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>