  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        @auth('admin')
        <a href="/admin" class="nav-link">Trang chủ</a>
        @else
        <a href="/" class="nav-link">Trang chủ</a>
        @endauth
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-inline-block">
          @auth('admin')
          <a href="{{route('admin.logout')}}" class="nav-link">Đăng xuất</a>
          @else
          <a href="{{route('user.logout')}}" class="nav-link">Đăng xuất</a>
          @endauth
        </li>
      </ul>
  </nav>
  <!-- /.navbar -->
