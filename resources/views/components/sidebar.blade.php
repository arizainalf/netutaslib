<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}">NetutasLib</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">NL</a>
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item  {{ Request::is('admin') ? 'active' : '' }}">
                <a href="{{ url('/admin') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/category') ? 'active' : '' }}">
                <a href="{{ url('admin/category') }}" class="nav-link"><i class="fas fa-columns"></i>
                    <span>Kategori</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/book') ? 'active' : '' }}">
                <a href="{{ url('admin/book') }}" class="nav-link"><i class="fas fa-book"></i> <span>Buku</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/member') ? 'active' : '' }}">
                <a href="{{ url('admin/member') }}" class="nav-link"><i class="fas fa-users"></i>
                    <span>Member</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/loan') ? 'active' : '' }}">
                <a href="{{ url('admin/loan') }}" class="nav-link"><i class="fas fa-book-open"></i>
                    <span>Peminjaman</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/visit') ? 'active' : '' }}">
                <a href="{{ url('admin/visit') }}" class="nav-link"><i class="fas fa-walking"></i>
                    <span>Kunjungan</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/user') ? 'active' : '' }}">
                <a href="{{ url('admin/user') }}" class="nav-link"><i class="fas fa-user"></i> <span>User</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/profill') ? 'active' : '' }}">
                <a href="{{ url('admin/profil') }}" class="nav-link"><i class="fa-solid fa-id-badge"></i>
                    <span>Profile</span></a>
            </li>
        </ul>
        <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="{{ url('logout') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
            </a>
        </div>
    </aside>
</div>
