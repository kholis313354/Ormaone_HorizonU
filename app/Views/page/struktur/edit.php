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
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
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
        width: 2.5rem; /* Ukuran sedang seperti search bar */
        height: 2.5rem; /* Ukuran sedang seperti search bar */
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
        font-size: 1.5rem; /* Ukuran sedang */
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
        
    }

    /* Tambahan styling khusus halaman struktur */
    .struktur-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background: #f8f9fa;
        position: relative;
    }
    
    .struktur-item h6 {
        color: #4e73df;
        font-weight: 700;
        margin-bottom: 20px;
        border-bottom: 2px solid #4e73df;
        padding-bottom: 10px;
        display: inline-block;
    }
    
    .preview-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 3px solid #dee2e6;
        display: block;
        margin-top: 10px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }

    /* Dark Mode Improvements */
    [data-bs-theme="dark"] .modern-search {
        background: #1e1e2d;
        border-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .modern-search input {
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .modern-search input::placeholder {
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .modern-search .search-shortcut {
        background: #2b2b40;
        border-color: #4b4b5a;
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .icon-btn {
        background: #1e1e2d;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .icon-btn:hover {
        background: #2b2b40;
    }
    
    [data-bs-theme="dark"] .user-dropdown-btn {
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .user-dropdown-btn:hover {
        background: #1e1e2d;
    }
    
    [data-bs-theme="dark"] .user-name, 
    [data-bs-theme="dark"] .user-chevron {
        color: #e2e2e2 !important;
    }

    /* Struktur Specific Dark Mode */
    [data-bs-theme="dark"] .card {
        background: #1e1e2d;
        border-color: #4b4b5a;
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .card-header {
        border-bottom-color: #4b4b5a;
        background: transparent;
    }
    
    [data-bs-theme="dark"] .card-title {
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .struktur-item {
        background: #2b2b40;
        border-color: #4b4b5a;
    }
    
    [data-bs-theme="dark"] .struktur-item h6 {
        color: #6993ff;
        border-bottom-color: #6993ff;
    }
    
    [data-bs-theme="dark"] .preview-image {
        border-color: #4b4b5a;
    }
    
    [data-bs-theme="dark"] .text-muted {
        color: #a0a0a0 !important;
    }
    
    /* Form controls in dark mode */
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background-color: #151521;
        border-color: #4b4b5a;
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background-color: #151521;
        border-color: #4e73df;
        color: #e2e2e2;
    }
    
    [data-bs-theme="dark"] .form-control[readonly] {
        background-color: #2b2b40 !important;
        color: #a0a0a0; 
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
                <a class="icon-btn" id="navbarDropdownChat" href="<?= url_to('pesan.index') ?>" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false" data-bs-auto-close="true">
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
                <a class="user-dropdown-btn" id="navbarDropdownUser" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                    <div class="user-avatar">
                        <?php 
                        $profilePhoto = session()->get('profile_photo');
                        if (!empty($profilePhoto) && file_exists(FCPATH . 'uploads/profile/' . $profilePhoto)) : ?>
                            <img src="<?= base_url('uploads/profile/' . $profilePhoto) ?>" alt="Profile">
                        <?php else : ?>
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
<header class="mb-3">
    <?php include_once(APPPATH . 'Views/components/layouts/partials/navbar.php'); ?>
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
                        <li class="breadcrumb-item"><a href="<?= url_to('struktur.index') ?>">Struktur</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Struktur Organisasi (Maksimal 6 Posisi)</h5>
            </div>
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>
                <form action="<?= url_to('struktur.update', $data['id']) ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="organisasi_id" class="form-label">Organisasi</label>
                        <?php 
                        $sessionOrgId = session()->get('organisasi_id');
                        $level = session()->get('level');
                        $currentOrgId = $data['organisasi_id'] ?? $sessionOrgId;
                        $organisasiName = '';
                        
                        // Prioritas 1: Ambil dari currentOrganisasi yang dikirim dari controller
                        if (isset($currentOrganisasi) && is_array($currentOrganisasi) && !empty($currentOrganisasi['name'])) {
                            $organisasiName = $currentOrganisasi['name'];
                            $currentOrgId = $currentOrganisasi['id'] ?? $currentOrgId;
                        } 
                        // Prioritas 2: Ambil dari array organisasis
                        elseif (isset($organisasis) && is_array($organisasis) && !empty($organisasis) && isset($organisasis[0])) {
                            if (isset($organisasis[0]['name'])) {
                                $organisasiName = $organisasis[0]['name'];
                                $currentOrgId = $organisasis[0]['id'] ?? $currentOrgId;
                            }
                        }
                        // Prioritas 3: Ambil dari data struktur yang sedang diedit
                        elseif (isset($data['organisasi_id']) && !empty($data['organisasi_id'])) {
                            $organisasiModel = new \App\Models\OrganisasiModel();
                            $orgFromData = $organisasiModel->find($data['organisasi_id']);
                            if ($orgFromData && isset($orgFromData['name'])) {
                                $organisasiName = $orgFromData['name'];
                                $currentOrgId = $data['organisasi_id'];
                            }
                        }
                        // Prioritas 4: Ambil langsung dari database
                        elseif (!empty($currentOrgId)) {
                            $organisasiModel = new \App\Models\OrganisasiModel();
                            $org = $organisasiModel->find($currentOrgId);
                            if ($org && isset($org['name'])) {
                                $organisasiName = $org['name'];
                            }
                        }
                        // Prioritas 5: Fallback dari users table
                        else {
                            $userId = session()->get('id');
                            if ($userId) {
                                $userModel = new \App\Models\UserModel();
                                $user = $userModel->select('organisasis.name as organisasi_name, users.organisasi_id')
                                    ->join('organisasis', 'organisasis.id = users.organisasi_id', 'left')
                                    ->where('users.id', $userId)
                                    ->first();
                                
                                if ($user && isset($user['organisasi_name']) && !empty($user['organisasi_name'])) {
                                    $organisasiName = $user['organisasi_name'];
                                    $currentOrgId = $user['organisasi_id'] ?? $currentOrgId;
                                }
                            }
                        }
                        
                        // Pastikan organisasi_id selalu ada dari data
                        if (empty($currentOrgId) && isset($data['organisasi_id'])) {
                            $currentOrgId = $data['organisasi_id'];
                        }
                        ?>
                        <?php if ($level == 1): ?>
                            <!-- SuperAdmin: pakai dropdown untuk pilih semua organisasi -->
                            <select name="organisasi_id" id="organisasi_id" class="form-select" required>
                                <option value="" disabled>Pilih Organisasi</option>
                                <?php if (isset($organisasis) && !empty($organisasis)): ?>
                                    <?php foreach ($organisasis as $org): ?>
                                        <option value="<?= $org['id'] ?>" <?= ($currentOrgId == $org['id']) ? 'selected' : '' ?>>
                                            <?= esc($org['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        <?php else: ?>
                            <!-- Admin/Anggota: tampilkan text field readonly -->
                            <?php if (!empty($organisasiName)): ?>
                                <input type="text" class="form-control" value="<?= esc($organisasiName) ?>" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            <?php else: ?>
                                <input type="text" class="form-control" value="Data organisasi tidak ditemukan" readonly style="background-color: #e9ecef; cursor: not-allowed; color: #dc3545;">
                            <?php endif; ?>
                            <?php if (!empty($currentOrgId)): ?>
                                <input type="hidden" name="organisasi_id" value="<?= (int)$currentOrgId ?>" required>
                            <?php else: ?>
                                <input type="hidden" name="organisasi_id" value="<?= isset($data['organisasi_id']) ? (int)$data['organisasi_id'] : '' ?>" required>
                                <small class="text-danger">PERINGATAN: Organisasi ID tidak ditemukan. Menggunakan ID dari data yang ada.</small>
                            <?php endif; ?>
                            <small class="text-muted">Organisasi otomatis terisi berdasarkan data user Anda</small>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tahun" class="form-label">Tahun</label>
                            <input type="text" class="form-control" id="tahun" name="tahun" 
                                   placeholder="Contoh: 2024/2025" 
                                   value="<?= $data['tahun'] ?? '' ?>" required maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label for="periode" class="form-label">Periode</label>
                            <input type="text" class="form-control" id="periode" name="periode" 
                                   placeholder="Contoh: Horizon University Student Council 2024/2025" 
                                   value="<?= $data['periode'] ?? '' ?>" maxlength="100">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="0" <?= (isset($data['is_active']) && $data['is_active'] == 0) ? 'selected' : '' ?>>Tidak Aktif</option>
                            <option value="1" <?= (isset($data['is_active']) && $data['is_active'] == 1) ? 'selected' : '' ?>>Aktif</option>
                        </select>
                    </div>
                    
                    <hr class="my-4">
                    <h5 class="mb-4">Data Struktur Organisasi (Maksimal 6 Posisi)</h5>
                    
                    <?php 
                    $posisiLabels = [
                        1 => 'Posisi 1 (President)',
                        2 => 'Posisi 2 (Vice President)',
                        3 => 'Posisi 3 (Secretary)',
                        4 => 'Posisi 4 (Treasurer)',
                        5 => 'Posisi 5 (PRO)',
                        6 => 'Posisi 6'
                    ];
                    
                    for ($i = 1; $i <= 6; $i++): 
                        $gambar = $data['gambar_' . $i] ?? '';
                        $nama = $data['nama_' . $i] ?? '';
                        $jabatan = $data['jabatan_' . $i] ?? '';
                        $prodi = $data['prodi_' . $i] ?? '';
                        $gambarUrl = !empty($gambar) ? base_url('uploads/struktur/' . $gambar) : '';
                    ?>
                    <div class="struktur-item">
                        <h6><?= $posisiLabels[$i] ?></h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="gambar_<?= $i ?>" class="form-label">Gambar</label>
                                    <?php if ($gambarUrl): ?>
                                        <div id="current_preview_<?= $i ?>" class="mb-2">
                                            <img src="<?= $gambarUrl ?>" class="preview-image" alt="Current Image">
                                            <small class="d-block text-center text-muted">Gambar saat ini</small>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="gambar_<?= $i ?>" name="gambar_<?= $i ?>" 
                                           accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this, <?= $i ?>)">
                                    <small class="text-muted">Format: JPG, PNG. Max 2MB. Kosongkan jika tidak ingin mengganti.</small>
                                    <div id="preview_<?= $i ?>" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="nama_<?= $i ?>" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama_<?= $i ?>" name="nama_<?= $i ?>" 
                                           placeholder="Nama lengkap" value="<?= esc($nama) ?>" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="jabatan_<?= $i ?>" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" id="jabatan_<?= $i ?>" name="jabatan_<?= $i ?>" 
                                           placeholder="Jabatan" value="<?= esc($jabatan) ?>" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="prodi_<?= $i ?>" class="form-label">Prodi</label>
                                    <input type="text" class="form-control" id="prodi_<?= $i ?>" name="prodi_<?= $i ?>" 
                                           placeholder="Program Studi" value="<?= esc($prodi) ?>" maxlength="100">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= url_to('struktur.index') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
function previewImage(input, posisi) {
    const preview = document.getElementById('preview_' + posisi);
    const currentPreview = document.getElementById('current_preview_' + posisi);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (currentPreview) {
                currentPreview.style.display = 'none';
            }
            preview.innerHTML = '<img src="' + e.target.result + '" class="preview-image" alt="New Preview">';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = '';
        if (currentPreview) {
            currentPreview.style.display = 'block';
        }
    }
}
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.2/dist/sweetalert2.all.min.js"></script>
<script>
// Tampilkan error dengan SweetAlert
<?php if (session()->getFlashdata('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        html: '<?= esc(session()->getFlashdata('error')) ?>',
        confirmButtonText: 'OK',
        confirmButtonColor: '#d33'
    });
<?php endif; ?>

// Tampilkan success dengan SweetAlert
<?php if (session()->getFlashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        html: '<?= esc(session()->getFlashdata('success')) ?>',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= url_to('struktur.index') ?>';
        }
    });
<?php endif; ?>
</script>

<?= $this->endSection() ?>
