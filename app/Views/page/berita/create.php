<?= $this->extend('components/layouts/app') ?>

<?= $this->section('title') ?>Tambah Berita Baru<?= $this->endSection() ?>

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
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Berita Baru</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('page/berita/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                    <input type="text" class="form-control <?= session('errors.nama_kegiatan') ? 'is-invalid' : '' ?>"
                        id="nama_kegiatan" name="nama_kegiatan" value="<?= old('nama_kegiatan') ?>" required>
                    <?php if (session('errors.nama_kegiatan')): ?>
                        <div class="invalid-feedback"><?= session('errors.nama_kegiatan') ?></div>
                    <?php endif ?>
                </div>

                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select <?= session('errors.kategori') ? 'is-invalid' : '' ?>" id="kategori"
                        name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="blogger" <?= old('kategori') == 'blogger' ? 'selected' : '' ?>>Blogger</option>
                        <option value="podcast" <?= old('kategori') == 'podcast' ? 'selected' : '' ?>>Podcast</option>
                    </select>
                    <?php if (session('errors.kategori')): ?>
                        <div class="invalid-feedback"><?= session('errors.kategori') ?></div>
                    <?php endif ?>
                </div>

                <!-- Field Link (hanya muncul jika kategori podcast) -->
                <div class="mb-3" id="link-field" style="display: none;">
                    <label for="link" class="form-label">Link Video YouTube <span class="text-danger">*</span></label>
                    <input type="url" class="form-control <?= session('errors.link') ? 'is-invalid' : '' ?>" id="link"
                        name="link" value="<?= old('link') ?>"
                        placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/...">
                    <div class="form-text">Masukkan link video YouTube untuk kategori podcast</div>
                    <?php if (session('errors.link')): ?>
                        <div class="invalid-feedback"><?= session('errors.link') ?></div>
                    <?php endif ?>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const kategoriSelect = document.getElementById('kategori');
                        const linkField = document.getElementById('link-field');
                        const linkInput = document.getElementById('link');
                        const gambarInput = document.getElementById('gambar');
                        const gambarRequired = document.getElementById('gambar-required');
                        const gambarLabel = document.getElementById('gambar-label');

                        function toggleFields() {
                            if (kategoriSelect.value === 'podcast') {
                                // Podcast: link wajib, gambar tidak wajib, label menjadi "Gambar thumbnail"
                                linkField.style.display = 'block';
                                linkInput.setAttribute('required', 'required');
                                gambarInput.removeAttribute('required');
                                gambarRequired.style.display = 'none';
                                gambarLabel.textContent = 'Gambar thumbnail';
                            } else if (kategoriSelect.value === 'blogger') {
                                // Blogger: gambar wajib, link tidak wajib, label menjadi "Gambar"
                                linkField.style.display = 'none';
                                linkInput.removeAttribute('required');
                                linkInput.value = '';
                                gambarInput.setAttribute('required', 'required');
                                gambarRequired.style.display = 'inline';
                                gambarLabel.textContent = 'Gambar';
                            } else {
                                // Belum pilih kategori
                                linkField.style.display = 'none';
                                linkInput.removeAttribute('required');
                                gambarInput.removeAttribute('required');
                                gambarRequired.style.display = 'none';
                                gambarLabel.textContent = 'Gambar';
                            }
                        }

                        // Toggle saat halaman dimuat
                        toggleFields();

                        // Toggle saat kategori berubah
                        kategoriSelect.addEventListener('change', toggleFields);
                    });
                </script>

                <div class="mb-3">
                    <label for="fakultas_id" class="form-label">Fakultas</label>
                    <select class="form-select <?= session('errors.fakultas_id') ? 'is-invalid' : '' ?>"
                        id="fakultas_id" name="fakultas_id" required>
                        <option value="">Pilih Fakultas</option>
                        <?php foreach ($fakultas as $f): ?>
                            <option value="<?= $f['id'] ?>" <?= old('fakultas_id') == $f['id'] ? 'selected' : '' ?>>
                                <?= esc($f['nama_fakultas']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                    <?php if (session('errors.fakultas_id')): ?>
                        <div class="invalid-feedback"><?= session('errors.fakultas_id') ?></div>
                    <?php endif ?>
                </div>

                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Kegiatan</label>
                    <input type="date" class="form-control <?= session('errors.tanggal') ? 'is-invalid' : '' ?>"
                        id="tanggal" name="tanggal" value="<?= old('tanggal') ?>" required>
                    <?php if (session('errors.tanggal')): ?>
                        <div class="invalid-feedback"><?= session('errors.tanggal') ?></div>
                    <?php endif ?>
                </div>

                <div class="mb-3" id="gambar-field">
                    <label for="gambar" class="form-label"><span id="gambar-label">Gambar</span> <span
                            id="gambar-required" class="text-danger">*</span></label>
                    <input type="file" class="form-control <?= session('errors.gambar') ? 'is-invalid' : '' ?>"
                        id="gambar" name="gambar">
                    <div class="form-text">Format: JPG, PNG, maks 2MB. Wajib untuk kategori Blogger.</div>
                    <?php if (session('errors.gambar')): ?>
                        <div class="invalid-feedback"><?= session('errors.gambar') ?></div>
                    <?php endif ?>
                </div>


                <div class="mb-3">
                    <label for="deskripsi1" class="form-label">Deskripsi Utama</label>
                    <textarea class="form-control <?= session('errors.deskripsi1') ? 'is-invalid' : '' ?>"
                        id="deskripsi1" name="deskripsi1" rows="3" required><?= old('deskripsi1') ?></textarea>
                    <?php if (session('errors.deskripsi1')): ?>
                        <div class="invalid-feedback"><?= session('errors.deskripsi1') ?></div>
                    <?php endif ?>
                </div>

                <div class="mb-3">
                    <label for="deskripsi2" class="form-label">Detail Kegiatan</label>
                    <textarea class="form-control <?= session('errors.deskripsi2') ? 'is-invalid' : '' ?>"
                        id="deskripsi2" name="deskripsi2" rows="3"><?= old('deskripsi2') ?></textarea>
                    <?php if (session('errors.deskripsi2')): ?>
                        <div class="invalid-feedback"><?= session('errors.deskripsi2') ?></div>
                    <?php endif ?>
                </div>
                <div class="mb-3">
                    <label for="deskripsi3" class="form-label">Informasi Tambahan</label>
                    <textarea class="form-control <?= session('errors.deskripsi3') ? 'is-invalid' : '' ?>"
                        id="deskripsi3" name="deskripsi3" rows="3"><?= old('deskripsi3') ?></textarea>
                    <?php if (session('errors.deskripsi3')): ?>
                        <div class="invalid-feedback"><?= session('errors.deskrips3') ?></div>
                    <?php endif ?>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?= base_url('page/berita') ?>" class="btn btn-secondary me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>