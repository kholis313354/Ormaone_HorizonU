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
    #table1,
    #table2,
    #table3,
    #table4 {
        border: 2px solid #dee2e6 !important;
    }

    #table1 th,
    #table1 td,
    #table2 th,
    #table2 td,
    #table3 th,
    #table3 td,
    #table4 th,
    #table4 td {
        border: 1px solid #dee2e6 !important;
    }

    #table1 thead th,
    #table2 thead th,
    #table3 thead th,
    #table4 thead th {
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
                    <input type="text" placeholder="Search car woi" aria-label="Search" id="modernSearchInput" />
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

<?php
// Set default timezone ke Asia/Jakarta untuk semua operasi date
date_default_timezone_set('Asia/Jakarta');

// Helper function untuk format tanggal dengan timezone Asia/Jakarta
function formatDateTimeIndonesia($datetimeString)
{
    try {
        $dateTime = new \DateTime($datetimeString);
        return $dateTime->format('d/m/Y H:i');
    } catch (\Exception $e) {
        return date('d/m/Y H:i', strtotime($datetimeString));
    }
}

// Extract unique years from all datasets
$allYears = [];
$datasets = [$data ?? [], $divisiData ?? [], $visiMisiData ?? [], $prokerData ?? []];

// Map organization active status by org_id and tahun
$orgStatusMap = [];
if (isset($data) && is_array($data)) {
    foreach ($data as $item) {
        $orgStatusMap[$item['organisasi_id']][$item['tahun']] = $item['is_active'];
    }
}

foreach ($datasets as $dataset) {
    if (is_array($dataset)) {
        foreach ($dataset as $item) {
            if (isset($item['tahun']) && !empty($item['tahun'])) {
                $allYears[] = $item['tahun'];
            }
        }
    }
}

$allYears = array_unique($allYears);
rsort($allYears); // Sort descending (newest first)
?>

<div class="page-heading">
    <div class="page-title">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Struktur Organisasi</h3>
                <p class="text-subtitle text-muted">Kelola data struktur organisasi, divisi, visi misi, dan program
                    kerja.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first text-md-end">
                <div class="form-group d-inline-block" style="min-width: 200px;">
                    <label for="yearFilter" class="form-label d-none">Filter Tahun</label>
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white"><i class="bi bi-calendar-event"></i></span>
                        <select class="form-select" id="yearFilter">
                            <option value="all">Semua Tahun</option>
                            <?php foreach ($allYears as $year): ?>
                                <option value="<?= $year ?>"><?= $year ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Data Struktur Organisasi</h4>
                        <?php
                        $level = session()->get('level');
                        // Tampilkan tombol Tambah untuk level 0 (Anggota Organisasi) dan level 2 (Admin)
                        if (in_array($level, [0, 2])):
                            ?>
                            <div class="d-flex gap-2">
                                <a href="<?= url_to('struktur.create') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Tambah Struktur
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered w-100" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Organisasi</th>
                                <th>Periode</th>
                                <th>Tahun</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data) || count($data) == 0): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No entries found</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; ?>
                                <?php foreach ($data as $item): ?>
                                    <tr class="data-row" data-year="<?= esc($item['tahun']) ?>">
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($item['organisasi_name'] ?? '-') ?></td>
                                        <td><?= esc($item['periode'] ?? '-') ?></td>
                                        <td><?= esc($item['tahun']) ?></td>
                                        <td>
                                            <?php if ($item['is_active'] == 1): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= formatDateTimeIndonesia($item['created_at']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $level = session()->get('level');
                                            // Tampilkan tombol Edit dan Delete untuk level 0 (Anggota Organisasi) dan level 2 (Admin)
                                            if (in_array($level, [0, 2])):
                                                ?>
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="<?= url_to('struktur.edit', $item['id']) ?>"
                                                        class="btn btn-primary btn-sm" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form action="<?= url_to('struktur.delete', $item['id']) ?>" method="POST"
                                                        class="d-inline">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                            onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">View Only</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Anggaran -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Data Anggaran</h4>
                        <?php if (in_array($level, [0, 2])): ?>
                            <a href="<?= url_to('struktur.anggaran.create') ?>" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Tambah Anggaran
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100" id="table4">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Organisasi</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th>Total Anggaran</th>
                                    <th>Dana Berkurang</th>
                                    <th>Sisa Saldo</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($anggaranData) || count($anggaranData) == 0): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No entries found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($anggaranData as $item): ?>
                                        <tr class="data-row" data-year="<?= esc($item['tahun']) ?>">
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($item['organisasi_name'] ?? '-') ?></td>
                                            <td><?= esc($item['tahun']) ?></td>
                                            <td>
                                                <?php
                                                $isActive = $orgStatusMap[$item['organisasi_id']][$item['tahun']] ?? 0;
                                                echo $isActive == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>';
                                                ?>
                                            </td>
                                            <td>Rp <?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                                            <td>Rp <?= number_format($item['dana_berkurang'], 0, ',', '.') ?></td>
                                            <td>Rp <?= number_format($item['jumlah'] - $item['dana_berkurang'], 0, ',', '.') ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if (in_array($level, [0, 2])): ?>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="<?= url_to('struktur.anggaran.edit', $item['id']) ?>"
                                                            class="btn btn-primary btn-sm" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <form action="<?= url_to('struktur.anggaran.delete', $item['id']) ?>"
                                                            method="POST" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">View Only</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabel Struktur Divisi -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Data Struktur Divisi</h4>
                        <?php if (in_array($level, [0, 2])): ?>
                            <a href="<?= url_to('struktur.divisi.create') ?>" class="btn btn-info">
                                <i class="bi bi-plus-circle"></i> Tambah Divisi
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover w-100" id="table2">
                            <thead>
                                <tr class="table-info text-center">
                                    <th style="width: 5%">No</th>
                                    <th>Organisasi</th>
                                    <th>Nama Divisi</th>
                                    <th>Periode</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th style="width: 15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($divisiData) || count($divisiData) == 0): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No entries found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($divisiData as $item): ?>
                                        <tr class="data-row" data-year="<?= esc($item['tahun']) ?>">
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($item['organisasi_name'] ?? '-') ?></td>
                                            <td><?= esc($item['nama_divisi']) ?></td>
                                            <td><?= esc($item['periode'] ?? '-') ?></td>
                                            <td><?= esc($item['tahun']) ?></td>
                                            <td>
                                                <?php
                                                $isActive = $orgStatusMap[$item['organisasi_id']][$item['tahun']] ?? 0;
                                                echo $isActive == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>';
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $level = session()->get('level');
                                                if (in_array($level, [0, 2])):
                                                    ?>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="<?= url_to('struktur.divisi.edit', $item['id']) ?>"
                                                            class="btn btn-primary btn-sm" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <form action="<?= url_to('struktur.divisi.delete', $item['id']) ?>"
                                                            method="POST" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">View Only</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabel Visi Misi -->
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Data Visi Misi</h4>
                        <?php if (in_array($level, [0, 2])): ?>
                            <a href="<?= url_to('struktur.visimisi.create') ?>" class="btn btn-warning text-white">
                                <i class="bi bi-plus-circle"></i> Tambah Visi Misi
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100" id="table2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Organisasi</th>
                                    <th>Tahun</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($visiMisiData) || count($visiMisiData) == 0): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No entries found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($visiMisiData as $item): ?>
                                        <tr class="data-row" data-year="<?= esc($item['tahun']) ?>">
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($item['organisasi_name'] ?? '-') ?></td>
                                            <td><?= esc($item['tahun']) ?></td>
                                            <td><?= esc($item['periode'] ?? '-') ?></td>
                                            <td>
                                                <?php
                                                $isActive = $orgStatusMap[$item['organisasi_id']][$item['tahun']] ?? 0;
                                                echo $isActive == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>';
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if (in_array($level, [0, 2])): ?>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="<?= url_to('struktur.visimisi.edit', $item['id']) ?>"
                                                            class="btn btn-primary btn-sm" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <form action="<?= url_to('struktur.visimisi.delete', $item['id']) ?>"
                                                            method="POST" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">View Only</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Tabel Program Kerja -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Data Program Kerja</h4>
                    <?php if (in_array($level, [0, 2])): ?>
                        <a href="<?= url_to('struktur.proker.create') ?>" class="btn btn-primary float-end">
                            <i class="bi bi-plus-circle"></i> Tambah Proker
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100" id="table3">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Organisasi</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($prokerData) || count($prokerData) == 0): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No entries found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($prokerData as $item): ?>
                                        <tr class="data-row" data-year="<?= esc($item['tahun']) ?>">
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($item['judul']) ?></td>
                                            <td><?= esc($item['organisasi_name'] ?? '-') ?></td>
                                            <td><?= esc($item['tahun']) ?></td>
                                            <td>
                                                <?php
                                                $isActive = $orgStatusMap[$item['organisasi_id']][$item['tahun']] ?? 0;
                                                echo $isActive == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Tidak Aktif</span>';
                                                echo ' <span class="badge bg-info">' . esc($item['is_active']) . '</span>';
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if (in_array($level, [0, 2])): ?>
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="<?= url_to('struktur.proker.edit', $item['id']) ?>"
                                                            class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
                                                        <form action="<?= url_to('struktur.proker.delete', $item['id']) ?>"
                                                            method="POST" class="d-inline">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Hapus data?')"><i
                                                                    class="bi bi-trash"></i></button>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const yearFilter = document.getElementById('yearFilter');
        const dataRows = document.querySelectorAll('.data-row');

        // Function to filter rows
        function filterRows() {
            const selectedYear = yearFilter.value;

            dataRows.forEach(row => {
                const rowYear = row.getAttribute('data-year');

                if (selectedYear === 'all' || rowYear === selectedYear) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Add event listener
        yearFilter.addEventListener('change', filterRows);

        // Trigger filter on load (optional: default to first option)
        // filterRows(); 
    });
</script>

<?= $this->endSection() ?>