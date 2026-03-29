<?= $this->extend('components/layouts/app') ?>

<?= $this->section('title') ?>Keamanan Website<?= $this->endSection() ?>

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
        text-decoration: none;
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

    /* Styling untuk icon buttons dengan circular background */
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

    /* Notification badge */
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

    /* Chat badge */
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

    /* User dropdown */
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

    /* Layout header */
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

    /* Responsive styles - simplified */
    @media (max-width: 768px) {
        .modern-search {
            width: auto;
        }

        .user-name {
            display: none;
        }
    }
</style>

<header class="mb-3">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <!-- Bagian Kiri: Hamburger + Search -->
        <div class="header-left">
            <a href="#" class="burger-btn d-block d-xl-none" id="sidebarToggle">
                <i class="bi bi-justify fs-3"></i>
            </a>
            <div class="d-none d-md-inline-block">
                <div class="modern-search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search..." aria-label="Search" id="modernSearchInput" />
                    <button type="button" class="search-shortcut">⌘K</button>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: Chat, Notification, User -->
        <div class="header-right">
            <!-- Chat Dropdown -->
            <div class="dropdown icon-btn-wrapper">
                <a class="icon-btn" id="navbarDropdownChat" href="<?= url_to('pesan.index') ?>" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-comment-dots"></i>
                    <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                        <span class="chat-badge"><?= $unreadCount > 99 ? '99+' : $unreadCount ?></span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownChat">
                    <li><a class="dropdown-item" href="<?= url_to('pesan.index') ?>">Aspirasi Mahasiswa</a></li>
                </ul>
            </div>

            <!-- Notification Dropdown -->
            <?= view_cell('App\Cells\NotificationCell::render') ?>

            <!-- User Dropdown -->
            <div class="dropdown">
                <a class="user-dropdown-btn" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
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
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                    <li><a class="dropdown-item" href="<?= url_to('profile.edit') ?>">Profile</a></li>
                    <li><a class="dropdown-item" href="<?= url_to('settings.index') ?>">Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="<?= url_to('logout') ?>">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
<style>
    /* Styling card dengan efek hover */
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

    .avatar {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .avatar i {
        font-size: 1.5rem;
    }

    /* Styling untuk tabel dengan border tebal */
    #table1,
    #tableBlocked {
        border: 2px solid #dee2e6 !important;
    }

    #table1 th,
    #table1 td,
    #tableBlocked th,
    #tableBlocked td {
        border: 1px solid #dee2e6 !important;
        vertical-align: middle;
    }

    #table1 thead th,
    #tableBlocked thead th {
        border-bottom: 2px solid #dee2e6 !important;
        background-color: #f8f9fa;
    }

    /* Tab Styling */
    .nav-tabs .nav-link {
        color: #607080;
        font-weight: 500;
    }

    .nav-tabs .nav-link.active {
        font-weight: bold;
        color: #435ebe;
    }
</style>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Keamanan Website & Log Serangan</h3>
                <p class="text-subtitle text-muted">Monitoring serangan, scanning, dan kesehatan website Real-time.</p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row">
        <!-- Serangan Hari Ini -->
        <div class="col-md-4 col-sm-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-info">
                            <h6 class="text-muted">Serangan Hari Ini</h6>
                            <h4 class="font-extrabold mb-0 text-danger"><?= $stats['total_today'] ?></h4>
                        </div>
                        <div class="stat-icon">
                            <div class="avatar bg-light-danger p-2">
                                <div class="avatar-content">
                                    <i class="bi bi-shield-exclamation text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Minggu Ini -->
        <div class="col-md-4 col-sm-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-info">
                            <h6 class="text-muted">Total Minggu Ini</h6>
                            <h4 class="font-extrabold mb-0 text-primary"><?= $stats['total_week'] ?></h4>
                        </div>
                        <div class="stat-icon">
                            <div class="avatar bg-light-primary p-2">
                                <div class="avatar-content">
                                    <i class="bi bi-calendar-week text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Website -->
        <div class="col-md-4 col-sm-12">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="stat-info">
                            <h6 class="text-muted">Status Website</h6>
                            <h4 class="font-extrabold mb-0 text-success">Aman Terkendali</h4>
                        </div>
                        <div class="stat-icon">
                            <div class="avatar bg-light-success p-2">
                                <div class="avatar-content">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <i class="bi bi-check-circle me-1"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" id="securityTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="logs-tab" data-bs-toggle="tab" href="#logs" role="tab"
                                aria-controls="logs" aria-selected="true"><i class="bi bi-list-ul me-1"></i> Log
                                Keamanan</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="blocked-tab" data-bs-toggle="tab" href="#blocked" role="tab"
                                aria-controls="blocked" aria-selected="false"><i class="bi bi-slash-circle me-1"></i> IP
                                Diblokir</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content" id="securityTabsContent">

                    <!-- TAB LOGS -->
                    <div class="tab-pane fade show active" id="logs" role="tabpanel" aria-labelledby="logs-tab">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-bordered" id="table1" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Waktu</th>
                                        <th>IP Address</th>
                                        <th>Tipe Event</th>
                                        <!--<th>Request URL</th>-->
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $log['created_at'] ?></td>
                                            <td>
                                                <span
                                                    class="badge bg-light-secondary text-secondary font-bold"><?= esc($log['ip_address']) ?></span>
                                            </td>
                                            <td>
                                                <?php
                                                $badgeClass = 'bg-secondary';
                                                if ($log['event_type'] == 'Blocked Attack')
                                                    $badgeClass = 'bg-danger';
                                                elseif ($log['event_type'] == '404 Not Found')
                                                    $badgeClass = 'bg-warning text-dark';
                                                elseif ($log['event_type'] == 'System Error')
                                                    $badgeClass = 'bg-dark';
                                                elseif ($log['event_type'] == 'Invalid View')
                                                    $badgeClass = 'bg-info text-dark';
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= esc($log['event_type']) ?></span>
                                            </td>
                                            <!--<td class="text-break" style="max-width: 250px;">-->
                                            <!--    <small-->
                                            <!--        class="d-block text-muted mb-1"><?= esc($log['request_method']) ?></small>-->
                                            <!--    <small-->
                                            <!--        class="font-monospace text-primary"><?= esc($log['request_url']) ?></small>-->
                                            <!--</td>-->
                                            <td class="text-break">
                                                <small><?= esc($log['description']) ?></small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="modal" data-bs-target="#blockModal"
                                                        data-ip="<?= esc($log['ip_address']) ?>" title="Block IP">
                                                        <i class="bi bi-slash-circle"></i>
                                                    </button>
                                                    <form action="<?= base_url('admin/keamanan/delete/' . $log['id']) ?>"
                                                        method="post" class="d-inline"
                                                        onsubmit="return confirm('Hapus log ini?')">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Hapus Log">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($logs)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">Belum ada log serangan
                                                tersimpan. Aman!</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB BLOCKED -->
                    <div class="tab-pane fade" id="blocked" role="tabpanel" aria-labelledby="blocked-tab">
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered" id="tableBlocked">
                                <thead>
                                    <tr>
                                        <th>IP Address</th>
                                        <!--<th>Alasan</th>-->
                                        <th>Diblokir Oleh</th>
                                        <th>Waktu Blokir</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($blocked_ips as $blocked): ?>
                                        <tr>
                                            <td><span class="badge bg-danger"><?= esc($blocked['ip_address']) ?></span></td>
                                            <!--<td><?= esc($blocked['reason']) ?></td>-->
                                            <td><span
                                                    class="badge bg-light-info text-info"><?= esc($blocked['blocked_by']) ?></span>
                                            </td>
                                            <td><?= $blocked['created_at'] ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/keamanan/unblock/' . $blocked['id']) ?>"
                                                    class="btn btn-sm btn-success"
                                                    onclick="return confirm('Buka blokir IP ini?')">
                                                    <i class="bi bi-unlock"></i> Unblock
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($blocked_ips)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada IP yang sedang
                                                diblokir.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Block IP -->
<div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/keamanan/block') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white" id="blockModalLabel"><i
                            class="bi bi-slash-circle me-2"></i>Blokir IP Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="ip_address" class="form-label fw-bold">Target IP Address</label>
                        <input type="text" class="form-control form-control-lg bg-light" id="ip_address"
                            name="ip_address" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label fw-bold">Alasan Blokir</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required
                            placeholder="Contoh: Spamming, Scanning, Mencurigakan...">Mendeteksi aktivitas mencurigakan / Spam.</textarea>
                    </div>
                    <div class="alert alert-warning">
                        <small><i class="bi bi-info-circle me-1"></i> IP yang diblokir tidak akan bisa mengakses seluruh
                            website ini.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Blokir IP Ini Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script to fill modal with IP
    var blockModal = document.getElementById('blockModal')
    blockModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        var ip = button.getAttribute('data-ip')
        var modalInput = blockModal.querySelector('.modal-body input#ip_address')
        modalInput.value = ip
    })
</script>

<?= $this->section('scripts') ?>
<script src="<?= base_url('dist/assets/extensions/simple-datatables/umd/simple-datatables.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Blocked IPs Table
        let tableBlocked = document.getElementById("tableBlocked");
        if (tableBlocked) {
            new simpleDatatables.DataTable(tableBlocked);
        }
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>