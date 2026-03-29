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
                <h3>Tambah Sertifikat</h3>
                <p class="text-subtitle text-muted">Form tambah sertifikat digital</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('sertifikat') ?>">E-Sertifikat</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Sertifikat</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <!-- Tabs untuk Single dan Batch Import -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="sertifikatTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="single-tab" data-bs-toggle="tab" data-bs-target="#single"
                            type="button" role="tab">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Sertifikat (Single)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="batch-tab" data-bs-toggle="tab" data-bs-target="#batch"
                            type="button" role="tab">
                            <i class="fas fa-upload me-2"></i>Import Batch (Excel/CSV)
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="sertifikatTabContent">
                    <!-- Tab Single -->
                    <div class="tab-pane fade show active" id="single" role="tabpanel">
                        <form action="<?= base_url('sertifikat/save') ?>" method="post" enctype="multipart/form-data"
                            id="singleForm" name="singleForm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="empty_certificate" value="1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_kegiatan">Nama Kegiatan</label>
                                        <input type="text"
                                            class="form-control <?= session('errors.nama_kegiatan') ? 'is-invalid' : '' ?>"
                                            id="nama_kegiatan" name="nama_kegiatan" value="<?= old('nama_kegiatan') ?>"
                                            required>
                                        <?php if (session('errors.nama_kegiatan')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.nama_kegiatan') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="nama_sertifikat_id">Nama Sertifikat</label>
                                        <select
                                            class="form-control <?= session('errors.nama_sertifikat_id') ? 'is-invalid' : '' ?>"
                                            id="nama_sertifikat_id" name="nama_sertifikat_id" required>
                                            <option value="">Pilih Nama Sertifikat</option>
                                            <?php foreach ($nama_sertifikat as $ns): ?>
                                                <option value="<?= $ns['id'] ?>" <?= old('nama_sertifikat_id') == $ns['id'] ? 'selected' : '' ?>>
                                                    <?= $ns['nama_sertifikat'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (session('errors.nama_sertifikat_id')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.nama_sertifikat_id') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="nama_penerima">Nama Penerima</label>
                                        <input type="text"
                                            class="form-control <?= session('errors.nama_penerima') ? 'is-invalid' : '' ?>"
                                            id="nama_penerima" name="nama_penerima" value="<?= old('nama_penerima') ?>"
                                            required>
                                        <?php if (session('errors.nama_penerima')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.nama_penerima') ?>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fakultas_id">Fakultas</label>
                                        <select
                                            class="form-control <?= session('errors.fakultas_id') ? 'is-invalid' : '' ?>"
                                            id="fakultas_id" name="fakultas_id" required>
                                            <option value="">Pilih Fakultas</option>
                                            <?php foreach ($fakultas as $f): ?>
                                                <option value="<?= $f['id'] ?>" <?= old('fakultas_id') == $f['id'] ? 'selected' : '' ?>>
                                                    <?= $f['nama_fakultas'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (session('errors.fakultas_id')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.fakultas_id') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="nim">NIM(jika Non Mahasiswa Gunakan Nama Panggilan)</label>
                                        <input type="text"
                                            class="form-control <?= session('errors.nim') ? 'is-invalid' : '' ?>"
                                            id="nim" name="nim" value="<?= old('nim') ?>" required>
                                        <?php if (session('errors.nim')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.nim') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="alert alert-light-warning border-warning">
                                        <div class="d-flex align-items-start gap-3 flex-column flex-sm-row">
                                            <div style="width: 200px; flex-shrink: 0;" class="mx-auto mx-sm-0">
                                                <img src="<?= base_url('dist/ormaone/Sertifikat.png') ?>"
                                                    alt="Contoh Sertifikat" class="img-fluid rounded border shadow-sm"
                                                    style="cursor: pointer;"
                                                    onclick="Swal.fire({imageUrl: this.src, imageAlt: 'Contoh Sertifikat', width: 800, padding: 0, showConfirmButton: false})">
                                                <div class="text-center mt-2 d-none d-sm-block">
                                                    <small class="text-muted fst-italic" style="font-size: 0.7rem;">Klik
                                                        untuk memperbesar</small>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="text-warning fw-bold"><i
                                                        class="fas fa-exclamation-triangle me-2"></i>Penting!</h6>
                                                <p class="mb-2 small text-dark">Pembuatan sertifikat <strong>tidak boleh
                                                        sembarangan</strong>. Pastikan desain Anda memiliki ruang kosong
                                                    untuk:</p>
                                                <ul class="mb-3 small text-dark ps-3">
                                                    <li><strong>Nama Penerima</strong> (biasanya di tengah)</li>
                                                    <li><strong>QR Code</strong> (verifikasi keaslian)</li>
                                                </ul>
                                                <div class="d-grid d-sm-block">
                                                    <a href="https://www.canva.com/design/DAG9Ks4kCvY/wuPq6VyLMlTdQxoeplFdSQ/edit?utm_content=DAG9Ks4kCvY&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton"
                                                        target="_blank" class="btn btn-sm btn-info text-white">
                                                        <i class="fas fa-palette me-2"></i>Buka Template Canva
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="file">File Sertifikat</label>
                                        <input type="file"
                                            class="form-control <?= session('errors.file') ? 'is-invalid' : '' ?>"
                                            id="file" name="file" required>
                                        <?php if (session('errors.file')): ?>
                                            <div class="invalid-feedback">
                                                <?= session('errors.file') ?>
                                            </div>
                                        <?php endif; ?>
                                        <small class="text-muted">Format: PDF, JPG, PNG (Maksimal 2MB). Rekomendasi
                                            ukuran: 1200x800 pixel dengan area kosong di bagian atas untuk nama dan
                                            kanan bawah untuk QR code.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="<?= url_to('sertifikat.index') ?>"
                                    class="btn btn-light-secondary me-2">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Batch Import -->
                    <div class="tab-pane fade" id="batch" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Petunjuk Import Batch:</strong>
                            <ol class="mb-0 mt-2">
                                <li>Download template Excel/CSV terlebih dahulu</li>
                                <li>Isi data sesuai template (nama kegiatan, nama sertifikat, nama penerima, fakultas,
                                    NIM)</li>
                                <li>Upload file Excel/CSV yang sudah diisi</li>
                                <li>Upload file sertifikat dasar (akan digunakan untuk semua sertifikat)</li>
                                <li>Klik "Import dan Generate Sertifikat"</li>
                            </ol>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <a href="<?= base_url('sertifikat/downloadTemplate') ?>" class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Download Template Excel/CSV
                                </a>
                            </div>
                        </div>

                        <form id="batchImportForm">
                            <?= csrf_field() ?>
                            <input type="hidden" id="csrf_token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            <meta name="csrf-token" content="<?= csrf_hash() ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="excel_file" class="form-label">
                                            <i class="fas fa-file-excel me-2"></i>File Excel/CSV
                                        </label>
                                        <input type="file" class="form-control" id="excel_file" accept=".xlsx,.xls,.csv"
                                            required>
                                        <small class="text-muted">Format: .xlsx, .xls, atau .csv</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-light-warning border-warning">
                                        <div class="d-flex align-items-start gap-3 flex-column flex-sm-row">
                                            <div style="width: 200px; flex-shrink: 0;" class="mx-auto mx-sm-0">
                                                <img src="<?= base_url('dist/ormaone/Sertifikat.png') ?>"
                                                    alt="Contoh Sertifikat" class="img-fluid rounded border shadow-sm"
                                                    style="cursor: pointer;"
                                                    onclick="Swal.fire({imageUrl: this.src, imageAlt: 'Contoh Sertifikat', width: 800, padding: 0, showConfirmButton: false})">
                                                <div class="text-center mt-2 d-none d-sm-block">
                                                    <small class="text-muted fst-italic" style="font-size: 0.7rem;">Klik
                                                        untuk memperbesar</small>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="text-warning fw-bold"><i
                                                        class="fas fa-exclamation-triangle me-2"></i>Penting!</h6>
                                                <p class="mb-2 small text-dark">Pembuatan sertifikat <strong>tidak boleh
                                                        sembarangan</strong>. Pastikan desain Anda memiliki ruang kosong
                                                    untuk:</p>
                                                <ul class="mb-3 small text-dark ps-3">
                                                    <li><strong>Nama Penerima</strong> (biasanya di tengah)</li>
                                                    <li><strong>QR Code</strong> (verifikasi keaslian)</li>
                                                </ul>
                                                <div class="d-grid d-sm-block">
                                                    <a href="https://www.canva.com/design/DAG9Ks4kCvY/wuPq6VyLMlTdQxoeplFdSQ/edit?utm_content=DAG9Ks4kCvY&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton"
                                                        target="_blank" class="btn btn-sm btn-info text-white">
                                                        <i class="fas fa-palette me-2"></i>Buka Template Canva
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="base_certificate_file" class="form-label">
                                            <i class="fas fa-file-image me-2"></i>File Sertifikat Dasar
                                        </label>
                                        <input type="file" class="form-control" id="base_certificate_file"
                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                        <small class="text-muted">File sertifikat yang akan digunakan untuk semua data
                                            (PDF, JPG, PNG, maks 2MB)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Checkbox dihapus - Generate QR Code dan Nama Penerima otomatis aktif -->

                            <!-- Preview Data -->
                            <div id="previewContainer" class="mt-4" style="display: none;">
                                <h5>Preview Data (<?= '<span id="previewCount">0</span>' ?> baris)</h5>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Kegiatan</th>
                                                <th>Nama Sertifikat</th>
                                                <th>Nama Penerima</th>
                                                <th>Fakultas</th>
                                                <th>NIM</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="previewTableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="<?= url_to('sertifikat.index') ?>"
                                    class="btn btn-light-secondary me-2">Kembali</a>
                                <button type="button" id="btnPreview" class="btn btn-info me-2" disabled>
                                    <i class="fas fa-eye me-2"></i>Preview Data
                                </button>
                                <button type="button" id="btnImport" class="btn btn-primary" disabled>
                                    <i class="fas fa-upload me-2"></i>Import dan Generate Sertifikat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- SheetJS untuk membaca Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<!-- Browser Image Compression untuk compress gambar otomatis -->
<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data untuk import batch
        let importData = [];
        let compressedBaseFile = null; // Simpan file yang sudah di-compress (jika ada)
        const namaSertifikatMap = <?= json_encode(array_column($nama_sertifikat, 'nama_sertifikat', 'id')) ?>;
        const fakultasMap = <?= json_encode(array_column($fakultas, 'nama_fakultas', 'id')) ?>;
        // Reverse map: nama -> id
        const namaSertifikatReverseMap = {};
        <?php foreach ($nama_sertifikat as $ns): ?>
            namaSertifikatReverseMap['<?= esc($ns['nama_sertifikat'], 'js') ?>'] = <?= $ns['id'] ?>;
        <?php endforeach; ?>
        const fakultasReverseMap = {};
        <?php foreach ($fakultas as $f): ?>
            fakultasReverseMap['<?= esc($f['nama_fakultas'], 'js') ?>'] = <?= $f['id'] ?>;
        <?php endforeach; ?>

        // Single Form Validation dengan Auto Compress
        let compressedSingleFile = null; // Simpan file yang sudah di-compress untuk form single
        const form = document.querySelector('form[action*="sertifikat/save"]');
        const fileInput = document.getElementById('file');
        const emptyCertificateInput = document.querySelector('input[name="empty_certificate"]');

        // Event listener untuk file input - compress otomatis jika > 2MB
        if (fileInput) {
            fileInput.addEventListener('change', async function () {
                const file = this.files[0];
                if (!file) {
                    compressedSingleFile = null;
                    return;
                }

                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                const emptyCertificate = emptyCertificateInput && (emptyCertificateInput.value === '1' || emptyCertificateInput.checked);
                let fileToUse = file;
                let isCompressed = false;

                // Jika file lebih dari 2MB dan empty_certificate aktif, compress otomatis
                if (file.size > maxSize && emptyCertificate) {
                    // Validasi tipe file (hanya gambar yang bisa di-compress)
                    const imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    const isImage = imageTypes.includes(file.type);

                    if (!isImage) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ukuran File Terlalu Besar!',
                            html: `
                                <p>File yang Anda pilih memiliki ukuran <strong>${(file.size / (1024 * 1024)).toFixed(2)} MB</strong></p>
                                <p>File PDF tidak dapat di-compress otomatis. Silakan compress file PDF terlebih dahulu menggunakan tool kompresi PDF sebelum upload.</p>
                                <p class="text-muted small mt-2">Ukuran maksimal: 2 MB</p>
                            `,
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#3085d6',
                            width: '600px'
                        });
                        this.value = '';
                        compressedSingleFile = null;
                        return;
                    }

                    // Simpan file asli untuk referensi
                    let originalFile = file;

                    // Tampilkan loading dengan animasi compress
                    const compressSwal = Swal.fire({
                        title: 'Mengompres File...',
                        html: `
                            <div class="text-center">
                                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p>File Anda berukuran <strong>${(file.size / (1024 * 1024)).toFixed(2)} MB</strong></p>
                                <p>Sedang mengompres file menjadi di bawah 2 MB...</p>
                                <div class="progress mt-3" style="height: 25px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                         style="width: 0%" id="compressProgressSingle">0%</div>
                                </div>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            // Simulasi progress bar
                            let progress = 0;
                            const progressInterval = setInterval(() => {
                                progress += Math.random() * 15;
                                if (progress > 90) progress = 90;
                                const progressBar = document.getElementById('compressProgressSingle');
                                if (progressBar) {
                                    progressBar.style.width = progress + '%';
                                    progressBar.textContent = Math.round(progress) + '%';
                                }
                            }, 200);

                            // Simpan interval untuk di-clear nanti
                            compressSwal.progressInterval = progressInterval;
                        }
                    });

                    try {
                        // Pastikan library imageCompression tersedia
                        if (typeof imageCompression === 'undefined') {
                            throw new Error('Library kompresi gambar tidak tersedia. Silakan refresh halaman.');
                        }

                        // Compress gambar menggunakan browser-image-compression
                        const options = {
                            maxSizeMB: 1.9, // Target di bawah 2MB dengan margin
                            maxWidthOrHeight: 1920,
                            useWebWorker: true,
                            fileType: file.type,
                            onProgress: (progress) => {
                                const progressBar = document.getElementById('compressProgressSingle');
                                if (progressBar) {
                                    const percent = Math.round(progress);
                                    progressBar.style.width = percent + '%';
                                    progressBar.textContent = percent + '%';
                                }
                            }
                        };

                        const compressedResult = await imageCompression(file, options);

                        // Pastikan hasil compress adalah File object
                        // Jika hasilnya Blob, convert ke File
                        let finalCompressedFile;
                        if (compressedResult instanceof File) {
                            finalCompressedFile = compressedResult;
                        } else if (compressedResult instanceof Blob) {
                            // Convert Blob ke File
                            finalCompressedFile = new File([compressedResult], file.name, {
                                type: compressedResult.type || file.type,
                                lastModified: Date.now()
                            });
                        } else {
                            throw new Error('Hasil compress bukan File atau Blob object yang valid');
                        }

                        compressedSingleFile = finalCompressedFile;
                        fileToUse = compressedSingleFile;
                        isCompressed = true;

                        // Log untuk debugging
                        console.log('File berhasil di-compress:', {
                            original: {
                                name: file.name,
                                size: (file.size / (1024 * 1024)).toFixed(2) + ' MB',
                                type: file.type,
                                isFile: file instanceof File
                            },
                            compressed: {
                                name: compressedSingleFile.name,
                                size: (compressedSingleFile.size / (1024 * 1024)).toFixed(2) + ' MB',
                                type: compressedSingleFile.type,
                                isFile: compressedSingleFile instanceof File,
                                isBlob: compressedSingleFile instanceof Blob
                            },
                            resultType: compressedResult.constructor.name
                        });

                        // Update progress ke 100%
                        const progressBar = document.getElementById('compressProgressSingle');
                        if (progressBar) {
                            progressBar.style.width = '100%';
                            progressBar.textContent = '100%';
                        }

                        // Clear interval
                        if (compressSwal.progressInterval) {
                            clearInterval(compressSwal.progressInterval);
                        }

                        // Tutup loading dan tampilkan success
                        await Swal.fire({
                            icon: 'success',
                            title: 'Compress Berhasil!',
                            html: `
                                <p>File berhasil dikompres dari <strong>${(file.size / (1024 * 1024)).toFixed(2)} MB</strong> menjadi <strong>${(compressedSingleFile.size / (1024 * 1024)).toFixed(2)} MB</strong></p>
                                <p class="text-muted small mt-2">Penghematan: ${(((file.size - compressedSingleFile.size) / file.size) * 100).toFixed(1)}%</p>
                                <p class="text-muted small mt-2">File siap untuk diupload.</p>
                            `,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6',
                            timer: 2000,
                            timerProgressBar: true
                        });

                    } catch (compressError) {
                        // Clear interval jika ada error
                        if (compressSwal.progressInterval) {
                            clearInterval(compressSwal.progressInterval);
                        }

                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengompres File',
                            html: `
                                <p>Terjadi kesalahan saat mengompres file:</p>
                                <p class="text-danger">${compressError.message || 'Error tidak diketahui'}</p>
                                <p class="text-muted small mt-2">Silakan compress file secara manual atau gunakan file dengan ukuran lebih kecil.</p>
                            `,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#d33',
                            width: '600px'
                        });
                        this.value = '';
                        compressedSingleFile = null;
                        return;
                    }
                } else {
                    // Jika file tidak perlu di-compress, reset compressedSingleFile
                    compressedSingleFile = null;
                }
            });
        }

        // Validasi dan submit form dengan file yang sudah di-compress
        if (form) {
            form.addEventListener('submit', function (e) {
                const file = fileInput.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                const emptyCertificate = emptyCertificateInput && (emptyCertificateInput.value === '1' || emptyCertificateInput.checked);

                // Jika ada file yang sudah di-compress, ganti file input dengan file yang sudah di-compress
                if (compressedSingleFile) {
                    try {
                        // Validasi bahwa compressedSingleFile adalah File object yang valid
                        if (!(compressedSingleFile instanceof File)) {
                            console.error('compressedSingleFile bukan File object:', typeof compressedSingleFile, compressedSingleFile);
                            e.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: `
                                    <p>File yang sudah di-compress tidak valid.</p>
                                    <p class="text-muted small mt-2">Silakan refresh halaman dan coba lagi.</p>
                                `,
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }

                        // Pastikan file memiliki properti yang diperlukan
                        if (!compressedSingleFile.name || compressedSingleFile.size === undefined) {
                            console.error('File tidak memiliki properti yang diperlukan:', compressedSingleFile);
                            e.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: `
                                    <p>File yang sudah di-compress tidak lengkap.</p>
                                    <p class="text-muted small mt-2">Silakan refresh halaman dan coba lagi.</p>
                                `,
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }

                        console.log('File yang akan di-replace:', {
                            name: compressedSingleFile.name,
                            size: (compressedSingleFile.size / (1024 * 1024)).toFixed(2) + ' MB',
                            type: compressedSingleFile.type,
                            isFile: compressedSingleFile instanceof File
                        });

                        // Buat DataTransfer object untuk mengganti file di input
                        // DataTransfer sudah didukung di semua browser modern
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedSingleFile);
                        fileInput.files = dataTransfer.files;

                        // Verifikasi file sudah ter-replace
                        const replacedFile = fileInput.files[0];
                        if (!replacedFile) {
                            e.preventDefault();
                            console.error('File tidak berhasil di-replace - tidak ada file di input');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal mengganti file. Silakan coba lagi.',
                                confirmButtonText: 'OK'
                            });
                            return false;
                        }

                        if (replacedFile.size !== compressedSingleFile.size) {
                            console.warn('Ukuran file tidak sama:', {
                                expected: compressedSingleFile.size,
                                actual: replacedFile.size
                            });
                        }

                        console.log('File berhasil di-replace sebelum submit:', {
                            original: file ? (file.size / (1024 * 1024)).toFixed(2) + ' MB' : 'N/A',
                            compressed: (compressedSingleFile.size / (1024 * 1024)).toFixed(2) + ' MB',
                            replaced: (replacedFile.size / (1024 * 1024)).toFixed(2) + ' MB'
                        });
                    } catch (error) {
                        e.preventDefault();
                        console.error('Error saat replace file:', error);
                        console.error('compressedSingleFile type:', typeof compressedSingleFile);
                        console.error('compressedSingleFile:', compressedSingleFile);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: `
                                <p>Terjadi kesalahan saat mengganti file.</p>
                                <p class="text-muted small mt-2">Error: ${error.message}</p>
                                <p class="text-muted small mt-2">Silakan refresh halaman dan coba lagi.</p>
                            `,
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }
                }

                // Gunakan file yang sudah di-compress jika ada, atau file dari input
                const fileToSubmit = compressedSingleFile || fileInput.files[0];

                if (fileToSubmit && emptyCertificate && fileToSubmit.size > maxSize) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar!',
                        html: `
                            <p>File yang Anda pilih memiliki ukuran <strong>${(fileToSubmit.size / (1024 * 1024)).toFixed(2)} MB</strong></p>
                            <p>Untuk menambahkan nama dan QR code ke dalam sertifikat, ukuran file harus maksimal <strong>2 MB</strong>.</p>
                            <p class="mt-3"><strong>Solusi:</strong></p>
                            <ul class="text-start">
                                <li>Kompres file sertifikat Anda terlebih dahulu menggunakan tool kompresi gambar/PDF</li>
                                <li>Atau gunakan file dengan resolusi lebih rendah</li>
                            </ul>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#3085d6',
                        width: '600px'
                    });

                    return false;
                }

                // Form akan di-submit secara normal dengan file yang sudah ter-replace
                // Tidak perlu preventDefault karena file sudah ter-replace
            });
        }

        // Batch Import Functions
        const excelFileInput = document.getElementById('excel_file');
        const baseCertificateInput = document.getElementById('base_certificate_file');
        const btnPreview = document.getElementById('btnPreview');
        const btnImport = document.getElementById('btnImport');
        const previewContainer = document.getElementById('previewContainer');
        const previewTableBody = document.getElementById('previewTableBody');

        // Enable/disable buttons based on file selection
        function checkFiles() {
            const hasExcel = excelFileInput.files.length > 0;
            const hasBase = baseCertificateInput.files.length > 0;
            btnPreview.disabled = !(hasExcel && hasBase);
            btnImport.disabled = !(hasExcel && hasBase && importData.length > 0);
        }

        excelFileInput.addEventListener('change', function () {
            importData = [];
            previewContainer.style.display = 'none';
            checkFiles();
        });

        baseCertificateInput.addEventListener('change', async function () {
            const file = this.files[0];
            if (!file) {
                compressedBaseFile = null;
                checkFiles();
                return;
            }

            const maxSize = 2 * 1024 * 1024; // 2MB
            let fileToUpload = file;
            let isCompressed = false;

            // Jika file lebih dari 2MB, compress otomatis
            if (file.size > maxSize) {
                // Validasi tipe file (hanya gambar yang bisa di-compress)
                const imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                const isImage = imageTypes.includes(file.type);

                if (!isImage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar!',
                        html: `
                            <p>File yang Anda pilih memiliki ukuran <strong>${(file.size / (1024 * 1024)).toFixed(2)} MB</strong></p>
                            <p>File PDF tidak dapat di-compress otomatis. Silakan compress file PDF terlebih dahulu menggunakan tool kompresi PDF sebelum upload.</p>
                            <p class="text-muted small mt-2">Ukuran maksimal: 2 MB</p>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#3085d6',
                        width: '600px'
                    });
                    this.value = '';
                    compressedBaseFile = null;
                    checkFiles();
                    return;
                }

                // Simpan file yang akan di-compress
                let originalFile = file;

                // Tampilkan loading dengan animasi compress
                const compressSwal = Swal.fire({
                    title: 'Mengompres File...',
                    html: `
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>File Anda berukuran <strong>${(file.size / (1024 * 1024)).toFixed(2)} MB</strong></p>
                            <p>Sedang mengompres file menjadi di bawah 2 MB...</p>
                            <div class="progress mt-3" style="height: 25px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                     style="width: 0%" id="compressProgress">0%</div>
                            </div>
                        </div>
                    `,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        // Simulasi progress bar
                        let progress = 0;
                        const progressInterval = setInterval(() => {
                            progress += Math.random() * 15;
                            if (progress > 90) progress = 90;
                            const progressBar = document.getElementById('compressProgress');
                            if (progressBar) {
                                progressBar.style.width = progress + '%';
                                progressBar.textContent = Math.round(progress) + '%';
                            }
                        }, 200);

                        // Simpan interval untuk di-clear nanti
                        compressSwal.progressInterval = progressInterval;
                    }
                });

                try {
                    // Pastikan library imageCompression tersedia
                    if (typeof imageCompression === 'undefined') {
                        throw new Error('Library kompresi gambar tidak tersedia. Silakan refresh halaman.');
                    }

                    // Compress gambar menggunakan browser-image-compression
                    const options = {
                        maxSizeMB: 1.9, // Target di bawah 2MB dengan margin
                        maxWidthOrHeight: 1920,
                        useWebWorker: true,
                        fileType: file.type,
                        onProgress: (progress) => {
                            const progressBar = document.getElementById('compressProgress');
                            if (progressBar) {
                                const percent = Math.round(progress);
                                progressBar.style.width = percent + '%';
                                progressBar.textContent = percent + '%';
                            }
                        }
                    };

                    compressedBaseFile = await imageCompression(file, options);
                    fileToUpload = compressedBaseFile;
                    isCompressed = true;

                    // Update progress ke 100%
                    const progressBar = document.getElementById('compressProgress');
                    if (progressBar) {
                        progressBar.style.width = '100%';
                        progressBar.textContent = '100%';
                    }

                    // Clear interval
                    if (compressSwal.progressInterval) {
                        clearInterval(compressSwal.progressInterval);
                    }

                    // Tutup loading dan tampilkan success
                    await Swal.fire({
                        icon: 'success',
                        title: 'Compress Berhasil!',
                        html: `
                            <p>File berhasil dikompres dari <strong>${(file.size / (1024 * 1024)).toFixed(2)} MB</strong> menjadi <strong>${(compressedBaseFile.size / (1024 * 1024)).toFixed(2)} MB</strong></p>
                            <p class="text-muted small mt-2">Penghematan: ${(((file.size - compressedBaseFile.size) / file.size) * 100).toFixed(1)}%</p>
                            <p class="text-muted small mt-2">File siap untuk diimport.</p>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6',
                        timer: 2000,
                        timerProgressBar: true
                    });

                } catch (compressError) {
                    // Clear interval jika ada error
                    if (compressSwal.progressInterval) {
                        clearInterval(compressSwal.progressInterval);
                    }

                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengompres File',
                        html: `
                            <p>Terjadi kesalahan saat mengompres file:</p>
                            <p class="text-danger">${compressError.message || 'Error tidak diketahui'}</p>
                            <p class="text-muted small mt-2">Silakan compress file secara manual atau gunakan file dengan ukuran lebih kecil.</p>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33',
                        width: '600px'
                    });
                    this.value = '';
                    compressedBaseFile = null;
                    checkFiles();
                    return;
                }
            } else {
                // Jika file tidak perlu di-compress, simpan langsung
                compressedBaseFile = file;
            }

            // File sudah siap, tidak perlu upload terpisah
            // File akan dikirim langsung saat import
            checkFiles();
        });

        // Preview Data dari Excel
        btnPreview.addEventListener('click', function () {
            const file = excelFileInput.files[0];
            if (!file) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pilih file Excel/CSV terlebih dahulu',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                    if (jsonData.length < 2) {
                        throw new Error('File Excel tidak memiliki data yang cukup');
                    }

                    // Skip header (baris pertama)
                    const headers = jsonData[0].map(h => (h || '').toString().trim().toLowerCase());
                    const dataRows = jsonData.slice(1);

                    // Mapping kolom (case insensitive dan handle berbagai variasi)
                    const colMap = {
                        'nama kegiatan': 'nama_kegiatan',
                        'nama_kegiatan': 'nama_kegiatan',
                        'nama sertifikat': 'nama_sertifikat',
                        'nama_sertifikat': 'nama_sertifikat',
                        'nama sertifikat (nama dari dropdown)': 'nama_sertifikat',
                        'nama penerima': 'nama_penerima',
                        'nama_penerima': 'nama_penerima',
                        'fakultas': 'fakultas',
                        'fakultas (nama dari dropdown)': 'fakultas',
                        'nim': 'nim'
                    };

                    importData = [];
                    const errors = [];

                    dataRows.forEach((row, index) => {
                        if (row.every(cell => !cell || cell.toString().trim() === '')) {
                            return; // Skip baris kosong
                        }

                        const rowData = {};
                        headers.forEach((header, colIndex) => {
                            const mappedKey = colMap[header] || header;
                            rowData[mappedKey] = row[colIndex] ? row[colIndex].toString().trim() : '';
                        });

                        // Validasi dan mapping
                        let hasError = false;
                        let errorMsg = [];

                        if (!rowData.nama_kegiatan || rowData.nama_kegiatan.trim() === '') {
                            errorMsg.push('Nama Kegiatan kosong');
                            hasError = true;
                        }

                        if (!rowData.nama_penerima || rowData.nama_penerima.trim() === '') {
                            errorMsg.push('Nama Penerima kosong');
                            hasError = true;
                        }

                        if (!rowData.nim || rowData.nim.trim() === '') {
                            errorMsg.push('NIM kosong');
                            hasError = true;
                        }

                        // Map nama sertifikat ke ID
                        if (rowData.nama_sertifikat && rowData.nama_sertifikat.trim() !== '') {
                            const sertifikatId = namaSertifikatReverseMap[rowData.nama_sertifikat.trim()];
                            if (sertifikatId) {
                                rowData.nama_sertifikat_id = sertifikatId;
                            } else {
                                errorMsg.push(`Nama sertifikat "${rowData.nama_sertifikat}" tidak ditemukan`);
                                hasError = true;
                            }
                        } else {
                            errorMsg.push('Nama Sertifikat kosong');
                            hasError = true;
                        }

                        // Map fakultas ke ID
                        if (rowData.fakultas && rowData.fakultas.trim() !== '') {
                            const fakultasId = fakultasReverseMap[rowData.fakultas.trim()];
                            if (fakultasId) {
                                rowData.fakultas_id = fakultasId;
                            } else {
                                errorMsg.push(`Fakultas "${rowData.fakultas}" tidak ditemukan`);
                                hasError = true;
                            }
                        } else {
                            errorMsg.push('Fakultas kosong');
                            hasError = true;
                        }

                        if (hasError) {
                            errors.push(`Baris ${index + 2}: ${errorMsg.join(', ')}`);
                            rowData._error = errorMsg.join(', ');
                        }

                        importData.push(rowData);
                    });

                    // Tampilkan preview
                    displayPreview(importData);
                    document.getElementById('previewCount').textContent = importData.length;

                    if (errors.length > 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            html: `<p>Ditemukan ${errors.length} error:</p><ul class="text-start"><li>${errors.slice(0, 5).join('</li><li>')}</li></ul>${errors.length > 5 ? '<p>... dan ' + (errors.length - 5) + ' error lainnya</p>' : ''}`,
                            confirmButtonText: 'OK',
                            width: '600px'
                        });
                    }

                    checkFiles();

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Gagal membaca file Excel',
                        confirmButtonText: 'OK'
                    });
                }
            };

            reader.readAsArrayBuffer(file);
        });

        function displayPreview(data) {
            previewTableBody.innerHTML = '';
            data.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.className = row._error ? 'table-warning' : '';
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${row.nama_kegiatan || '-'}</td>
                    <td>${row.nama_sertifikat || '-'}</td>
                    <td>${row.nama_penerima || '-'}</td>
                    <td>${row.fakultas || '-'}</td>
                    <td>${row.nim || '-'}</td>
                    <td>
                        ${row._error ? `<span class="badge bg-danger">${row._error}</span>` : '<span class="badge bg-success">Valid</span>'}
                    </td>
                `;
                previewTableBody.appendChild(tr);
            });
            previewContainer.style.display = 'block';
        }

        // Import Batch
        btnImport.addEventListener('click', async function () {
            const excelFile = excelFileInput.files[0];
            // Gunakan file yang sudah di-compress jika ada, jika tidak gunakan file asli
            const baseCertificateFile = compressedBaseFile || baseCertificateInput.files[0];

            if (!excelFile || !baseCertificateFile || importData.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pastikan file Excel dan sertifikat dasar sudah dipilih',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Filter data yang valid
            const validData = importData.filter(row =>
                row.nama_kegiatan &&
                row.nama_penerima &&
                row.nim &&
                row.nama_sertifikat_id &&
                row.fakultas_id &&
                !row._error
            );

            if (validData.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Tidak ada data valid untuk diimport',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Konfirmasi
            const result = await Swal.fire({
                title: 'Konfirmasi Import',
                html: `Apakah Anda yakin ingin mengimport <strong>${validData.length}</strong> sertifikat?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Import',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6'
            });

            if (!result.isConfirmed) return;

            // Loading
            Swal.fire({
                title: 'Memproses...',
                html: 'Sedang mengimport dan membuat sertifikat, mohon tunggu...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Kirim data ke server dengan FormData (file langsung dikirim)
            try {
                // Ambil CSRF token dari form
                let csrfToken = null;
                const csrfInputById = document.querySelector('#csrf_token');
                if (csrfInputById && csrfInputById.value) {
                    csrfToken = csrfInputById.value.trim();
                } else {
                    const csrfInputByName = document.querySelector('input[name="<?= csrf_token() ?>"]');
                    if (csrfInputByName && csrfInputByName.value) {
                        csrfToken = csrfInputByName.value.trim();
                    }
                }

                // Buat FormData untuk mengirim file dan data sekaligus
                const formData = new FormData();
                formData.append('excel_file', excelFile);
                formData.append('base_certificate_file', baseCertificateFile);
                formData.append('data', JSON.stringify(validData));
                formData.append('empty_certificate', '1'); // Otomatis true - selalu generate QR Code dan Nama Penerima

                // CSRF token tidak diperlukan karena route sudah di-except dari CSRF filter
                // Tapi tetap kirim untuk kompatibilitas jika diperlukan
                if (csrfToken) {
                    formData.append('<?= csrf_token() ?>', csrfToken);
                }

                let response;
                let timeoutId;
                const controller = new AbortController();

                try {
                    // Set timeout 5 menit (300000 ms)
                    timeoutId = setTimeout(() => {
                        controller.abort();
                    }, 300000);

                    response = await fetch('<?= base_url("sertifikat/importBatch") ?>', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData,
                        signal: controller.signal
                    });

                    // Clear timeout jika request berhasil
                    clearTimeout(timeoutId);
                } catch (fetchError) {
                    // Clear timeout jika ada error
                    if (timeoutId) {
                        clearTimeout(timeoutId);
                    }

                    // Handle network error atau timeout
                    Swal.close();
                    let errorMsg = 'Gagal mengirim request ke server';

                    if (fetchError.name === 'AbortError' || fetchError.name === 'TimeoutError') {
                        errorMsg = 'Request timeout. Proses import memakan waktu terlalu lama. Silakan coba dengan data yang lebih sedikit atau hubungi administrator.';
                    } else if (fetchError.message && (fetchError.message.includes('Failed to fetch') || fetchError.message.includes('NetworkError'))) {
                        errorMsg = 'Tidak dapat terhubung ke server. Pastikan koneksi internet stabil dan server sedang berjalan.';
                    } else {
                        errorMsg = fetchError.message || 'Terjadi kesalahan saat mengirim request';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        html: `
                            <p>${errorMsg}</p>
                            <p class="text-muted small mt-2">Error: ${fetchError.name || 'Unknown'}</p>
                            <p class="text-muted small mt-2">Silakan coba lagi atau hubungi administrator jika masalah berlanjut.</p>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33',
                        width: '500px'
                    });
                    return;
                }

                // Cek status response
                if (!response.ok) {
                    // Jika response tidak OK, coba parse error message
                    let errorMessage = 'Gagal mengimport data';
                    let errorData = null;

                    try {
                        errorData = await response.json();
                        errorMessage = errorData.message || errorData.error || errorMessage;
                    } catch (e) {
                        // Jika bukan JSON, coba baca sebagai text
                        try {
                            const text = await response.text();
                            if (text) {
                                errorMessage = text;
                            } else {
                                errorMessage = `Error ${response.status}: ${response.statusText}`;
                            }
                        } catch (e2) {
                            errorMessage = `Error ${response.status}: ${response.statusText}`;
                        }
                    }

                    // Jika error 403 (CSRF), berikan pesan yang lebih jelas
                    if (response.status === 403) {
                        errorMessage = 'CSRF token tidak valid. Silakan refresh halaman dan coba lagi.';
                    }

                    throw new Error(errorMessage);
                }

                const result = await response.json();

                if (result.success) {
                    // Tampilkan hasil dengan animasi
                    Swal.fire({
                        icon: 'success',
                        title: 'Import Berhasil!',
                        html: `
                            <div class="text-center">
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <p><strong>${result.success_count}</strong> sertifikat berhasil dibuat</p>
                                ${result.error_count > 0 ? `<p class="text-danger"><strong>${result.error_count}</strong> sertifikat gagal</p>` : ''}
                                ${result.errors && result.errors.length > 0 ? `
                                    <div class="mt-3 text-start">
                                        <strong>Detail Error:</strong>
                                        <ul class="text-start small">
                                            ${result.errors.slice(0, 5).map(err => `<li>${err}</li>`).join('')}
                                            ${result.errors.length > 5 ? `<li>... dan ${result.errors.length - 5} error lainnya</li>` : ''}
                                        </ul>
                                    </div>
                                ` : ''}
                            </div>
                        `,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6',
                        width: '600px',
                        willClose: () => {
                            window.location.href = '<?= url_to("sertifikat.index") ?>';
                        }
                    });
                } else {
                    throw new Error(result.message || 'Gagal import data');
                }
            } catch (error) {
                Swal.close();

                // Tentukan pesan error yang lebih spesifik
                let errorTitle = 'Error';
                let errorMessage = '';

                if (error && error.message) {
                    errorMessage = String(error.message);
                } else if (typeof error === 'string') {
                    errorMessage = error;
                } else if (error && typeof error === 'object') {
                    errorMessage = JSON.stringify(error);
                } else {
                    errorMessage = 'Gagal mengimport data';
                }

                let errorDetail = '';
                const errorMessageStr = String(errorMessage).toLowerCase();

                if (errorMessageStr.includes('csrf')) {
                    errorTitle = 'CSRF Token Error';
                    errorDetail = '<p class="text-muted small mt-2">Silakan refresh halaman untuk mendapatkan token baru.</p>';
                } else if (errorMessageStr.includes('undefined variable')) {
                    errorTitle = 'Error Server';
                    errorDetail = '<p class="text-muted small mt-2">Terjadi kesalahan pada server. Silakan hubungi administrator.</p>';
                } else if (errorMessageStr.includes('failed to fetch') || errorMessageStr.includes('networkerror')) {
                    errorTitle = 'Network Error';
                    errorDetail = '<p class="text-muted small mt-2">Tidak dapat terhubung ke server. Pastikan koneksi internet stabil dan server sedang berjalan.</p>';
                } else {
                    errorDetail = '<p class="text-muted small mt-2">Pastikan semua data sudah benar dan file valid.</p>';
                }

                Swal.fire({
                    icon: 'error',
                    title: errorTitle,
                    html: `
                        <p>${errorMessage}</p>
                        ${errorDetail}
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33',
                    width: '500px'
                });
            }
        });

        // Tampilkan pesan error dari flashdata jika ada
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                html: '<?= esc(session()->getFlashdata('error')) ?>',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#d33',
                width: '600px'
            });
        <?php endif; ?>

        // Tampilkan pesan success dari flashdata jika ada
        <?php if (session()->getFlashdata('pesan')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= esc(session()->getFlashdata('pesan')) ?>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>