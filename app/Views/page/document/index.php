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
        object-position: center;
        display: block;
        position: absolute;
        top: 0;
        left: 0;
    }

    .user-avatar i {
        font-size: 1.5rem;
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
            min-width: 2.5rem;
            min-height: 2.5rem;
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
            min-width: 2.5rem;
            min-height: 2.5rem;
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
            min-width: 2.25rem;
            min-height: 2.25rem;
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
            <a href="#" class="burger-btn d-block d-xl-none" id="sidebarToggle">
                <i class="bi bi-justify fs-3"></i>
            </a>
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
        <?php
        // Ensure all variables are defined
        $documents = $documents ?? [];
        $availableYears = $availableYears ?? [];
        $selectedYear = $selectedYear ?? date('Y');
        $selectedKategori = $selectedKategori ?? null;
        $level = $level ?? 0;
        $stats = $stats ?? [];
        ?>
        <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
        <?php include_once(APPPATH . 'Views/components/flash.php'); ?>

        <?php if (in_array($level, [1, 2])): ?>
            <!-- Statistik untuk Level 1 dan 2 -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="stat-info">
                                    <h6 class="text-muted">Total Document</h6>
                                    <h4><?= $stats['total'] ?? 0 ?></h4>
                                </div>
                                <div class="avatar bg-primary text-white">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php foreach ($stats['byCategory'] ?? [] as $cat): ?>
                    <div class="col-md-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="stat-info">
                                        <h6 class="text-muted"><?= $cat['kategori'] ?></h6>
                                        <h4><?= $cat['count'] ?></h4>
                                    </div>
                                    <div class="avatar bg-info text-white">
                                        <i class="bi bi-folder"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Grafik untuk Level 1 dan 2 -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Trent Penerbitan Document</h4>
                            <select id="yearSelect" class="form-select form-select-sm" style="width: 100px;">
                                <option value="<?= $selectedYear ?>"><?= $selectedYear ?></option>
                            </select>
                        </div>
                        <div class="card-body">
                            <canvas id="trenChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Distribusi Per Kategori</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="distribusiChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tabel Document -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        <?php if (isset($selectedKategori) && !empty($selectedKategori)): ?>
                            Daftar Document - <?= esc($selectedKategori) ?>
                        <?php else: ?>
                            Daftar Document
                        <?php endif; ?>
                    </h4>
                    <div class="d-flex align-items-center gap-2">
                        <label for="filterTahun" class="form-label mb-0">Filter Tahun:</label>
                        <select id="filterTahun" class="form-select" style="width: auto; min-width: 150px;">
                            <option value="all" <?= ($selectedYear == 'all') ? 'selected' : '' ?>>Semua Tahun</option>
                            <?php foreach ($availableYears as $year): ?>
                                <option value="<?= $year ?>" <?= ($selectedYear == $year) ? 'selected' : '' ?>>
                                    <?= $year ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (in_array($level, [1, 2, 0])): ?>
                            <a href="<?= url_to('document.create') . ($selectedKategori ? '?kategori=' . urlencode($selectedKategori) : '') ?>"
                                class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Document
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table1" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Tahun</th>
                                <th>Uploader</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php if (!empty($documents)): ?>
                                <?php foreach ($documents as $doc): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($doc['judul']) ?></td>
                                        <td><?= esc($doc['kategori']) ?></td>
                                        <td><?= esc($doc['tahun']) ?></td>
                                        <td><?= esc($doc['user_name']) ?></td>
                                        <td><?= esc($doc['user_email']) ?></td>
                                        <td>
                                            <?php
                                            $roleLabels = [0 => 'Anggota', 1 => 'Admin', 2 => 'SuperAdmin'];
                                            echo $roleLabels[$doc['user_level']] ?? 'Unknown';
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($doc['file_path']): ?>
                                                <a href="<?= base_url($doc['file_path']) ?>" target="_blank"
                                                    class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> Lihat
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Tidak Ada File</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= url_to('document.download', $doc['id']) ?>"
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-download"></i> Unduh
                                            </a>
                                            <?php if (in_array($level, [1, 2]) || ($level == 0 && $doc['user_id'] == session()->get('id'))): ?>
                                                <a href="<?= url_to('document.edit', $doc['id']) ?>" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                            <?php endif; ?>
                                            <?php if (in_array($level, [1, 2]) || ($level == 0 && $doc['user_id'] == session()->get('id'))): ?>
                                                <form action="<?= url_to('document.delete', $doc['id']) ?>" method="POST"
                                                    class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus document ini?')">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data document</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<?php if (in_array($level, [1, 2])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryData = <?= json_encode($stats['byCategory'] ?? []) ?>;
            let currentYear = <?= $selectedYear ?>;
            let availableYears = <?= json_encode($availableYears) ?>;
            let trenChart, distribusiChart;

            function getMonthName(month) {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return months[month - 1] || '';
            }

            async function fetchMonthlyData(year) {
                try {
                    const response = await axios.get(`<?= base_url('api/document/monthly-data') ?>?year=${year}`);
                    return response.data;
                } catch (error) {
                    console.error('Error fetching monthly data:', error);
                    return Array.from({ length: 12 }, (_, i) => ({
                        month: i + 1,
                        count: 0,
                        year: year
                    }));
                }
            }

            async function updateTrenChart(year) {
                const monthlyData = await fetchMonthlyData(year);
                const actualCurrentYear = new Date().getFullYear();
                const isActualCurrentYear = year == actualCurrentYear;
                const currentMonth = new Date().getMonth() + 1;

                const labels = [];
                const dataValues = [];
                const backgroundColors = [];
                const borderColors = [];

                const maxMonth = isActualCurrentYear ? currentMonth : 12;

                monthlyData.sort((a, b) => a.month - b.month);

                for (let month = 1; month <= maxMonth; month++) {
                    const monthData = monthlyData.find(item => item.month == month) || { count: 0 };

                    labels.push(getMonthName(month));
                    dataValues.push(monthData.count);

                    if (isActualCurrentYear && month == currentMonth) {
                        backgroundColors.push('rgba(75, 192, 192, 0.7)');
                        borderColors.push('rgba(75, 192, 192, 1)');
                    } else {
                        backgroundColors.push('rgba(54, 162, 235, 0.5)');
                        borderColors.push('rgba(54, 162, 235, 1)');
                    }
                }

                if (trenChart) {
                    trenChart.data.labels = labels;
                    trenChart.data.datasets[0].data = dataValues;
                    trenChart.data.datasets[0].backgroundColor = backgroundColors;
                    trenChart.data.datasets[0].borderColor = borderColors;
                    trenChart.update();
                }
            }

            function initializeTrenChart() {
                const trenCtx = document.getElementById('trenChart').getContext('2d');
                trenChart = new Chart(trenCtx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Jumlah Document',
                            data: [],
                            backgroundColor: [],
                            borderColor: [],
                            borderWidth: 1,
                            borderRadius: 4,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    callback: function (value) {
                                        return Number.isInteger(value) ? value : '';
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const actualCurrentYear = new Date().getFullYear();
                                        const isActualCurrentYear = currentYear == actualCurrentYear;
                                        const currentMonth = new Date().getMonth() + 1;
                                        const isCurrentMonth = (context.dataIndex === currentMonth - 1);

                                        return `Jumlah: ${context.raw}${isActualCurrentYear && isCurrentMonth ? ' (Bulan Ini)' : ''}`;
                                    },
                                    afterLabel: function (context) {
                                        return `Periode ${currentYear}`;
                                    }
                                }
                            },
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            function initializeDistribusiChart() {
                const distribusiCtx = document.getElementById('distribusiChart').getContext('2d');
                distribusiChart = new Chart(distribusiCtx, {
                    type: 'pie',
                    data: {
                        labels: categoryData.map(item => item.kategori),
                        datasets: [{
                            data: categoryData.map(item => item.count),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            document.getElementById('yearSelect')?.addEventListener('change', function () {
                currentYear = parseInt(this.value);
                updateTrenChart(currentYear);
            });

            async function initializeAll() {
                initializeTrenChart();
                initializeDistribusiChart();
                await updateTrenChart(currentYear);
            }

            initializeAll();
        });
    </script>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterTahun = document.getElementById('filterTahun');

        filterTahun.addEventListener('change', function () {
            const tahun = this.value;
            const url = new URL(window.location.href);

            if (tahun === 'all') {
                url.searchParams.delete('tahun');
            } else {
                url.searchParams.set('tahun', tahun);
            }

            // Preserve kategori filter if exists
            const kategori = url.searchParams.get('kategori');
            if (kategori) {
                url.searchParams.set('kategori', kategori);
            }

            window.location.href = url.toString();
        });
    });
</script>
<?= $this->endSection() ?>