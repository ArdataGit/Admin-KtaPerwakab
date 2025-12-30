<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-id-card"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name') }}</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Menu Dashboard Dropdown -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDashboard"
            aria-expanded="true" aria-controls="collapseDashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <div id="collapseDashboard" class="collapse" aria-labelledby="headingDashboard" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('dashboard') }}">Dashboard Utama</a>
                <a class="collapse-item" href="#">Analytics</a>
                <a class="collapse-item" href="/master/home-banner">Banners</a>
            </div>
        </div>
    </li>

    @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
        <!-- Keanggotaan -->
        <div class="sidebar-heading">Keanggotaan</div>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.anggota') }}">
                <i class="fas fa-users"></i>
                <span>Data Anggota & Publik</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-id-badge"></i>
                <span>Kartu Anggota (KTA)</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('membership-fee.index') }}">
                <i class="fas fa-money"></i>
                <span>Iuran Anggota</span>
            </a>
        </li>
    @endif

    <!-- Modul Anggota Umum -->
    {{-- @if(Auth::user()->role == 'anggota')
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-download"></i> <span>Unduh KTA</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-wallet"></i> <span>Riwayat Pembayaran</span>
        </a>
    </li>
    @endif --}}

    @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
        <!-- Berita & Pengumuman -->
        <hr class="sidebar-divider">

        <div class="sidebar-heading">Publikasi</div>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('news.index') }}">
                <i class="fas fa-newspaper"></i>
                <span>Berita & Artikel</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('publikasi.index') }}">
                <i class="fas fa-brain"></i>
                <span>Publikasi Karya</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('bisnis.index') }}">
                <i class="fas fa-building"></i>
                <span>Bisnis</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('struktur-organisasi.index') }}">
                <i class="fas fa-sitemap"></i>
                <span>Struktur Organisasi</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('info-duka.index') }}">
                <i class="fas fa-bell"></i>
                <span>Info Duka</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('master.donation-campaign.index') }}">
                <i class="fas fa-bell"></i>
                <span>Campaign Donation</span>
            </a>
        </li>
    @endif

    @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
        <!-- UMKM -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('umkm.index') }}">
                <i class="fas fa-store"></i>
                <span>UMKM</span>
            </a>
        </li>
    @endif

    {{-- @if(Auth::user()->role == 'bendahara' || Auth::user()->role == 'superadmin')
    <!-- Keuangan -->
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Keuangan</div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('keuangan.index') }}">
            <i class="fas fa-money-check-alt"></i>
            <span>Laporan Keuangan</span>
        </a>
    </li>
    @endif --}}
    <hr class="sidebar-divider">

    <!-- Point Management -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePoint" aria-expanded="true"
            aria-controls="collapsePoint">
            <i class="fas fa-fw fa-coins"></i>
            <span>Point</span>
        </a>

        <div id="collapsePoint" class="collapse" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">

                <a class="collapse-item" href="{{ route('point-kategoris.index') }}">
                    Kategori Point
                </a>

                <a class="collapse-item" href="{{ route('penukaran-poin.index') }}">
                    Produk Point
                </a>

                <a class="collapse-item" href="{{ route('tukar-point.index') }}">
                    Tukar Point
                </a>

            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <!-- Master Dropdown -->
    @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="true"
                aria-controls="collapseMaster">
                <i class="fas fa-fw fa-cog"></i>
                <span>Master</span>
            </a>
            <div id="collapseMaster" class="collapse" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('posisi.index') }}">Posisi</a>
                    <a class="collapse-item" href="{{ route('divisi.index') }}">Divisi</a>
                    <a class="collapse-item" href="{{ route('user.index') }}">User</a>
                </div>
            </div>
        </li>
    @endif

</ul>
<!-- End Sidebar -->