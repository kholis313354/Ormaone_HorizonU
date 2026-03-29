<?= $this->extend('components/layouts/app') ?>
<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<style>
    /* Styling untuk header dengan hamburger dan icons - copied from original */
    header.mb-3 {
        padding: 0.75rem 0;
    }

    .burger-btn {
        color: #4e73df !important;
        text-decoration: none;
        padding: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .burger-btn:hover {
        color: #2e59d9 !important;
    }

    .burger-btn i {
        font-size: 1.5rem;
    }

    /* Modern Search */
    .modern-search {
        position: relative;
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        width: 320px;
    }

    .modern-search:focus-within {
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .modern-search .search-icon {
        color: #9ca3af;
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    .modern-search input {
        border: none;
        outline: none;
        background: transparent;
        flex: 1;
        padding: 0;
        font-size: 0.875rem;
        color: #111827;
    }

    .modern-search input::placeholder {
        color: #9ca3af;
    }

    .modern-search .search-shortcut {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        margin-left: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modern-search .search-shortcut:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }

    /* Icon Buttons */
    .icon-btn-wrapper {
        position: relative;
    }

    .icon-btn {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: #f3f4f6;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }

    .icon-btn:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .icon-btn i {
        font-size: 1.125rem;
    }

    /* Badges */
    .notification-badge {
        position: absolute;
        top: -0.125rem;
        right: -0.125rem;
        width: 0.75rem;
        height: 0.75rem;
        background: #f97316;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    .chat-badge {
        position: absolute;
        top: -0.25rem;
        right: -0.25rem;
        min-width: 1.25rem;
        height: 1.25rem;
        background: #dc3545;
        border: 2px solid #fff;
        border-radius: 0.625rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6875rem;
        font-weight: 600;
        color: #fff;
        padding: 0 0.375rem;
    }

    .chat-badge.hidden {
        display: none;
    }

    /* User Dropdown */
    .user-dropdown-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        background: transparent;
        border: none;
        cursor: pointer;
        text-decoration: none;
        color: #111827;
        transition: all 0.2s;
        border-radius: 0.5rem;
    }

    .user-dropdown-btn:hover {
        background: #f3f4f6;
    }

    .user-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        overflow: hidden;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-avatar i {
        font-size: 1.5rem;
    }

    .user-name {
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
    }

    .user-chevron {
        color: #9ca3af;
        font-size: 0.75rem;
    }

    /* Layout Helpers */
    header .d-flex {
        width: 100%;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-left: auto;
    }

    /* Chart Styles */
    .chart-container {
        height: 400px;
        position: relative;
    }

    .title-body-chart {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1.5rem;
    }

    .chart-arrow {
        position: relative;
    }

    .card-chart {
        border: 1px solid #e0e0e0;
        border-top: 2px solid #d0d0d0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        background: #fff;
        padding: 0;
        overflow: hidden;
    }

    .card-chart .card-body {
        padding: 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Standard Card Wrapper */
    .card-widget {
        border: 1px solid #e0e0e0;
        border-top: 2px solid #d0d0d0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        background: #fff;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card-widget .card-header {
        background: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: #333;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-widget .card-body {
        padding: 1.5rem;
    }

    /* List Styles */
    .widget-list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .widget-list-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .widget-list-item:first-child {
        padding-top: 0;
    }

    .widget-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .modern-search {
            width: 280px;
        }
    }

    @media (max-width: 767.98px) {
        header .d-flex {
            gap: 0.75rem !important;
            flex-wrap: nowrap;
        }

        .header-left {
            flex: 1;
            min-width: 0;
        }

        .header-right {
            flex-shrink: 0;
        }

        .modern-search {
            width: auto;
            max-width: none;
            flex: 1;
        }

        .user-name {
            display: none;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* Styling untuk header dengan hamburger dan icons */
    header.mb-3 {
        padding: 0.75rem 0;
    }

    .burger-btn {
        color: #4e73df !important;
        text-decoration: none;
        padding: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .burger-btn:hover {
        color: #2e59d9 !important;
    }

    .burger-btn i {
        font-size: 1.5rem;
    }

    /* Styling untuk search bar modern - ukuran sedang di kiri */
    .modern-search {
        position: relative;
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        width: 320px;
        /* Diperpanjang sedikit ke kanan */
    }

    .modern-search:focus-within {
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .modern-search .search-icon {
        color: #9ca3af;
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    .modern-search input {
        border: none;
        outline: none;
        background: transparent;
        flex: 1;
        padding: 0;
        font-size: 0.875rem;
        color: #111827;
    }

    .modern-search input::placeholder {
        color: #9ca3af;
    }

    .modern-search .search-shortcut {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        margin-left: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modern-search .search-shortcut:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }

    /* Styling untuk icon buttons dengan circular background - ukuran sedang */
    .icon-btn-wrapper {
        position: relative;
    }

    .icon-btn {
        width: 2.5rem;
        /* Ukuran sedang seperti search bar */
        height: 2.5rem;
        /* Ukuran sedang seperti search bar */
        border-radius: 50%;
        background: #f3f4f6;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }

    .icon-btn:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .icon-btn i {
        font-size: 1.125rem;
        /* Ukuran sedang */
    }

    /* Notification badge - ukuran sedang */
    .notification-badge {
        position: absolute;
        top: -0.125rem;
        right: -0.125rem;
        width: 0.75rem;
        /* Ukuran sedang */
        height: 0.75rem;
        /* Ukuran sedang */
        background: #f97316;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    /* Chat badge untuk unread pesan */
    .chat-badge {
        position: absolute;
        top: -0.25rem;
        right: -0.25rem;
        min-width: 1.25rem;
        height: 1.25rem;
        background: #dc3545;
        border: 2px solid #fff;
        border-radius: 0.625rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6875rem;
        font-weight: 600;
        color: #fff;
        padding: 0 0.375rem;
    }

    .chat-badge.hidden {
        display: none;
    }

    /* User dropdown dengan nama - ukuran sedang */
    .user-dropdown-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        background: transparent;
        border: none;
        cursor: pointer;
        text-decoration: none;
        color: #111827;
        transition: all 0.2s;
        border-radius: 0.5rem;
    }

    .user-dropdown-btn:hover {
        background: #f3f4f6;
    }

    .user-avatar {
        width: 2.5rem;
        /* Ukuran sedang seperti search bar */
        height: 2.5rem;
        /* Ukuran sedang seperti search bar */
        border-radius: 50%;
        background: #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        overflow: hidden;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-avatar i {
        font-size: 1.5rem;
        /* Ukuran sedang */
    }

    .user-name {
        font-size: 0.875rem;
        /* Ukuran sedang */
        font-weight: 500;
        color: #111827;
    }

    .user-chevron {
        color: #9ca3af;
        font-size: 0.75rem;
        /* Ukuran sedang */
    }

    /* Layout header - search kiri, icons kanan */
    header .d-flex {
        width: 100%;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-left: auto;
    }

    /* Responsive untuk tablet */
    @media (max-width: 991.98px) {
        .modern-search {
            width: 280px;
            /* Diperpanjang sedikit */
        }

        .icon-btn {
            width: 2.5rem;
            height: 2.5rem;
        }

        .icon-btn i {
            font-size: 1.125rem;
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
        }

        .user-avatar i {
            font-size: 1.5rem;
        }

        .user-name {
            font-size: 0.875rem;
        }
    }

    /* Responsive untuk mobile */
    @media (max-width: 767.98px) {
        header .d-flex {
            gap: 0.75rem !important;
            flex-wrap: nowrap;
            /* Tidak wrap agar sejajar dalam satu baris */
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
            min-width: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .modern-search {
            flex: 1;
            min-width: 0;
            width: auto;
            max-width: none;
        }

        .icon-btn {
            width: 2.5rem;
            height: 2.5rem;
        }

        .icon-btn i {
            font-size: 1.125rem;
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
        }

        .user-avatar i {
            font-size: 1.5rem;
        }

        .user-name {
            font-size: 0.8125rem;
        }
    }

    @media (max-width: 575.98px) {
        header .d-flex {
            gap: 0.5rem !important;
        }

        .header-left {
            gap: 0.5rem;
        }

        .header-right {
            gap: 0.375rem;
        }

        .burger-btn i {
            font-size: 1.3rem;
        }

        .modern-search {
            flex: 1;
            min-width: 0;
        }

        .modern-search .search-shortcut {
            display: none;
        }

        .icon-btn {
            width: 2.25rem;
            height: 2.25rem;
        }

        .icon-btn i {
            font-size: 1rem;
        }

        .user-avatar {
            width: 2.25rem;
            height: 2.25rem;
        }

        .user-avatar i {
            font-size: 1.25rem;
        }

        .user-name {
            font-size: 0.75rem;
        }
    }

    /* Styling untuk tabel dengan border tebal - copied from user/index.php */
    .custom-table {
        border: 2px solid #dee2e6 !important;
    }

    .custom-table th,
    .custom-table td {
        border: 1px solid #dee2e6 !important;
    }

    .custom-table thead th {
        border-bottom: 2px solid #dee2e6 !important;
    }

    /* Dark Mode Improvements */
    [data-bs-theme="dark"] .modern-search {
        background: #1e1e2d;
        border-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .modern-search input {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .modern-search input::placeholder {
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .modern-search .search-shortcut {
        background: #2b2b40;
        border-color: #4b4b5a;
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .icon-btn {
        background: #1e1e2d;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .icon-btn:hover {
        background: #2b2b40;
    }

    [data-bs-theme="dark"] .user-dropdown-btn {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .user-dropdown-btn:hover {
        background: #1e1e2d;
    }

    [data-bs-theme="dark"] .user-name,
    [data-bs-theme="dark"] .user-chevron {
        color: #e2e2e2 !important;
    }

    [data-bs-theme="dark"] .card-widget {
        background: #1e1e2d;
        border-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .card-widget .card-header {
        border-bottom-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .card-widget .card-title {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .card-chart {
        background: #1e1e2d;
        border-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .card-chart .title-body-chart {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .widget-list-item {
        border-bottom-color: #2b2b40;
    }

    [data-bs-theme="dark"] .widget-list-item h6 {
        color: #e2e2e2 !important;
    }

    [data-bs-theme="dark"] .text-dark {
        color: #e2e2e2 !important;
    }

    /* Ensure table text is readable in dark mode */
    [data-bs-theme="dark"] .custom-table {
        color: #e2e2e2;
        border-color: #4b4b5a !important;
    }

    [data-bs-theme="dark"] .custom-table th,
    [data-bs-theme="dark"] .custom-table td {
        border-color: #4b4b5a !important;
    }
</style>

<header class="mb-3">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <!-- Bagian Kiri: Hamburger + Search -->
        <div class="header-left">
            <!-- Hamburger Menu - Hanya untuk Mobile/Tablet -->
            <a href="#" class="burger-btn d-block d-xl-none" id="sidebarToggle">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <!-- Search Bar Modern - Pojok Kiri, Ukuran Sedang -->
            <div class="d-none d-md-inline-block">
                <div class="modern-search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search..." aria-label="Search" id="modernSearchInput" />
                    <button type="button" class="search-shortcut" title="Press ⌘K to search">⌘K</button>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: Chat, Notification, User -->
        <div class="header-right">
            <!-- Chat Dropdown -->
            <div class="dropdown icon-btn-wrapper">
                <a class="icon-btn" id="navbarDropdownChat" href="<?= url_to('pesan.index') ?>" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                    <i class="fas fa-comment-dots"></i>
                    <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                        <span class="chat-badge"><?= $unreadCount > 99 ? '99+' : $unreadCount ?></span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end bg-brown" aria-labelledby="navbarDropdownChat">
                    <li>
                        <a class="dropdown-item" href="<?= url_to('pesan.index') ?>">
                            <i class="bi bi-chat-dots me-2"></i>Aspirasi Mahasiswa
                            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                                <span class="badge bg-danger float-end"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Notification Dropdown -->
            <?= view_cell('App\Cells\NotificationCell::render') ?>

            <!-- User Dropdown -->
            <div class="dropdown">
                <a class="user-dropdown-btn" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false" data-bs-auto-close="true">
                    <div class="user-avatar">
                        <?php
                        $profilePhoto = session()->get('profile_photo');
                        if (!empty($profilePhoto) && file_exists(FCPATH . 'uploads/profile/' . $profilePhoto)): ?>
                            <img src="<?= base_url('uploads/profile/' . $profilePhoto) ?>" alt="Profile">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <span class="user-name d-none d-sm-inline"><?= session()->get('name') ?? 'User' ?></span>
                    <i class="fas fa-chevron-down user-chevron"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end bg-brown" aria-labelledby="navbarDropdownUser">
                    <li>
                        <a class="dropdown-item" href="<?= url_to('profile.edit') ?>">
                            <i class="fas fa-user-circle fa-lg me-2"></i>Profile
                        </a>
                        <hr class="dropdown-divider light" />
                    </li>
                    <li>
                        <a class="dropdown-item" href="#!">
                            <i class="fas fa-cog fa-lg me-2"></i>Pengaturan
                        </a>
                        <hr class="dropdown-divider light" />
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url_to('logout') ?>">
                            <i class="fas fa-sign-out-alt fa-lg me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="page-heading mb-4">
    <h3>Dashboard Overview</h3>
    <p class="text-subtitle text-muted">Ringkasan aktivitas dan status terkini organisasi Anda.</p>
</div>

<!-- SECTION 1: KEY STATS -->
<section class="section mb-2">
    <div class="row">
        <!-- Total Berita (Was Total Anggota) -->
        <div class="col-6 col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon purple mb-2"><i class="bi-newspaper"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Berita</h6>
                            <h6 class="font-extrabold mb-0"><?= $totalBerita ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Document (Was Total Pemilihan) -->
        <div class="col-6 col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon blue mb-2"><i class="bi-file-earmark-text"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Document</h6>
                            <h6 class="font-extrabold mb-0"><?= $totalDocument ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Suara -->
        <div class="col-6 col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon red mb-2"><i class="bi-bar-chart-line"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Suara</h6>
                            <h6 class="font-extrabold mb-0"><?= $totalSuara ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- E-Sertifikat -->
        <div class="col-6 col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                            <div class="stats-icon text-white bg-warning mb-2"><i class="bi-award"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">E-Sertifikat</h6>
                            <h6 class="font-extrabold mb-0"><?= $totalSertifikat ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 2: CHARTS (Conditional) -->
<?php if ($showStatistik && !empty($totalSuaras)): ?>
    <section class="section mb-4">
        <div class="card-widget h-100">
            <div class="card-header">
                Statistik Voting Real-Time
            </div>
            <div class="card-body">
                <div class="chart-container" style="min-height: 400px;">
                    <canvas id="chart-voting"></canvas>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- SECTION 3: ACTIVITY WIDGETS -->
<section class="section">
    <div class="row">
        <!-- LEFT COLUMN: Agenda & Berita Tables -->
        <div class="col-12 col-lg-8">
            <!-- Widget Agenda -->
            <div class="card-widget mb-4">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title">Agenda Mendatang</h5>
                    <a href="<?= url_to('kalender.index') ?>" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($upcomingEvents)): ?>
                        <?php foreach ($upcomingEvents as $evt): ?>
                            <div class="widget-list-item">
                                <div class="d-flex align-items-center">
                                    <?php
                                    $colorClass = 'bg-light-' . ($evt['event_color'] ?? 'primary');
                                    $iconColor = 'text-' . ($evt['event_color'] ?? 'primary');
                                    $dateObj = new DateTime($evt['start_date']);
                                    $month = $dateObj->format('M');
                                    $day = $dateObj->format('d');
                                    ?>
                                    <div class="text-center me-3 px-2 py-1 rounded border">
                                        <div class="small text-uppercase fw-bold text-muted"
                                            style="font-size: 0.7rem; line-height:1;"><?= $month ?></div>
                                        <div class="fs-5 fw-bold text-dark" style="line-height:1;"><?= $day ?></div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-dark"><?= esc($evt['event_title']) ?></h6>
                                        <div class="small text-muted">
                                            <i class="bi bi-building me-1"></i><?= esc($evt['user_name'] ?? 'Organisasi') ?>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-light text-secondary rounded-pill"><?= $dateObj->format('H:i') ?>
                                    WIB</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">Belum ada agenda mendatang.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Widget Berita Table (Was Active Forms) -->
            <div class="card-widget mb-4">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title">Berita Terbaru</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($latestBerita)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 custom-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="d-none d-md-table-cell">No</th>
                                        <th>Nama Organisasi</th>
                                        <th class="d-none d-md-table-cell">Foto</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($latestBerita as $news): ?>
                                        <tr>
                                            <td class="d-none d-md-table-cell"><?= $i++ ?></td>
                                            <td>
                                                <span class="fw-bold"><?= esc($news['user_name'] ?? 'Admin') ?></span>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <?php if (!empty($news['gambar'])): ?>
                                                    <img src="<?= base_url('uploads/berita/' . $news['gambar']) ?>" alt="Foto"
                                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($news['nama_kegiatan']) ?></td>
                                            <td><?= date('d M Y', strtotime($news['tanggal'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">Belum ada berita.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Messages & Docs -->
        <div class="col-12 col-lg-4">
            <!-- Widget Recent Messages -->
            <div class="card-widget mb-4">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title">Aspirasi Terbaru</h5>
                    <a href="<?= url_to('pesan.index') ?>" class="btn btn-sm btn-light">Inbox</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentMessages)): ?>
                        <?php foreach ($recentMessages as $msg): ?>
                            <div class="widget-list-item align-items-start">
                                <div class="d-flex align-items-start w-100">
                                    <div class="widget-icon bg-light-primary text-primary mt-1" style="min-width:40px;">
                                        <i class="bi bi-chat-quote-fill"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2" style="min-width: 0;">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-0 text-truncate" title="<?= esc($msg['subject']) ?>">
                                                <?= esc($msg['subject']) ?>
                                            </h6>
                                            <small class="text-muted ms-2"
                                                style="white-space:nowrap;"><?= date('d/m', strtotime($msg['created_at'])) ?></small>
                                        </div>
                                        <p class="small text-muted mb-1 text-truncate"><?= esc($msg['name']) ?></p>
                                        <p class="small text-secondary mb-1 text-truncate" style="opacity:0.8;">
                                            <?= substr(strip_tags($msg['message']), 0, 50) ?>...
                                        </p>
                                        <a href="<?= url_to('pesan.sendEmail', $msg['id']) ?>"
                                            class="text-decoration-none small fw-bold">Balas <i
                                                class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">Belum ada aspirasi masuk.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Widget Recent Docs -->
            <div class="card-widget mb-4">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title">Dokumen Terbaru</h5>
                    <a href="<?= url_to('document.index') ?>" class="btn btn-sm btn-light">Arsip</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recentDocs)): ?>
                        <?php foreach ($recentDocs as $doc): ?>
                            <?php
                            $ext = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION));
                            $iconClass = 'bi-file-earmark-text';
                            $bgClass = 'bg-light-secondary';
                            $textClass = 'text-secondary';
                            if (in_array($ext, ['pdf'])) {
                                $iconClass = 'bi-file-pdf';
                                $bgClass = 'bg-light-danger';
                                $textClass = 'text-danger';
                            } elseif (in_array($ext, ['doc', 'docx'])) {
                                $iconClass = 'bi-file-word';
                                $bgClass = 'bg-light-primary';
                                $textClass = 'text-primary';
                            }
                            ?>
                            <div class="widget-list-item">
                                <div class="d-flex align-items-center w-100">
                                    <div class="widget-icon <?= $bgClass ?> <?= $textClass ?>">
                                        <i class="bi <?= $iconClass ?>"></i>
                                    </div>
                                    <div class="flex-grow-1" style="min-width:0;">
                                        <h6 class="mb-0 text-truncate" title="<?= esc($doc['judul']) ?>">
                                            <?= esc($doc['judul']) ?>
                                        </h6>
                                        <div class="small text-muted">
                                            <?= $doc['kategori'] ?? 'Umum' ?> •
                                            <?= date('d M', strtotime($doc['created_at'])) ?>
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-building me-1"></i><?= esc($doc['user_name'] ?? 'Organisasi') ?>
                                        </div>
                                    </div>
                                    <!-- Download button removed as requested -->
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">Belum ada dokumen.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Spacing to prevent footer overlap -->
<div class="mb-5 pb-5"></div>

<!-- Include Chart Logic (same as original, if present) -->
<?php if ($showStatistik && !empty($totalSuaras)): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var ctx = document.getElementById("chart-voting").getContext("2d");
            var votingData = <?= json_encode($totalSuaras) ?>;

            var labels = [];
            var datas = [];

            // Prepare data matching voting.php logic
            for (var key in votingData) {
                if (votingData.hasOwnProperty(key)) {
                    // Bersihkan nama kandidat
                    var cleanLabel = key.split('-')[0].trim();
                    cleanLabel = cleanLabel.replace('Total suara:', '').trim();
                    labels.push(cleanLabel);
                    datas.push(votingData[key]);
                }
            }

            // Define colors (Same as voting.php)
            var backgroundColors = [
                '#980517', '#4A6FDC', '#2E8B57', '#FF8C00', '#9932CC',
                '#FF6347', '#20B2AA', '#FFD700', '#9370DB', '#3CB371',
                '#FF4500', '#4682B4', '#32CD32', '#DA70D6', '#F08080',
                '#1E90FF', '#9ACD32', '#BA55D3', '#5F9EA0'
            ];

            // Hash function for consistent colors
            function hashString(str) {
                var hash = 0;
                for (var i = 0; i < str.length; i++) {
                    var char = str.charCodeAt(i);
                    hash = ((hash << 5) - hash) + char;
                    hash = hash & hash;
                }
                return Math.abs(hash);
            }

            var labelColorMap = {};
            labels.forEach(function (label) {
                if (!labelColorMap[label]) {
                    var hash = hashString(label);
                    var colorIndex = hash % backgroundColors.length;
                    labelColorMap[label] = backgroundColors[colorIndex];
                }
            });

            var pieColors = labels.map(function (label) {
                return labelColorMap[label] || backgroundColors[0];
            });

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: datas,
                        backgroundColor: pieColors,
                        borderColor: '#ffffff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { size: 12, weight: 'bold' }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    var value = context.raw;
                                    var total = context.chart._metasets[context.datasetIndex].total;
                                    var percentage = Math.round((value / total) * 100) + '%';
                                    return label + value + ' (' + percentage + ')';
                                }
                            }
                        }
                    },
                    cutout: '30%'
                }
            });
        });
    </script>
<?php endif; ?>

<?= $this->endSection() ?>