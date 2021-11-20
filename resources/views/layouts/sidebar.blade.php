  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @auth('admin')
    <a href="{{route('admin.home')}}" class="brand-link">
      <img src="{{ asset('images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text">Honghafeed</span>
    </a>
    @else
    <a href="{{route('user.home')}}" class="brand-link">
      <img src="{{ asset('images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text">Honghafeed</span>
    </a>
    @endauth

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            @auth('admin')
            <a href="{{route('admin.home')}}" class="nav-link {{ Request::is('admin') ? 'active' : '' }}">
            @else
            <a href="{{route('user.home')}}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
            @endauth
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link {{ Request::is('user/dashboard') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v1</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link {{ Request::is('tender*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th-list"></i>
              <p>
                Tender
              </p>
            </a>
          </li>


          @auth('admin')
          <li class="nav-header">HỆ THỐNG</li>
          <li class="nav-item">
            <a href="{{route('admin.users.index')}}" class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Người dùng
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin.admins.index')}}" class="nav-link {{ Request::is('admin/admins*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>
                Người quản trị
              </p>
            </a>
          </li>
          @endauth
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
