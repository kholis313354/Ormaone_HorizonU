<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Security Headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self' 'unsafe-inline' https:; style-src 'self' 'unsafe-inline' https:; font-src 'self' https:;");

// CSRF Protection
$csrf = csrf_token();

// Pagination Configuration - sudah dihitung di controller dengan query database yang optimal
$perPage = $perPage ?? 12;
$currentPage = $currentPage ?? 1;
$totalItems = $totalItems ?? 0;
$totalPages = $totalPages ?? 1;
// Data sudah dipaginasi di database level, langsung gunakan allSertifikat
$paginatedSertifikat = $allSertifikat ?? [];
?>

<!-- Hero Section -->
<section id="hero" class="hero section">
    <div class="hero-bg">
        <img src="<?= base_url('dist/landing/assets/img/bg_1.png') ?>" alt="" loading="eager" decoding="async" onerror="this.style.display='none'">
    </div>
    <div class="container text-center">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-6 text-center">
                    <img src="<?= base_url('dist/landing/assets/img/logo_horizon.png') ?>" width="20%" height="20px" class="img-fluid" alt="Sertifikat" loading="eager" decoding="async" onerror="this.style.display='none'">
                    <h2 style="color: #980517;">Horizon University Indonesia</h2>
                    <h2 style="color: #980517;">Verifikasi Sertifikat Digital</h2>
                    <p>Sistem verifikasi sertifikat digital yang aman dan terpercaya. Cari dan verifikasi sertifikat Anda dengan mudah.</p>
                    <!-- Form Pencarian -->
                    <div class="container mt-2">
                        <form method="GET" action="" class="d-flex justify-content-end mb-4">
                            <input type="hidden" name="<?= csrf_token() ?>" value="<?= $csrf ?>">
                            <input type="text" name="search" class="form-control me-2 search-bar"
                                placeholder="Cari nama lengkap..."
                                value="<?= esc($search ?? '') ?>"
                                pattern="[A-Za-z\s]{1,50}"
                                title="Hanya huruf dan spasi diperbolehkan (maksimal 50 karakter)"
                                maxlength="50"
                                required>
                            <button type="submit" class="btn" style="background-color: #980517; color:#fff;">Cari</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- /Hero Section -->
<br><br>

<!-- Filter Dropdown -->
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="GET" action="">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= $csrf ?>">
                <div class="input-group">
                    <select name="nama_sertifikat" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Jenis Sertifikat</option>
                        <?php if (isset($nama_sertifikat) && is_array($nama_sertifikat)): ?>
                            <?php foreach ($nama_sertifikat as $ns): ?>
                                <?php if (is_numeric($ns['id'])): ?>
                                    <option value="<?= esc($ns['id']) ?>" <?= (service('request')->getGet('nama_sertifikat') == $ns['id']) ? 'selected' : '' ?>>
                                        <?= esc($ns['nama_sertifikat']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <?php if (!empty($search)): ?>
                    <input type="hidden" name="search" value="<?= esc($search) ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<!-- ======= Gallery Section ======= -->
<!-- Gallery Section -->
<section id="gallery" class="gallery">
    <div class="container-fluid">
        <div class="row gy-4 justify-content-center">
            <?php if (!empty($paginatedSertifikat) && is_array($paginatedSertifikat)): ?>
                <?php foreach ($paginatedSertifikat as $sertifikat): ?>
                    <?php
                    // Validasi data sebelum ditampilkan
                    $validFile = preg_match('/^[a-zA-Z0-9_\-\.]+\.(jpg|jpeg|png|pdf)$/', $sertifikat['file']);
                    $validPath = realpath(FCPATH . 'uploads/sertifikat/' . $sertifikat['file']) !== false;
                    ?>
                    <?php if ($validFile && $validPath): ?>
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="gallery-item h-100">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Nama :<?= esc($sertifikat['nama_penerima']) ?></h5>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                Kegiatan :<?= esc($sertifikat['nama_kegiatan']) ?><br>
                                                sertifikat :<?= esc($sertifikat['nama_sertifikat']) ?><br>
                                                <?= esc($sertifikat['nama_fakultas']) ?>
                                            </small>
                                        </p>
                                    </div>
                                    <img src="<?= base_url('uploads/sertifikat/' . esc($sertifikat['file'])) ?>"
                                        class="card-img-bottom"
                                        alt="Sertifikat"
                                        style="height: 200px; object-fit: contain;"
                                        loading="lazy"
                                        decoding="async"
                                        onerror="this.style.display='none'">
                                    <div class="gallery-links d-flex align-items-center justify-content-center">
                                        <a href="<?= base_url('uploads/sertifikat/' . esc($sertifikat['file'])) ?>"
                                            title="<?= esc($sertifikat['nama_kegiatan']) ?>"
                                            class="glightbox preview-link"
                                            data-caption="<?= esc($sertifikat['nama_penerima']) ?>">
                                            <i class="bi bi-arrows-angle-expand"></i>
                                        </a>
                                    </div>
                                    <!-- Tombol Unduh -->
                                    <div class="card-footer p-2">
                                        <a href="<?= base_url('uploads/sertifikat/' . esc($sertifikat['file'])) ?>" 
                                           download="<?= esc($sertifikat['nama_penerima'] . '_' . esc($sertifikat['nama_kegiatan']) . '.' . pathinfo($sertifikat['file'], PATHINFO_EXTENSION)) ?>"
                                           class="btn btn-sm w-100" 
                                           style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;"
                                           title="Unduh Sertifikat">
                                            <i class="bi bi-download"></i> Unduh
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="alert alert-warning">
                        Tidak ada sertifikat ditemukan.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section><!-- End Gallery Section -->

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <?php
    // Logika untuk menampilkan maksimal 5 nomor halaman dengan ellipsis
    $maxVisible = 5;
    $startPage = 1;
    $endPage = $totalPages;
    
    if ($totalPages > $maxVisible) {
        if ($currentPage <= 3) {
            // Jika di awal (halaman 1-3), tampilkan 1-5
            $startPage = 1;
            $endPage = 5;
        } elseif ($currentPage >= $totalPages - 2) {
            // Jika di akhir, tampilkan 5 halaman terakhir
            $startPage = $totalPages - 4;
            $endPage = $totalPages;
        } else {
            // Jika di tengah, tampilkan 2 sebelum dan 2 setelah current
            $startPage = $currentPage - 2;
            $endPage = $currentPage + 2;
        }
    }
    ?>
    <div class="container mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-custom justify-content-center">
                <!-- Previous Button -->
                <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>" tabindex="-1">
                        <span class="pagination-text">Previous</span>
                        <span class="pagination-icon">‹</span>
                    </a>
                </li>

                <!-- First Page -->
                <?php if ($startPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a>
                    </li>
                    <?php if ($startPage > 2): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Last Page -->
                <?php if ($endPage < $totalPages): ?>
                    <?php if ($endPage < $totalPages - 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $totalPages])) ?>"><?= $totalPages ?></a>
                    </li>
                <?php endif; ?>

                <!-- Next Button -->
                <li class="page-item <?= ($currentPage == $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>">
                        <span class="pagination-text">Next</span>
                        <span class="pagination-icon">›</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <style>
        /* Pagination Custom Styles */
        .pagination-custom {
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .pagination-custom .page-item {
            margin: 0;
        }

        .pagination-custom .page-link {
            min-width: 40px;
            height: 40px;
            padding: 0.5rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .pagination-custom .page-link:hover:not(.disabled):not(.active) {
            background-color: #f8f9fa;
            border-color: #980517;
            color: #980517;
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(152, 5, 23, 0.2);
        }

        .pagination-custom .page-item.active .page-link {
            background-color: #A01D1D;
            border-color: #A01D1D;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(160, 29, 29, 0.3);
        }

        .pagination-custom .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #e9ecef;
            border-color: #dee2e6;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .pagination-custom .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
        }

        .pagination-custom .page-link .pagination-text {
            display: inline;
        }

        .pagination-custom .page-link .pagination-icon {
            display: none;
        }

        /* Tablet Styles */
        @media (max-width: 768px) {
            .pagination-custom .page-link {
                min-width: 36px;
                height: 36px;
                padding: 0.4rem 0.6rem;
                font-size: 0.9rem;
            }

            .pagination-custom .page-link .pagination-text {
                font-size: 0.85rem;
            }
        }

        /* Mobile Styles */
        @media (max-width: 576px) {
            .pagination-custom {
                gap: 0.15rem;
            }

            .pagination-custom .page-link {
                min-width: 32px;
                height: 32px;
                padding: 0.3rem 0.5rem;
                font-size: 0.8rem;
            }

            .pagination-custom .page-link .pagination-text {
                display: none;
            }

            .pagination-custom .page-link .pagination-icon {
                display: inline;
                font-size: 1.2rem;
                font-weight: bold;
            }

            /* Hide ellipsis on very small screens if needed */
            .pagination-custom .page-item.disabled .page-link {
                padding: 0.3rem 0.4rem;
            }
        }

        /* Extra Small Mobile */
        @media (max-width: 375px) {
            .pagination-custom .page-link {
                min-width: 28px;
                height: 28px;
                padding: 0.25rem 0.4rem;
                font-size: 0.75rem;
            }

            .pagination-custom .page-link .pagination-icon {
                font-size: 1rem;
            }
        }

        /* Focus states for accessibility */
        .pagination-custom .page-link:focus {
            outline: 2px solid #980517;
            outline-offset: 2px;
            z-index: 1;
        }
    </style>
<?php endif; ?>

<script nonce="<?= bin2hex(random_bytes(16)) ?>">
    // Inline script protection
    document.addEventListener('DOMContentLoaded', function() {
        // Validasi semua link eksternal
        document.querySelectorAll('a').forEach(link => {
            if (link.href && !link.href.startsWith('<?= base_url() ?>') && !link.href.startsWith('#')) {
                link.setAttribute('rel', 'noopener noreferrer');
                link.setAttribute('target', '_blank');
            }
        });
    });
</script>
<?= $this->endSection() ?>