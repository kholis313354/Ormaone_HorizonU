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

    /* Styling untuk row yang dipilih */
    .row-selected {
        background-color: #e3f2fd !important;
    }

    #selectAll {
        cursor: pointer;
    }

    .mahasiswa-checkbox {
        cursor: pointer;
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
                        <h5 class="card-title mb-0">
                            Data Mahasiswa Voting OTP
                        </h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <!-- Bulk Actions (hidden by default) -->
                            <div id="bulkActions" class="d-none d-flex gap-2 align-items-center">
                                <?php if (in_array(session()->get('level'), [1, 2])): ?>
                                    <button type="button" class="btn btn-warning btn-sm" id="btnBulkEdit"
                                        title="Edit yang dipilih">
                                        <i class="bi bi-pencil-square"></i> Edit (<span id="selectedCountEdit">0</span>)
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" id="btnBulkDelete"
                                        title="Hapus yang dipilih">
                                        <i class="bi bi-trash"></i> Hapus (<span id="selectedCountDelete">0</span>)
                                    </button>
                                <?php endif; ?>
                                <button type="button" class="btn btn-secondary btn-sm" id="btnClearSelection">
                                    <i class="bi bi-x-circle"></i> Batal
                                </button>
                            </div>
                            <a href="<?= url_to('organisasi.mahasiswa.create') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
                            </a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered" id="table1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAll" title="Pilih Semua">
                            </th>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="mahasiswa-checkbox" value="<?= $item['id'] ?>"
                                        data-id="<?= $item['id'] ?>">
                                </td>
                                <td><?= $no++ ?></td>
                                <td><?= esc($item['nama']) ?></td>
                                <td><?= esc($item['nim']) ?></td>
                                <td><?= esc($item['email']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $item['status'] === 'aktif' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($item['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
                                <td>
                                    <?php if (in_array(session()->get('level'), [1, 2])): ?>
                                        <a href="<?= url_to('organisasi.mahasiswa.edit', $item['id']) ?>"
                                            class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square btn-sm"></i>
                                            Edit
                                        </a>
                                        <form action="<?= url_to('organisasi.mahasiswa.delete', $item['id']) ?>" method="POST"
                                            class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="bi bi-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Bulk Actions Functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.mahasiswa-checkbox');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCountEdit = document.getElementById('selectedCountEdit');
        const selectedCountDelete = document.getElementById('selectedCountDelete');
        const btnBulkEdit = document.getElementById('btnBulkEdit');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const btnClearSelection = document.getElementById('btnClearSelection');

        // Function to update selected count
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.mahasiswa-checkbox:checked');
            const count = selected.length;

            // Update row highlighting
            checkboxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                if (checkbox.checked) {
                    row.classList.add('row-selected');
                } else {
                    row.classList.remove('row-selected');
                }
            });

            if (count > 0) {
                bulkActions.classList.remove('d-none');
                if (selectedCountEdit) selectedCountEdit.textContent = count;
                if (selectedCountDelete) selectedCountDelete.textContent = count;
            } else {
                bulkActions.classList.add('d-none');
            }

            // Update select all checkbox state
            if (selectAllCheckbox) {
                selectAllCheckbox.indeterminate = count > 0 && count < checkboxes.length;
                selectAllCheckbox.checked = count === checkboxes.length && checkboxes.length > 0;
            }
        }

        // Select All functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });
        }

        // Individual checkbox change
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        // Clear selection
        if (btnClearSelection) {
            btnClearSelection.addEventListener('click', function () {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    const row = checkbox.closest('tr');
                    row.classList.remove('row-selected');
                });
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
                updateSelectedCount();
            });
        }

        // Bulk Edit - redirect ke edit page pertama yang dipilih
        if (btnBulkEdit) {
            btnBulkEdit.addEventListener('click', function () {
                const selected = Array.from(document.querySelectorAll('.mahasiswa-checkbox:checked'))
                    .map(cb => cb.value);

                if (selected.length === 0) {
                    alert('Pilih mahasiswa yang ingin diedit terlebih dahulu');
                    return;
                }

                if (selected.length === 1) {
                    // Jika hanya satu, langsung redirect ke edit page
                    window.location.href = '<?= base_url('organisasi/mahasiswa/edit') ?>/' + selected[0];
                } else {
                    // Jika lebih dari satu, edit satu per satu (mulai dari yang pertama)
                    if (confirm(`Anda memilih ${selected.length} mahasiswa. Anda akan diarahkan untuk mengedit satu per satu, mulai dari yang pertama. Lanjutkan?`)) {
                        window.location.href = '<?= base_url('organisasi/mahasiswa/edit') ?>/' + selected[0];
                    }
                }
            });
        }

        // Bulk Delete
        if (btnBulkDelete) {
            btnBulkDelete.addEventListener('click', function () {
                const selected = Array.from(document.querySelectorAll('.mahasiswa-checkbox:checked'))
                    .map(cb => cb.value);

                if (selected.length === 0) {
                    alert('Pilih mahasiswa yang ingin dihapus terlebih dahulu');
                    return;
                }

                if (confirm(`Apakah Anda yakin ingin menghapus ${selected.length} mahasiswa yang dipilih?`)) {
                    // Ambil CSRF token dari form yang ada di halaman atau meta tag
                    let csrfToken = null;
                    const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                    if (csrfInput && csrfInput.value) {
                        csrfToken = csrfInput.value.trim();
                    } else {
                        // Fallback: ambil dari meta tag jika ada
                        const metaCsrf = document.querySelector('meta[name="csrf-token"]');
                        if (metaCsrf) {
                            csrfToken = metaCsrf.getAttribute('content');
                        }
                    }

                    if (!csrfToken) {
                        alert('CSRF token tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                        return;
                    }

                    // Create form untuk bulk delete dengan method POST
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= base_url('organisasi/mahasiswa/bulkDelete') ?>';
                    form.style.display = 'none';

                    // Add CSRF token
                    const csrfInputField = document.createElement('input');
                    csrfInputField.type = 'hidden';
                    csrfInputField.name = '<?= csrf_token() ?>';
                    csrfInputField.value = csrfToken;
                    form.appendChild(csrfInputField);

                    // Add selected IDs
                    selected.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Initialize
        updateSelectedCount();
    });
</script>
<?= $this->endSection() ?>