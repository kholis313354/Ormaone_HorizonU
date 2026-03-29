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

                <p class="text-subtitle text-muted"></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
            </div>
        </div>
    </div>
    <section class="section">
        <!-- Tabs untuk Single dan Batch Import -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="mahasiswaTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="single-tab" data-bs-toggle="tab" data-bs-target="#single"
                            type="button" role="tab">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Mahasiswa (Single)
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
                <div class="tab-content" id="mahasiswaTabContent">
                    <!-- Tab Single -->
                    <div class="tab-pane fade show active" id="single" role="tabpanel">
                        <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                        <form action="<?= url_to('organisasi.mahasiswa.store') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Nama Mahasiswa</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="<?= old('nama') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nim">NIM</label>
                                        <input type="text" class="form-control" id="nim" name="nim"
                                            value="<?= old('nim') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?= old('email') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="aktif" <?= old('status') === 'aktif' ? 'selected' : '' ?>>Aktif
                                            </option>
                                            <option value="nonaktif" <?= old('status') === 'nonaktif' ? 'selected' : '' ?>>
                                                Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                    <a href="<?= url_to('organisasi.mahasiswa.index') ?>"
                                        class="btn btn-light">Batal</a>
                                </div>
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
                                <li>Isi data sesuai template (Nama, NIM, Email, Status)</li>
                                <li>Upload file Excel/CSV yang sudah diisi</li>
                                <li>Klik "Preview Data" untuk melihat preview</li>
                                <li>Klik "Import Data" untuk menyimpan semua data</li>
                            </ol>
                            <p class="mb-0 mt-2"><strong>Catatan:</strong> Sistem dapat mengimport hingga 2000+ data
                                sekaligus. NIM dan Email harus unik.</p>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <a href="<?= base_url('organisasi/mahasiswa/downloadTemplate') ?>"
                                    class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Download Template Excel/CSV
                                </a>
                            </div>
                        </div>

                        <form id="batchImportForm">
                            <?= csrf_field() ?>
                            <input type="hidden" id="csrf_token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="excel_file" class="form-label">
                                            <i class="fas fa-file-excel me-2"></i>File Excel/CSV
                                        </label>
                                        <input type="file" class="form-control" id="excel_file" accept=".xlsx,.xls,.csv"
                                            required>
                                        <small class="text-muted">Format: .xlsx, .xls, atau .csv (Maksimal 10MB)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Data -->
                            <div id="previewContainer" class="mt-4" style="display: none;">
                                <h5>Preview Data (<span id="previewCount">0</span> baris)</h5>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIM</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Status Validasi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="previewTableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="<?= url_to('organisasi.mahasiswa.index') ?>"
                                    class="btn btn-light-secondary me-2">Kembali</a>
                                <button type="button" id="btnPreview" class="btn btn-info me-2" disabled>
                                    <i class="fas fa-eye me-2"></i>Preview Data
                                </button>
                                <button type="button" id="btnImport" class="btn btn-primary" disabled>
                                    <i class="fas fa-upload me-2"></i>Import Data
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data untuk import batch
        let importData = [];
        const excelFileInput = document.getElementById('excel_file');
        const btnPreview = document.getElementById('btnPreview');
        const btnImport = document.getElementById('btnImport');
        const previewContainer = document.getElementById('previewContainer');
        const previewTableBody = document.getElementById('previewTableBody');

        // Enable/disable buttons based on file selection
        function checkFile() {
            const hasExcel = excelFileInput && excelFileInput.files.length > 0;
            btnPreview.disabled = !hasExcel;
            btnImport.disabled = !(hasExcel && importData.length > 0);
        }

        if (excelFileInput) {
            excelFileInput.addEventListener('change', function () {
                importData = [];
                previewContainer.style.display = 'none';
                checkFile();
            });
        }

        // Preview Data dari Excel
        if (btnPreview) {
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

                        // Mapping kolom
                        const colMap = {
                            'nama': 'nama',
                            'nim': 'nim',
                            'email': 'email',
                            'status': 'status'
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

                            if (!rowData.nama || rowData.nama.trim() === '') {
                                errorMsg.push('Nama kosong');
                                hasError = true;
                            }

                            if (!rowData.nim || rowData.nim.trim() === '') {
                                errorMsg.push('NIM kosong');
                                hasError = true;
                            }

                            if (!rowData.email || rowData.email.trim() === '') {
                                errorMsg.push('Email kosong');
                                hasError = true;
                            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(rowData.email)) {
                                errorMsg.push('Format email tidak valid');
                                hasError = true;
                            }

                            if (!rowData.status || rowData.status.trim() === '') {
                                errorMsg.push('Status kosong');
                                hasError = true;
                            } else if (!['aktif', 'nonaktif'].includes(rowData.status.toLowerCase())) {
                                errorMsg.push('Status harus aktif atau nonaktif');
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

                        checkFile();

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
        }

        function displayPreview(data) {
            previewTableBody.innerHTML = '';
            data.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.className = row._error ? 'table-warning' : '';
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${row.nama || '-'}</td>
                    <td>${row.nim || '-'}</td>
                    <td>${row.email || '-'}</td>
                    <td>${row.status || '-'}</td>
                    <td>
                        ${row._error ? `<span class="badge bg-danger">${row._error}</span>` : '<span class="badge bg-success">Valid</span>'}
                    </td>
                `;
                previewTableBody.appendChild(tr);
            });
            previewContainer.style.display = 'block';
        }

        // Import Batch
        if (btnImport) {
            btnImport.addEventListener('click', async function () {
                if (importData.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Pastikan file Excel sudah diupload dan di-preview terlebih dahulu',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Filter data yang valid
                const validData = importData.filter(row =>
                    row.nama &&
                    row.nim &&
                    row.email &&
                    row.status &&
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
                    html: `Apakah Anda yakin ingin mengimport <strong>${validData.length}</strong> data mahasiswa?`,
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
                    html: 'Sedang mengimport data mahasiswa, mohon tunggu...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Ambil CSRF token
                const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]')?.value ||
                    document.querySelector('#csrf_token')?.value ||
                    '<?= csrf_hash() ?>';

                // Kirim data ke server
                try {
                    const response = await fetch('<?= base_url("organisasi/mahasiswa/importBatch") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            data: validData
                        })
                    });

                    // Cek status response
                    if (!response.ok) {
                        let errorMessage = 'Gagal mengimport data';
                        try {
                            const errorData = await response.json();
                            errorMessage = errorData.message || errorData.error || errorMessage;
                        } catch (e) {
                            errorMessage = `Error ${response.status}: ${response.statusText}`;
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
                                    <p><strong>${result.success_count}</strong> data mahasiswa berhasil diimport</p>
                                    ${result.error_count > 0 ? `<p class="text-danger"><strong>${result.error_count}</strong> data gagal</p>` : ''}
                                    ${result.skipped_count > 0 ? `<p class="text-warning"><strong>${result.skipped_count}</strong> data di-skip (duplikat)</p>` : ''}
                                    ${result.errors && result.errors.length > 0 ? `
                                        <div class="mt-3 text-start">
                                            <strong>Detail Error:</strong>
                                            <ul class="text-start small" style="max-height: 200px; overflow-y: auto;">
                                                ${result.errors.slice(0, 10).map(err => `<li>${err}</li>`).join('')}
                                                ${result.errors.length > 10 ? `<li>... dan ${result.errors.length - 10} error lainnya</li>` : ''}
                                            </ul>
                                        </div>
                                    ` : ''}
                                </div>
                            `,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6',
                            width: '600px',
                            willClose: () => {
                                window.location.href = '<?= url_to("organisasi.mahasiswa.index") ?>';
                            }
                        });
                    } else {
                        throw new Error(result.message || 'Gagal import data');
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: `<p>${error.message || 'Gagal mengimport data'}</p>
                               <p class="text-muted small mt-2">Pastikan CSRF token valid dan semua data sudah benar.</p>`,
                        confirmButtonText: 'OK',
                        width: '500px'
                    });
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>