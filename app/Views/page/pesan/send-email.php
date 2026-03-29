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

    /* Responsive Styles untuk Send Email */
    .alert-info {
        font-size: 0.875rem;
    }

    .alert-heading {
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        font-size: 0.875rem;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 150px;
    }

    /* Tablet */
    @media (max-width: 991.98px) {
        .page-title h3 {
            font-size: 1.5rem;
        }

        .page-title .text-subtitle {
            font-size: 0.875rem;
        }

        .card-header h5 {
            font-size: 1.125rem;
        }

        .alert-info {
            padding: 1rem;
        }

        .alert-heading {
            font-size: 0.9375rem;
        }

        .alert-info p {
            font-size: 0.8125rem;
        }

        textarea.form-control {
            min-height: 200px;
        }
    }

    /* Mobile */
    @media (max-width: 767.98px) {
        .page-heading {
            padding: 0.5rem;
        }

        .page-title h3 {
            font-size: 1.25rem;
        }

        .page-title .text-subtitle {
            font-size: 0.8125rem;
        }

        .breadcrumb {
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        .section {
            padding: 0.5rem 0;
        }

        .card {
            margin-bottom: 1rem;
        }

        .card-header {
            padding: 0.75rem 1rem;
        }

        .card-header h5 {
            font-size: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .alert-info {
            padding: 0.75rem;
            margin-bottom: 1rem;
        }

        .alert-heading {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .alert-info hr {
            margin: 0.5rem 0;
        }

        .alert-info p {
            font-size: 0.8125rem;
            margin-bottom: 0.5rem;
        }

        .alert-info .row {
            margin: 0;
        }

        .alert-info .col-md-6 {
            padding: 0.25rem 0;
        }

        .form-label {
            font-size: 0.875rem;
        }

        .form-control,
        .form-select {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }

        textarea.form-control {
            min-height: 180px;
            font-size: 0.875rem;
        }

        .form-text {
            font-size: 0.75rem;
        }

        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.5rem !important;
        }

        .btn {
            width: 100%;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }

        .btn i {
            margin-right: 0.5rem;
        }
    }

    /* Small Mobile */
    @media (max-width: 575.98px) {
        .page-heading {
            padding: 0.25rem;
        }

        .page-title h3 {
            font-size: 1.125rem;
        }

        .page-title .text-subtitle {
            font-size: 0.75rem;
        }

        .breadcrumb {
            font-size: 0.6875rem;
        }

        .breadcrumb-item {
            padding: 0.25rem 0;
        }

        .card-body {
            padding: 0.75rem;
        }

        .alert-info {
            padding: 0.625rem;
        }

        .alert-heading {
            font-size: 0.8125rem;
        }

        .alert-info p {
            font-size: 0.75rem;
        }

        .form-label {
            font-size: 0.8125rem;
        }

        .form-control,
        .form-select {
            font-size: 0.8125rem;
            padding: 0.5rem;
        }

        textarea.form-control {
            min-height: 150px;
            font-size: 0.8125rem;
        }

        .form-text {
            font-size: 0.6875rem;
        }

        .btn {
            font-size: 0.8125rem;
            padding: 0.5rem;
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
                <p class="text-subtitle text-muted">Balas Aspirasi Mahasiswa via Email</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url_to('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= url_to('pesan.index') ?>">Aspirasi</a></li>
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

                <!-- Info Pesan Asli -->
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informasi Pesan Asli</h6>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Nama:</strong> <?= esc($pesan['name']) ?></p>
                            <p class="mb-2"><strong>Email:</strong> <?= esc($pesan['email']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Subject:</strong> <?= esc($pesan['subject']) ?></p>
                            <p class="mb-2"><strong>Tanggal:</strong>
                                <?= formatDateTimeIndonesia($pesan['created_at']) ?></p>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-1"><strong>Pesan:</strong></p>
                    <p class="mb-0" style="white-space: pre-wrap; word-break: break-word;"><?= esc($pesan['message']) ?>
                    </p>
                </div>

                <!-- Form Balas Email -->
                <form action="<?= url_to('pesan.processReply') ?>" method="POST" id="replyEmailForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="pesan_id" value="<?= $pesan['id'] ?>">

                    <div class="mb-3">
                        <label for="to_email" class="form-label">Kepada (Email)</label>
                        <input type="email" class="form-control" id="to_email" value="<?= esc($pesan['email']) ?>"
                            readonly>
                        <small class="form-text text-muted">Email penerima balasan</small>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" value="Re: <?= esc($pesan['subject']) ?>"
                            readonly>
                        <small class="form-text text-muted">Subject email balasan</small>
                    </div>

                    <div class="mb-3">
                        <label for="reply_message" class="form-label">Pesan Balasan <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="reply_message" name="reply_message" rows="10"
                            placeholder="Tulis pesan balasan Anda di sini..."
                            required><?= old('reply_message') ?></textarea>
                        <small class="form-text text-muted">Pesan ini akan dikirim ke email
                            <?= esc($pesan['email']) ?></small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-send"></i> <span id="submitText">Kirim Email</span>
                        </button>
                        <a href="<?= url_to('pesan.index') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('replyEmailForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const replyMessage = document.getElementById('reply_message').value.trim();

                // Validasi
                if (!replyMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pesan balasan tidak boleh kosong!',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }

                // Tampilkan loading animation
                Swal.fire({
                    title: 'Mengirim Email...',
                    html: 'Mohon tunggu, email sedang dikirim',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Disable button
                submitBtn.disabled = true;
                submitText.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';

                // Submit form secara asinkron
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // If not JSON, might be redirect or HTML
                            if (response.redirected) {
                                return { success: true, redirect: response.url };
                            }
                            return response.text().then(text => {
                                try {
                                    return JSON.parse(text);
                                } catch {
                                    return { success: false, message: 'Terjadi kesalahan' };
                                }
                            });
                        }
                    })
                    .then(data => {
                        if (data.success) {
                            // Tampilkan success animation
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Email balasan berhasil dikirim',
                                confirmButtonColor: '#28a745',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: true,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Redirect ke halaman index
                                window.location.href = data.redirect || '<?= url_to('pesan.index') ?>';
                            });

                            // Auto redirect setelah 2 detik jika user tidak klik OK
                            setTimeout(() => {
                                window.location.href = data.redirect || '<?= url_to('pesan.index') ?>';
                            }, 2000);
                        } else {
                            // Error
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message || 'Gagal mengirim email. Silakan coba lagi.',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            });

                            // Enable button kembali
                            submitBtn.disabled = false;
                            submitText.innerHTML = '<i class="bi bi-send"></i> Kirim Email';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat mengirim email. Silakan coba lagi.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });

                        // Enable button kembali
                        submitBtn.disabled = false;
                        submitText.innerHTML = '<i class="bi bi-send"></i> Kirim Email';
                    });
            });
        }
    });
</script>
<?= $this->endSection() ?>