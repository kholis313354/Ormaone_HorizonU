<?= $this->extend('components/layouts/app') ?>

<?= $this->section('title') ?>
<?= $title ?>
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
        min-width: 2.5rem;
        min-height: 2.5rem;
        border-radius: 50%;
        background: #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        overflow: hidden;
        flex-shrink: 0;
        position: relative;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        position: absolute;
        top: 0;
        left: 0;
    }

    .user-avatar i {
        font-size: 1.5rem;
        /* Ukuran sedang */
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
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

    .settings-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #fff;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .settings-section h4 {
        margin-bottom: 1.5rem;
        color: #1f2937;
        font-weight: 600;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.75rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #374151;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }

    .form-group input:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .btn-update {
        background-color: #dc2626;
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-update:hover {
        background-color: #b91c1c;
    }

    .form-group small {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.75rem;
    }

    .password-strength.weak {
        color: #dc2626;
    }

    .password-strength.medium {
        color: #f59e0b;
    }

    .password-strength.strong {
        color: #10b981;
    }

    .info-box {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 1rem;
        margin-top: 1rem;
        border-radius: 0.25rem;
    }

    .info-box p {
        margin: 0;
        color: #1e40af;
        font-size: 0.875rem;
    }

    .warning-box {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 1rem;
        margin-top: 1rem;
        border-radius: 0.25rem;
    }

    .warning-box p {
        margin: 0;
        color: #92400e;
        font-size: 0.875rem;
    }

    /* Responsive untuk tablet */
    @media (max-width: 991.98px) {
        .modern-search {
            width: 280px;
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

        .settings-section {
            padding: 1rem;
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

                <p class="text-subtitle text-muted"></p>
            </div>
        </div>
    </div>

    <section class="section">
        <!-- Statistik Fakultas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Distribusi Berita Per Fakultas</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($facultyStats as $stat): ?>
                                <div class="col-md-3 col-sm-6">
                                    <div class="card stat-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="stat-info">
                                                    <h6 class="text-muted"><?= $stat['nama_fakultas'] ?></h6>
                                                    <h4 class="font-extrabold mb-0"><?= $stat['count'] ?></h4>
                                                </div>
                                                <div class="stat-icon">
                                                    <div class="avatar bg-light-primary p-50 m-0">
                                                        <div class="avatar-content">
                                                            <i class="bi bi-building text-primary"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Statistik Fakultas -->

        <!-- Tabel Berita -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Semua Data Blog</h4>
                    <a href="<?= site_url('page/berita/create') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Berita
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table1" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama Kegiatan</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Fakultas</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($beritas as $i => $berita): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td>
                                        <?php if (!empty($berita['gambar'])): ?>
                                            <img src="<?= base_url('uploads/berita/' . $berita['gambar']) ?>"
                                                alt="<?= esc($berita['nama_kegiatan']) ?>"
                                                style="width: 100px; height: 70px; object-fit: cover;" class="img-thumbnail">
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $berita['nama_kegiatan'] ?></td>
                                    <td><?= $berita['kategori'] ?></td>
                                    <td><?= substr($berita['deskripsi1'], 0, 50) ?>...</td>
                                    <td><?= $berita['nama_fakultas'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($berita['tanggal'])) ?></td>
                                    <td>
                                        <a href="<?= site_url('page/berita/edit/' . $berita['id']) ?>"
                                            class="btn btn-sm btn-warning col-xs-2">Edit</a>
                                        <form action="<?= site_url('page/berita/delete/' . $berita['id']) ?>" method="post"
                                            class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End Tabel Berita -->
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .stat-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-info h6 {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .stat-info h4 {
        font-weight: 700;
    }

    .progress {
        border-radius: 10px;
    }

    .progress-bar {
        border-radius: 10px;
    }

    .avatar {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar i {
        font-size: 1.5rem;
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
<?= $this->endSection() ?>