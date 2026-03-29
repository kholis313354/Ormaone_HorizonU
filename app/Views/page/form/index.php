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

                <p class="text-subtitle text-muted"></p>
            </div>
        </div>
    </div>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <p class="text-subtitle text-muted"></p>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Data Form</h4>
                            <a href="<?= url_to('form.create') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Form
                            </a>
                        </div>
                    </div>
                    <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                    <?php include_once(APPPATH . 'Views/components/flash.php'); ?>

                    <?php if (!empty($data)): ?>
                        <table class="table table-striped table-bordered" id="table1" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Tipe</th>
                                    <th>Organisasi</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Response</th>
                                    <th>QR Code & Link</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($data as $item): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= clean_html($item['title']) ?></td>
                                        <td><?= esc($item['form_type']) ?></td>
                                        <td><?= esc($item['organisasi_name'] ?? '-') ?></td>
                                        <td>
                                            <?php if ($item['status'] == 'published'): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php elseif ($item['status'] == 'draft'): ?>
                                                <span class="badge bg-warning">Draft</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Closed</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $item['created_at'] ?></td>
                                        <td>
                                            <a href="<?= url_to('form.response', $item['id']) ?>"
                                                class="btn btn-success btn-sm">
                                                <i class="bi bi-activity"></i>
                                                Lihat
                                            </a>
                                        </td>
                                        <td>
                                            <?php if ($item['status'] == 'published' && !empty($item['encrypted_link'])): ?>
                                                <?php
                                                // Pastikan QR code path ada dan file benar-benar ada
                                                $qrCodeUrl = '';
                                                if (!empty($item['qr_code_path'])) {
                                                    // Normalize path untuk file system check
                                                    $qrCodePath = ltrim($item['qr_code_path'], '/');
                                                    $qrCodeFullPath = FCPATH . $qrCodePath;
                                                    $qrCodeFullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $qrCodeFullPath);
                                                    $qrCodeFullPath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $qrCodeFullPath);

                                                    if (file_exists($qrCodeFullPath) && filesize($qrCodeFullPath) > 0) {
                                                        // Normalize path untuk web URL (forward slash)
                                                        $webPath = str_replace('\\', '/', $qrCodePath);
                                                        $webPath = preg_replace('/\/+/', '/', $webPath);
                                                        $qrCodeUrl = base_url($webPath);
                                                    }
                                                }
                                                ?>
                                                <button type="button" class="btn btn-info btn-sm btn-qr-link"
                                                    data-form-id="<?= $item['id'] ?>"
                                                    data-title="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-link="<?= htmlspecialchars(base_url('form/public/' . $item['encrypted_link']), ENT_QUOTES, 'UTF-8') ?>"
                                                    data-qrcode="<?= htmlspecialchars($qrCodeUrl, ENT_QUOTES, 'UTF-8') ?>">
                                                    <i class="bi bi-qr-code"></i>
                                                    QR & Link
                                                </button>
                                            <?php elseif ($item['status'] == 'draft'): ?>
                                                <span class="badge bg-warning text-dark"
                                                    title="Ubah status ke Published untuk generate QR Code">
                                                    <i class="bi bi-info-circle"></i> Belum Published
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= url_to('form.edit', $item['id']) ?>" class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>
                                            <form action="<?= url_to('form.delete', $item['id']) ?>" method="POST"
                                                class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus form ini?')">
                                                    <i class="bi bi-trash"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info">Belum ada data form.</div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
    <?= $this->endSection() ?>
    <?= $this->section('scripts') ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Search functionality
            const searchInput = document.getElementById('modernSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const tables = document.querySelectorAll('#table1');

                    tables.forEach(table => {
                        const rows = table.querySelectorAll('tbody tr');
                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            if (text.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    });
                });
            }

            // QR Code & Link button click handler
            const qrLinkButtons = document.querySelectorAll('.btn-qr-link');
            qrLinkButtons.forEach(button => {
                button.addEventListener('click', async function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const formId = this.getAttribute('data-form-id');
                    const title = this.getAttribute('data-title') || '';
                    const link = this.getAttribute('data-link') || '';
                    let qrCodePath = this.getAttribute('data-qrcode') || '';

                    console.log('QR Link clicked:', { formId, title, link, qrCodePath });

                    // Jika QR Code belum ada tapi link ada, generate QR Code terlebih dahulu
                    if (!qrCodePath && link && formId) {
                        try {
                            // Show loading
                            Swal.fire({
                                title: 'Generate QR Code',
                                html: 'Sedang generate QR Code...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Generate QR Code via AJAX
                            const qrCodeUrl = '<?= base_url('form/qrcode/') ?>' + formId;
                            const response = await fetch(qrCodeUrl, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json'
                                }
                            });

                            // Check if response is OK
                            if (!response.ok) {
                                throw new Error('HTTP error! status: ' + response.status);
                            }

                            let result;
                            try {
                                const responseText = await response.text();
                                console.log('Raw response text:', responseText);
                                result = JSON.parse(responseText);
                            } catch (parseError) {
                                console.error('Error parsing JSON response:', parseError);
                                throw new Error('Invalid JSON response from server');
                            }

                            console.log('QR Code generation response:', result);
                            console.log('Response success:', result.success);
                            console.log('Response qr_code_url:', result.qr_code_url);
                            console.log('Response qr_code_path:', result.qr_code_path);

                            if (result.success && result.qr_code_url) {
                                qrCodePath = result.qr_code_url;
                                console.log('QR Code URL received:', qrCodePath);

                                // Update data attribute
                                this.setAttribute('data-qrcode', qrCodePath);
                                Swal.close();

                                // Tampilkan success message singkat
                                Swal.fire({
                                    icon: 'success',
                                    title: 'QR Code berhasil digenerate!',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            } else {
                                // QR Code gagal di-generate, tapi tetap tampilkan modal dengan link
                                Swal.close();
                                console.error('QR Code generation failed:', result.message || 'Unknown error');
                                console.error('Full response:', result);
                                console.error('Response status:', response.status);
                                // qrCodePath tetap kosong, akan ditampilkan warning di modal
                            }
                        } catch (error) {
                            console.error('Error generating QR Code:', error);
                            Swal.close();
                            // Tetap lanjutkan untuk menampilkan modal dengan link
                            // qrCodePath tetap kosong, akan ditampilkan warning di modal
                        }
                    }

                    // Show QR Code and Link (link tetap ditampilkan meskipun QR Code gagal)
                    showQRCodeAndLink(title, link, qrCodePath);
                });
            });
        });

        // Function to show QR Code and Link in SweetAlert (must be global)
        window.showQRCodeAndLink = function (title, link, qrCodePath) {
            try {
                // Check if SweetAlert2 is loaded
                if (typeof Swal === 'undefined') {
                    console.error('SweetAlert2 is not loaded!');
                    alert('Error: SweetAlert2 library tidak ditemukan. Silakan refresh halaman.');
                    return;
                }

                // Escape HTML untuk keamanan
                const escapeHtml = (text) => {
                    if (!text) return '';
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                };

                let htmlContent = '<div style="text-align: center; padding: 1rem;">';

                // QR Code Image
                console.log('showQRCodeAndLink called with:', { title, link, qrCodePath });

                if (qrCodePath && qrCodePath.trim() !== '') {
                    console.log('Displaying QR Code with path:', qrCodePath);
                    console.log('QR Code path type:', typeof qrCodePath);
                    console.log('QR Code path length:', qrCodePath.length);

                    htmlContent += '<div style="margin-bottom: 1.5rem;">';
                    htmlContent += '<p style="margin-bottom: 0.5rem; font-weight: 500; color: #374151;">QR Code:</p>';

                    // Pastikan URL adalah full URL
                    let qrCodeUrl = qrCodePath;
                    if (!qrCodeUrl.startsWith('http://') && !qrCodeUrl.startsWith('https://')) {
                        // Jika bukan full URL, pastikan dimulai dengan /
                        if (!qrCodeUrl.startsWith('/')) {
                            qrCodeUrl = '/' + qrCodeUrl;
                        }
                        // Gunakan base URL dari window.location jika base_url tidak tersedia
                        const baseUrl = window.location.origin;
                        qrCodeUrl = baseUrl + qrCodeUrl;
                    }

                    // Tambahkan timestamp untuk cache busting
                    const qrCodeUrlWithCache = qrCodeUrl + (qrCodeUrl.indexOf('?') > -1 ? '&' : '?') + 't=' + Date.now();
                    console.log('Final QR Code URL:', qrCodeUrlWithCache);

                    const qrCodeSrc = escapeHtml(qrCodeUrlWithCache);
                    htmlContent += '<img src="' + qrCodeSrc + '" alt="QR Code" id="qrCodeImage" style="max-width: 250px; height: auto; border: 1px solid #ddd; padding: 10px; background: #fff; border-radius: 8px; display: inline-block; margin: 0 auto;" onload="console.log(\'QR Code image loaded successfully: \' + this.src);" onerror="console.error(\'QR Code image failed to load:\', this.src); console.error(\'Error details:\', event); this.style.display=\'none\'; const parent = this.parentElement; if (parent) { parent.innerHTML=\'<div style=\\\'padding: 1rem; background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px;\\\'><p style=\\\'color: #92400e; margin: 0;\\\'>Gambar QR Code tidak dapat dimuat</p><p style=\\\'color: #78350f; margin: 0.5rem 0 0 0; font-size: 0.75rem;\\\'>URL: \' + this.src + \'</p></div>\'; }">';
                    htmlContent += '</div>';
                } else {
                    console.log('QR Code path is empty, showing warning message');
                    htmlContent += '<div style="margin-bottom: 1.5rem; padding: 1.5rem; background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px;">';
                    htmlContent += '<i class="bi bi-exclamation-triangle-fill" style="color: #f59e0b; font-size: 2rem; margin-bottom: 0.5rem;"></i>';
                    htmlContent += '<p style="color: #92400e; margin: 0; font-weight: 500;">QR Code belum digenerate</p>';
                    htmlContent += '<p style="color: #78350f; margin: 0.5rem 0 0 0; font-size: 0.875rem;">QR Code sedang dalam proses generate atau terjadi error. Link form tetap dapat digunakan di bawah ini.</p>';
                    htmlContent += '</div>';
                }

                // Link Section
                htmlContent += '<div style="margin-top: 1.5rem;">';
                htmlContent += '<label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151; text-align: left;">Link Form:</label>';
                htmlContent += '<div style="display: flex; gap: 0.5rem; align-items: center; background: #f9fafb; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">';
                htmlContent += '<input type="text" id="formLinkInput" value="' + escapeHtml(link) + '" readonly style="flex: 1; border: none; background: transparent; outline: none; font-size: 0.875rem; color: #111827; font-family: monospace;">';
                htmlContent += '<button type="button" onclick="copyLink(\'' + escapeHtml(link) + '\')" class="btn btn-sm btn-primary" style="white-space: nowrap; padding: 0.5rem 1rem;">';
                htmlContent += '<i class="bi bi-clipboard"></i> Copy';
                htmlContent += '</button>';
                htmlContent += '</div>';
                htmlContent += '</div>';

                htmlContent += '</div>';

                console.log('Opening SweetAlert with HTML content length:', htmlContent.length);
                console.log('QR Code path in modal:', qrCodePath);

                // Strip HTML for clean title display
                const stripHtml = (html) => {
                    let tmp = document.createElement("DIV");
                    tmp.innerHTML = html;
                    return tmp.textContent || tmp.innerText || "";
                };

                Swal.fire({
                    title: stripHtml(title),
                    html: htmlContent,
                    width: '550px',
                    showCloseButton: true,
                    showConfirmButton: true,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#4e73df',
                    customClass: {
                        popup: 'swal2-popup-custom'
                    },
                    didOpen: () => {
                        console.log('SweetAlert opened, checking for QR Code image...');
                        // Check if image exists after modal opens
                        setTimeout(() => {
                            const img = document.querySelector('.swal2-popup img[alt="QR Code"]');
                            if (img) {
                                console.log('QR Code image element found:', img.src);
                                img.addEventListener('load', () => {
                                    console.log('QR Code image loaded successfully');
                                });
                                img.addEventListener('error', (e) => {
                                    console.error('QR Code image failed to load:', e);
                                    console.error('Image src:', img.src);
                                });
                            } else {
                                console.warn('QR Code image element not found in modal');
                            }
                        }, 100);
                    }
                });
            } catch (error) {
                console.error('Error showing QR Code modal:', error);
                alert('Terjadi error saat menampilkan QR Code. Silakan coba lagi.');
            }
        };

        // Function to copy link to clipboard
        function copyLink(link) {
            const input = document.getElementById('formLinkInput');
            if (input) {
                input.select();
                input.setSelectionRange(0, 99999); // For mobile devices

                try {
                    document.execCommand('copy');

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Link berhasil disalin ke clipboard',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } catch (err) {
                    // Fallback for modern browsers
                    navigator.clipboard.writeText(link).then(function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Link berhasil disalin ke clipboard',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }).catch(function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Tidak dapat menyalin link',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    });
                }
            }
        }
    </script>
    <style>
        .swal2-popup-custom {
            border-radius: 0.75rem;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    </style>
    <?= $this->endSection() ?>