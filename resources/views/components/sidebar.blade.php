<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">NetutasLib</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">NL</a>
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item ">
                <a href="{{ url('/') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item {{ Request::is('category') ? 'active' : '' }}">
                <a href="{{ url('category') }}" class="nav-link"><i class="fas fa-columns"></i>
                    <span>Kategori</span></a>
            </li>
            <li class="nav-item {{ Request::is('book') ? 'active' : '' }}">
                <a href="{{ url('book') }}" class="nav-link"><i class="fas fa-book"></i> <span>Buku</span></a>
            </li>
            <li class="nav-item {{ Request::is('member') ? 'active' : '' }}">
                <a href="{{ url('member') }}" class="nav-link"><i class="fas fa-users"></i> <span>Member</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ url('loan') }}" class="nav-link"><i class="fas fa-book-open"></i>
                    <span>Peminjaman</span></a>
            </li>
            <li class="nav-item {{ Request::is('visit') ? 'active' : '' }}">
                <a href="{{ url('visit') }}" class="nav-link"><i class="fas fa-walking"></i> <span>Kunjungan</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ url('user') }}" class="nav-link"><i class="fas fa-user"></i> <span>User</span></a>
            </li>
        </ul>

        {{-- <div class="hide-sidebar-mini mt-4 mb-4 p-3">
            <a href="https://getstisla.com/docs"
                class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Documentation
            </a>
        </div> --}}
    </aside>
</div>
