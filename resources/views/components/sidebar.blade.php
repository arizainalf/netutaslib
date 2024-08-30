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
            <li class="nav-item {{ Request::is('admin/kelas') ? 'active' : '' }}">
                <a href="{{ url('admin/kelas') }}" class="nav-link"><i class="fas fa-book"></i> <span>Kelas</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/siswa') ? 'active' : '' }}">
                <a href="{{ url('admin/siswa') }}" class="nav-link"><i class="fas fa-users"></i>
                    <span>Siswa</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/mapel') ? 'active' : '' }}">
                <a href="{{ url('admin/mapel') }}" class="nav-link"><i class="fas fa-users"></i>
                    <span>Mapel</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/kategori') ? 'active' : '' }}">
                <a href="{{ url('admin/kategori') }}" class="nav-link"><i class="fas fa-columns"></i>
                    <span>Kategori</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/buku') ? 'active' : '' }}">
                <a href="{{ url('admin/buku') }}" class="nav-link"><i class="fas fa-book"></i> <span>Buku</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/peminjamanmapel') ? 'active' : '' }}">
                <a href="{{ url('admin/peminjamanmapel') }}" class="nav-link"><i class="fas fa-book-open"></i>
                    <span>Peminjaman Buku Mapel</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/peminjaman') ? 'active' : '' }}">
                <a href="{{ url('admin/peminjaman') }}" class="nav-link"><i class="fas fa-book-open"></i>
                    <span>Peminjaman</span></a>
            </li>
            <li class="nav-item {{ Request::is('admin/kunjungan') ? 'active' : '' }}">
                <a href="{{ url('admin/kunjungan') }}" class="nav-link"><i class="fas fa-walking"></i>
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
