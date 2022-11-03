  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @auth('admin')
    <a href="{{route('admin.home')}}" class="brand-link">
      <img src="{{ asset('images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text">{{Auth::user()->name}}</span>
    </a>
    @else
    <a href="{{route('user.home')}}" class="brand-link">
      <img src="{{ asset('images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text">{{Auth::user()->name}}</span>
    </a>
    @endauth

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          @auth('admin')
          <li class="nav-item">
            <a href="{{route('admin.home')}}" class="nav-link {{ Request::is('admin') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('admin.tenders.index')}}" class="nav-link {{ Request::is('admin/tender*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th-list"></i>
              <p>
                Tender
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('admin.profile')}}" class="nav-link {{ Request::is('admin/profile*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-id-card"></i>
              <p>
                Hồ sơ của tôi
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('admin.logout')}}" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
                Đăng xuất
              </p>
            </a>
          </li>
          @else
          <li class="nav-item">
            <a href="{{route('user.home')}}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('user.tenders.index')}}" class="nav-link {{ Request::is('tender*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th-list"></i>
              <p>
                Tender
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('user.profile')}}" class="nav-link {{ Request::is('profile*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-id-card"></i>
              <p>
                Hồ sơ của tôi
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('user.logout')}}" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p>
                Đăng xuất
              </p>
            </a>
          </li>
          @endauth

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
          <li class="nav-item">
            <a href="{{route('admin.suppliers.index')}}" class="nav-link {{ Request::is('admin/suppliers*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-truck"></i>
              <p>
                Nhà cung cấp
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('admin.materials.index')}}" class="nav-link {{ Request::is('admin/materials*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-box-open"></i>
              <p>
                Hàng hóa
              </p>
            </a>
          </li>

          @can('create-admin')
          <li class="nav-item">
            <a href="{{route('admin.roles.index')}}" class="nav-link {{ Request::is('admin/roles*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-id-card-alt"></i>
              <p>
                Chức vụ
              </p>
            </a>
          </li>
          @endcan
          @endauth
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
