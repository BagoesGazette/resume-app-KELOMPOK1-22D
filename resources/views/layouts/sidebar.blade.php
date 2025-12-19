<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="/">
        @role('admin')
            ADMIN
        @else
            KANDIDAT
        @endrole
      </a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="/">
        @role('admin')
            AD
        @else
            KD
        @endrole
      </a>
    </div>

    <div class="sidebar-menu-wrapper">
      <ul class="sidebar-menu">
        <li class="menu-header">Menu</li>

        @role('admin')
        <!-- Menu untuk admin -->
        <li class="{{ Request::is('/') ? 'active' : '' }}">
          <a class="nav-link" href="/">
            <i class="fas fa-fire"></i> 
            <span>Dashboard</span>
          </a>
        </li>
        <li class="{{ Request::is('users*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-users"></i> 
            <span>Pengguna</span>
          </a>
        </li>
        <li class="{{ Request::is('job*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('job.index') }}">
            <i class="fas fa-briefcase"></i> 
            <span>Lowongan Kerja</span>
          </a>
        </li>
        <li class="{{ Request::is('kriteria') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('kriteria.index') }}">
            <i class="fas fa-columns"></i> 
            <span>Kriteria</span>
          </a>
        </li>
        <li class="{{ Request::is('laporan') ? 'active' : '' }}">
          <a class="nav-link" href="/">
            <i class="fas fa-file-alt"></i> 
            <span>Laporan</span>
          </a>
        </li>
        @endrole

        @role('kandidat')
        <!-- Menu untuk user -->
        <li class="{{ Request::is('/') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fire"></i> 
            <span>Dashboard</span>
          </a>
        </li>
        <li class="{{ Request::is('lowongan-kerja*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('lowongan-kerja.index') }}">
            <i class="fas fa-briefcase"></i> 
            <span>Lowongan Kerja</span>
          </a>
        </li>
        <li class="{{ Request::is('hasil') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('hasil.index') }}">
            <i class="fas fa-poll"></i> 
            <span>Hasil</span>
          </a>
        </li>
        @endrole
      </ul>
    </div>

    <!-- Tombol Logout di bawah -->
    <div class="logout-wrapper">
      <a href="{{ route('logout') }}" class="btn btn-primary btn-lg btn-block btn-icon-split"
         onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
        <i class="fas fa-rocket"></i> Logout
      </a>
      <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
      </form>
    </div>
  </aside>
</div>
