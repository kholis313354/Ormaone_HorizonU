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

    /* Dark Mode untuk Search */
    body.dark-mode .modern-search,
    [data-theme="dark"] .modern-search,
    [data-bs-theme="dark"] .modern-search {
        background: #1f2937;
        border-color: #374151;
    }

    body.dark-mode .modern-search input,
    [data-theme="dark"] .modern-search input,
    [data-bs-theme="dark"] .modern-search input {
        color: #f9fafb;
    }

    body.dark-mode .modern-search input::placeholder,
    [data-theme="dark"] .modern-search input::placeholder,
    [data-bs-theme="dark"] .modern-search input::placeholder {
        color: #6b7280;
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

    /* Dark Mode untuk Search Shortcut */
    body.dark-mode .modern-search .search-shortcut,
    [data-theme="dark"] .modern-search .search-shortcut,
    [data-bs-theme="dark"] .modern-search .search-shortcut {
        background: #374151;
        border-color: #4b5563;
        color: #9ca3af;
    }

    body.dark-mode .modern-search .search-shortcut:hover,
    [data-theme="dark"] .modern-search .search-shortcut:hover,
    [data-bs-theme="dark"] .modern-search .search-shortcut:hover {
        background: #4b5563;
        border-color: #6b7280;
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
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .settings-section h4 {
        margin-bottom: 1.5rem;
        color: #1f2937;
        font-weight: 600;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.75rem;
        transition: color 0.3s ease, border-color 0.3s ease;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #374151;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background-color: #fff;
        color: #111827;
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    /* Dark Mode Styles */
    body.dark-mode .settings-section,
    [data-theme="dark"] .settings-section,
    [data-bs-theme="dark"] .settings-section {
        background: #1f2937;
        border-color: #374151;
    }

    body.dark-mode .settings-section h4,
    [data-theme="dark"] .settings-section h4,
    [data-bs-theme="dark"] .settings-section h4 {
        color: #f9fafb;
        border-bottom-color: #374151;
    }

    body.dark-mode .form-group label,
    [data-theme="dark"] .form-group label,
    [data-bs-theme="dark"] .form-group label {
        color: #e5e7eb;
    }

    body.dark-mode .form-group input,
    [data-theme="dark"] .form-group input,
    [data-bs-theme="dark"] .form-group input {
        background-color: #111827;
        border-color: #4b5563;
        color: #f9fafb;
    }

    body.dark-mode .form-group input:focus,
    [data-theme="dark"] .form-group input:focus,
    [data-bs-theme="dark"] .form-group input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.2);
    }

    body.dark-mode .form-group input::placeholder,
    [data-theme="dark"] .form-group input::placeholder,
    [data-bs-theme="dark"] .form-group input::placeholder {
        color: #9ca3af;
    }

    body.dark-mode .form-group small,
    [data-theme="dark"] .form-group small,
    [data-bs-theme="dark"] .form-group small {
        color: #9ca3af;
    }

    /* Dark Mode untuk Card */
    body.dark-mode .card,
    [data-theme="dark"] .card,
    [data-bs-theme="dark"] .card {
        background-color: #1f2937;
        border-color: #374151;
        color: #f9fafb;
    }

    body.dark-mode .card-header,
    [data-theme="dark"] .card-header,
    [data-bs-theme="dark"] .card-header {
        background-color: #111827;
        border-bottom-color: #374151;
        color: #f9fafb;
    }

    body.dark-mode .card-body,
    [data-theme="dark"] .card-body,
    [data-bs-theme="dark"] .card-body {
        color: #f9fafb;
    }

    body.dark-mode .card-title,
    [data-theme="dark"] .card-title,
    [data-bs-theme="dark"] .card-title {
        color: #f9fafb;
    }

    /* Dark Mode untuk Page Heading dan Breadcrumb */
    body.dark-mode .page-heading h3,
    [data-theme="dark"] .page-heading h3,
    [data-bs-theme="dark"] .page-heading h3 {
        color: #f9fafb;
    }

    body.dark-mode .breadcrumb-item a,
    [data-theme="dark"] .breadcrumb-item a,
    [data-bs-theme="dark"] .breadcrumb-item a {
        color: #9ca3af;
    }

    body.dark-mode .breadcrumb-item.active,
    [data-theme="dark"] .breadcrumb-item.active,
    [data-bs-theme="dark"] .breadcrumb-item.active {
        color: #f9fafb;
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
        transition: color 0.3s ease;
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

    /* Dark Mode untuk Password Strength */
    body.dark-mode .password-strength.weak,
    [data-theme="dark"] .password-strength.weak,
    [data-bs-theme="dark"] .password-strength.weak {
        color: #f87171;
    }

    body.dark-mode .password-strength.medium,
    [data-theme="dark"] .password-strength.medium,
    [data-bs-theme="dark"] .password-strength.medium {
        color: #fbbf24;
    }

    body.dark-mode .password-strength.strong,
    [data-theme="dark"] .password-strength.strong,
    [data-bs-theme="dark"] .password-strength.strong {
        color: #34d399;
    }

    .info-box {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 1rem;
        margin-top: 1rem;
        border-radius: 0.25rem;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .info-box p {
        margin: 0;
        color: #1e40af;
        font-size: 0.875rem;
        transition: color 0.3s ease;
    }

    .warning-box {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 1rem;
        margin-top: 1rem;
        border-radius: 0.25rem;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .warning-box p {
        margin: 0;
        color: #92400e;
        font-size: 0.875rem;
        transition: color 0.3s ease;
    }

    /* Dark Mode untuk Info Box dan Warning Box */
    body.dark-mode .info-box,
    [data-theme="dark"] .info-box,
    [data-bs-theme="dark"] .info-box {
        background: #1e3a5f;
        border-left-color: #60a5fa;
    }

    body.dark-mode .info-box p,
    [data-theme="dark"] .info-box p,
    [data-bs-theme="dark"] .info-box p {
        color: #93c5fd;
    }

    body.dark-mode .warning-box,
    [data-theme="dark"] .warning-box,
    [data-bs-theme="dark"] .warning-box {
        background: #78350f;
        border-left-color: #fbbf24;
    }

    body.dark-mode .warning-box p,
    [data-theme="dark"] .warning-box p,
    [data-bs-theme="dark"] .warning-box p {
        color: #fcd34d;
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
                <h3><?= $title ?></h3>
                <p class="text-subtitle text-muted"></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"><?= $title ?></h5>
            </div>
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>

                <!-- Ubah Name -->
                <div class="settings-section">
                    <h4><i class="fas fa-user me-2"></i>Ubah Nama</h4>
                    <form action="<?= url_to('settings.updateName') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="<?= old('name', $user['name']) ?>" required>
                        </div>
                        <button type="submit" class="btn-update">
                            <i class="fas fa-save me-2"></i>Simpan Nama
                        </button>
                    </form>
                </div>

                <!-- Ubah Email -->
                <div class="settings-section">
                    <h4><i class="fas fa-envelope me-2"></i>Ubah Email</h4>
                    <form action="<?= url_to('settings.updateEmail') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="email">Email Baru</label>
                            <input type="email" id="email" name="email" value="<?= old('email', $user['email']) ?>"
                                required>
                        </div>
                        <button type="submit" class="btn-update">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Email Verifikasi
                        </button>
                        <div class="info-box">
                            <p><i class="fas fa-info-circle me-2"></i>Email verifikasi akan dikirim ke email baru Anda.
                                Silakan cek email dan klik link verifikasi untuk mengaktifkan email baru.</p>
                        </div>
                        <?php if (session()->get('email_verification')): ?>
                            <div class="warning-box">
                                <p><i class="fas fa-exclamation-triangle me-2"></i>Email verifikasi telah dikirim. Silakan
                                    cek email Anda untuk menyelesaikan proses perubahan email.</p>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Ubah Password -->
                <div class="settings-section">
                    <h4><i class="fas fa-key me-2"></i>Ubah Password</h4>
                    <form action="<?= url_to('settings.updatePassword') ?>" method="post" id="passwordForm">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="old_password">Password Lama</label>
                            <input type="password" id="old_password" name="old_password" required
                                autocomplete="current-password">
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-lock me-1"></i>
                                Masukkan password lama Anda untuk verifikasi
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" id="password" name="password" required minlength="8"
                                autocomplete="new-password">
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle me-1"></i>
                                Password harus minimal 8 karakter, mengandung huruf kecil, huruf besar, angka, dan
                                karakter khusus (@$!%*?&)
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirm" name="password_confirm" required
                                autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn-update">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Email Verifikasi
                        </button>
                        <div class="info-box">
                            <p><i class="fas fa-info-circle me-2"></i>Email verifikasi akan dikirim ke
                                <strong><?= esc($user['email']) ?></strong>. Silakan cek email Anda dan klik link
                                verifikasi untuk menyelesaikan proses perubahan password.
                            </p>
                        </div>
                        <?php if (session()->get('password_change')): ?>
                            <div class="warning-box">
                                <p><i class="fas fa-exclamation-triangle me-2"></i>Email verifikasi perubahan password telah
                                    dikirim. Silakan cek email Anda untuk menyelesaikan proses perubahan password.</p>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const oldPasswordInput = document.getElementById('old_password');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirm');
        const passwordForm = document.getElementById('passwordForm');

        // Validasi real-time untuk password strength
        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                const password = this.value;
                const strengthIndicator = document.getElementById('password-strength');

                if (!strengthIndicator) {
                    const indicator = document.createElement('div');
                    indicator.id = 'password-strength';
                    indicator.className = 'password-strength';
                    this.parentElement.appendChild(indicator);
                }

                const indicator = document.getElementById('password-strength');
                const strength = checkPasswordStrength(password);

                if (password.length === 0) {
                    indicator.textContent = '';
                    indicator.className = 'password-strength';
                } else {
                    indicator.textContent = 'Kekuatan password: ' + strength.text;
                    indicator.className = 'password-strength ' + strength.class;
                }
            });
        }

        // Validasi konfirmasi password
        if (passwordConfirmInput && passwordInput) {
            passwordConfirmInput.addEventListener('input', function () {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Konfirmasi password tidak cocok');
                } else {
                    this.setCustomValidity('');
                }
            });
        }

        // Validasi form sebelum submit
        if (passwordForm) {
            passwordForm.addEventListener('submit', function (e) {
                const oldPassword = oldPasswordInput.value;
                const password = passwordInput.value;
                const passwordConfirm = passwordConfirmInput.value;

                // Validasi password lama harus diisi
                if (!oldPassword || oldPassword.trim() === '') {
                    e.preventDefault();
                    alert('Password lama harus diisi');
                    return false;
                }

                // Validasi password baru tidak boleh sama dengan password lama
                if (oldPassword === password) {
                    e.preventDefault();
                    alert('Password baru harus berbeda dengan password lama');
                    return false;
                }

                // Validasi password strength
                const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/;

                if (!passwordRegex.test(password) || password.length < 8) {
                    e.preventDefault();
                    alert('Password harus minimal 8 karakter dan mengandung: huruf kecil, huruf besar, angka, dan karakter khusus (@$!%*?&)');
                    return false;
                }

                if (password !== passwordConfirm) {
                    e.preventDefault();
                    alert('Konfirmasi password tidak cocok');
                    return false;
                }
            });
        }

        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];

            if (password.length >= 8) strength++;
            else feedback.push('minimal 8 karakter');

            if (/[a-z]/.test(password)) strength++;
            else feedback.push('huruf kecil');

            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('huruf besar');

            if (/\d/.test(password)) strength++;
            else feedback.push('angka');

            if (/[@$!%*?&]/.test(password)) strength++;
            else feedback.push('karakter khusus');

            if (strength <= 2) {
                return { text: 'Lemah', class: 'weak' };
            } else if (strength <= 4) {
                return { text: 'Sedang', class: 'medium' };
            } else {
                return { text: 'Kuat', class: 'strong' };
            }
        }
    });
</script>

<?= $this->endSection() ?>