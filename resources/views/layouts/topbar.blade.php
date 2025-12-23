<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-2 mb-4"
    style="position: sticky; top: 0; z-index: 1000;">
    <div class="container-fluid">


        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
        <!-- Judul Halaman -->
        <span class="navbar-brand fw-bold">Admin Panel</span>

        <!-- Bagian Kanan -->
        <ul class="navbar-nav ms-auto align-items-center">

            <!-- Notification -->
            <li class="nav-item dropdown me-3">
                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-bell"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Tidak ada notifikasi</a></li>
                </ul>
            </li>

            <!-- Profile Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown"
                    role="button" data-bs-toggle="dropdown">
                    <img src="https://via.placeholder.com/32" class="rounded-circle me-2" alt="avatar">
                    <span>Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                </ul>
            </li>

        </ul>
    </div>
</nav>