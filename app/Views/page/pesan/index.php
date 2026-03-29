<?= $this->extend('components/layouts/app') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Set default timezone ke Asia/Jakarta untuk semua operasi date
date_default_timezone_set('Asia/Jakarta');

// Helper function untuk format tanggal dengan timezone Asia/Jakarta
function formatDateTimeIndonesia($datetimeString)
{
    try {
        // Data dari database MySQL sudah dalam timezone Asia/Jakarta
        // (karena MySQL timezone sudah di-set ke +07:00 di BaseController)
        // Jadi kita tidak perlu konversi timezone lagi, hanya format saja

        // Buat DateTime dari string database (tanpa timezone, akan menggunakan default)
        $dateTime = new \DateTime($datetimeString);

        // Format langsung tanpa konversi timezone
        return $dateTime->format('d/m/Y H:i');
    } catch (\Exception $e) {
        // Fallback: gunakan date() dengan timezone yang sudah di-set
        return date('d/m/Y H:i', strtotime($datetimeString));
    }
}
?>
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

    /* Responsive Styles untuk Aspirasi Index */
    .pesan-card-view {
        display: none;
    }

    .pesan-table-view {
        display: block;
    }

    .pesan-card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .pesan-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }

    .pesan-card-title {
        font-weight: 600;
        color: #495057;
        margin: 0;
    }

    .pesan-card-body {
        display: grid;
        gap: 0.5rem;
    }

    .pesan-card-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .pesan-card-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
    }

    .pesan-card-value {
        font-size: 0.875rem;
        color: #212529;
        word-break: break-word;
    }

    .pesan-card-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #e9ecef;
    }

    .pesan-card-actions .btn {
        flex: 1;
        font-size: 0.875rem;
    }

    .search-form-responsive {
        width: 100%;
    }

    .search-form-responsive .form-control,
    .search-form-responsive .form-select {
        flex: 1;
        min-width: 0;
    }

    /* Tablet */
    @media (max-width: 991.98px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .search-form-responsive {
            width: 100%;
            margin-top: 1rem;
        }

        .search-form-responsive .d-flex {
            flex-wrap: wrap;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #table1 {
            min-width: 800px;
        }

        #table1 th,
        #table1 td {
            font-size: 0.875rem;
            padding: 0.5rem;
        }

        .pesan-card-actions .btn {
            font-size: 0.8125rem;
            padding: 0.375rem 0.75rem;
        }
    }

    /* Mobile */
    @media (max-width: 767.98px) {
        .page-title h3 {
            font-size: 1.5rem;
        }

        .page-title .text-subtitle {
            font-size: 0.875rem;
        }

        .card-header h4 {
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }

        .search-form-responsive {
            width: 100%;
        }

        .search-form-responsive .d-flex {
            flex-direction: column;
            gap: 0.5rem;
        }

        .search-form-responsive .form-control,
        .search-form-responsive .form-select,
        .search-form-responsive .btn {
            width: 100%;
            min-width: 0;
        }

        /* Hide table, show card view */
        .pesan-table-view {
            display: none;
        }

        .pesan-card-view {
            display: block;
        }

        .table-responsive {
            display: none;
        }
    }

    /* Small Mobile */
    @media (max-width: 575.98px) {
        .page-heading {
            padding: 0.5rem;
        }

        .section {
            padding: 0.5rem 0;
        }

        .card {
            margin-bottom: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .pesan-card {
            padding: 0.75rem;
        }

        .pesan-card-title {
            font-size: 0.875rem;
        }

        .pesan-card-value {
            font-size: 0.8125rem;
        }

        .pesan-card-actions {
            flex-direction: column;
        }

        .pesan-card-actions .btn {
            width: 100%;
        }

        .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
    }

    /* Styling untuk tabel dengan border tebal */
    #table1 {
        border: 2px solid #dee2e6 !important;
    }

    #table1 th,
    #table1 td {
        border: 1px solid #dee2e6 !important;
    }

    #table1 thead th {
        border-bottom: 2px solid #dee2e6 !important;
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
                    <input type="text" placeholder="Search" aria-label="Search" id="modernSearchInput" />
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
                        <a class="dropdown-item" href="<?= url_to('settings.index') ?>">
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

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?= $title ?></h3>
                <p class="text-subtitle text-muted">Daftar Aspirasi Mahasiswa</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title">Data Aspirasi</h4>
                        <div class="search-form-responsive">
                            <!-- Search Form -->
                            <form method="GET" action="<?= url_to('pesan.index') ?>" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Cari..."
                                    value="<?= esc($search ?? '') ?>">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="unread" <?= ($status ?? '') == 'unread' ? 'selected' : '' ?>>Belum
                                        Dibaca</option>
                                    <option value="read" <?= ($status ?? '') == 'read' ? 'selected' : '' ?>>Sudah Dibaca
                                    </option>
                                    <option value="replied" <?= ($status ?? '') == 'replied' ? 'selected' : '' ?>>Sudah
                                        Dibalas</option>
                                </select>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> <span class="d-none d-md-inline">Cari</span>
                                </button>
                                <?php if (!empty($search) || !empty($status)): ?>
                                    <a href="<?= url_to('pesan.index') ?>" class="btn btn-secondary">
                                        <i class="bi bi-x"></i> <span class="d-none d-md-inline">Reset</span>
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Desktop/Tablet Table View -->
                <div class="table-responsive pesan-table-view">
                    <table class="table table-striped table-bordered" id="table1" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Pesan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pesans)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                                <?php foreach ($pesans as $pesan): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($pesan['name']) ?></td>
                                        <td><?= esc($pesan['email']) ?></td>
                                        <td><?= esc($pesan['subject']) ?></td>
                                        <td>
                                            <?php
                                            $message = esc($pesan['message']);
                                            // Pecah pesan menjadi array kata-kata
                                            $words = explode(' ', $message);
                                            // Ambil hanya 10 kata pertama
                                            if (count($words) > 10) {
                                                $limitedWords = array_slice($words, 0, 10);
                                                echo implode(' ', $limitedWords) . '...';
                                            } else {
                                                echo $message;
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            switch ($pesan['status']) {
                                                case 'unread':
                                                    $statusClass = 'badge bg-danger';
                                                    $statusText = 'Belum Dibaca';
                                                    break;
                                                case 'read':
                                                    $statusClass = 'badge bg-warning';
                                                    $statusText = 'Sudah Dibaca';
                                                    break;
                                                case 'replied':
                                                    $statusClass = 'badge bg-success';
                                                    $statusText = 'Sudah Dibalas';
                                                    break;
                                            }
                                            ?>
                                            <span class="<?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                        <td><?= formatDateTimeIndonesia($pesan['created_at']) ?></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?= url_to('pesan.sendEmail', $pesan['id']) ?>"
                                                    class="btn btn-primary btn-sm" title="Balas Via Email">
                                                    <i class="bi bi-envelope"></i> <span class="d-none d-lg-inline">Balas</span>
                                                </a>
                                                <form action="<?= url_to('pesan.delete', $pesan['id']) ?>" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="pesan-card-view">
                    <?php if (empty($pesans)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">Tidak ada data</p>
                        </div>
                    <?php else: ?>
                        <?php $no = ($pager->getCurrentPage() - 1) * 10 + 1; ?>
                        <?php foreach ($pesans as $pesan): ?>
                            <div class="pesan-card">
                                <div class="pesan-card-header">
                                    <h6 class="pesan-card-title">#<?= $no++ ?></h6>
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch ($pesan['status']) {
                                        case 'unread':
                                            $statusClass = 'badge bg-danger';
                                            $statusText = 'Belum Dibaca';
                                            break;
                                        case 'read':
                                            $statusClass = 'badge bg-warning';
                                            $statusText = 'Sudah Dibaca';
                                            break;
                                        case 'replied':
                                            $statusClass = 'badge bg-success';
                                            $statusText = 'Sudah Dibalas';
                                            break;
                                    }
                                    ?>
                                    <span class="<?= $statusClass ?>"><?= $statusText ?></span>
                                </div>
                                <div class="pesan-card-body">
                                    <div class="pesan-card-item">
                                        <span class="pesan-card-label">Nama</span>
                                        <span class="pesan-card-value"><?= esc($pesan['name']) ?></span>
                                    </div>
                                    <div class="pesan-card-item">
                                        <span class="pesan-card-label">Email</span>
                                        <span class="pesan-card-value"><?= esc($pesan['email']) ?></span>
                                    </div>
                                    <div class="pesan-card-item">
                                        <span class="pesan-card-label">Subject</span>
                                        <span class="pesan-card-value"><?= esc($pesan['subject']) ?></span>
                                    </div>
                                    <div class="pesan-card-item">
                                        <span class="pesan-card-label">Pesan</span>
                                        <span class="pesan-card-value"><?= esc($pesan['message']) ?></span>
                                    </div>
                                    <div class="pesan-card-item">
                                        <span class="pesan-card-label">Tanggal</span>
                                        <span
                                            class="pesan-card-value"><?= formatDateTimeIndonesia($pesan['created_at']) ?></span>
                                    </div>
                                </div>
                                <div class="pesan-card-actions">
                                    <a href="<?= url_to('pesan.sendEmail', $pesan['id']) ?>" class="btn btn-primary btn-sm">
                                        <i class="bi bi-envelope"></i> Balas
                                    </a>
                                    <form action="<?= url_to('pesan.delete', $pesan['id']) ?>" method="POST" class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pager->hasMore() || $pager->getCurrentPage() > 1): ?>
                    <div class="d-flex justify-content-center mt-3">
                        <?= $pager->links('default', 'pagination') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>