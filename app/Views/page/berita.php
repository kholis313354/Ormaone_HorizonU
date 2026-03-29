<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?><?= htmlspecialchars($title ?? '', ENT_QUOTES, 'UTF-8') ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Load helper untuk encrypt ID
helper('nav');

// Security Headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; font-src 'self' https:;");

// CSRF Protection
$csrf = csrf_token();
?>
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token">

<header class="py-5 bg-light border-bottom mb-4">
    <div class="container">
        <div class="text-center my-5">
            <h1 class="fw-bolder">Welcome to Blog Ormaone</h1>
            <p class="lead mb-0">Program - program Kegiatan dari Semua Organisasi Mahasiswa di Universitas Horizon Indonesia</p>
        </div>
    </div>
</header>

<div class="container">
    <div class="row">
        <!-- Container untuk postingan terbaru (full width) -->
        <div class="col-lg-8" id="latest-posts-container">
            <?php if (!empty($beritas) && count($beritas) > 0) : ?>
                <?php
                // Ambil postingan terbaru (pertama dalam array)
                $latestPost = $beritas[0];
                // Validate and sanitize data
                $latestPost['nama_kegiatan'] = isset($latestPost['nama_kegiatan']) ? htmlspecialchars($latestPost['nama_kegiatan'], ENT_QUOTES, 'UTF-8') : '';
                $latestPost['deskripsi1'] = isset($latestPost['deskripsi1']) ? htmlspecialchars($latestPost['deskripsi1'], ENT_QUOTES, 'UTF-8') : '';
                ?>
                <div class="card mb-4">
                    <?php if (isset($latestPost['gambar'])) : ?>
                        <?php
                        // Validate image path
                        $imagePath = 'uploads/berita/' . $latestPost['gambar'];
                        if (file_exists(FCPATH . $imagePath) && is_file(FCPATH . $imagePath)) : ?>
                            <img class="card-img-top" src="<?= base_url($imagePath) ?>" alt="<?= esc($latestPost['nama_kegiatan']) ?>" style="max-height: 300px; object-fit: cover;" loading="lazy" decoding="async" onerror="this.src='<?= base_url('assets/img/default-image.jpg') ?>';">
                        <?php else : ?>
                            <img class="card-img-top" src="<?= base_url('assets/img/default-image.jpg') ?>" alt="Default image" style="max-height: 300px; object-fit: cover;" loading="lazy" decoding="async">
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="small text-muted">
                            <?= isset($latestPost['tanggal']) ? date('d F Y', strtotime($latestPost['tanggal'])) : '' ?> |
                            <?= isset($latestPost['nama_fakultas']) ? esc($latestPost['nama_fakultas']) : '' ?>
                        </div>
                        <h2 class="card-title"><?= esc($latestPost['nama_kegiatan']) ?></h2>
                        <p class="card-text"><?= substr(esc($latestPost['deskripsi1']), 0, 200) ?>...</p>
                        <a class="btn" style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;" href="<?= base_url('berita/detail/' . (isset($latestPost['id']) ? encrypt_berita_id((int)$latestPost['id']) : '')) ?>">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </div>
            <?php else : ?>
                <div class="alert alert-info">
                    <h4>Tidak ada berita yang tersedia</h4>
                    <p>Silakan kembali lagi nanti atau hubungi administrator untuk informasi lebih lanjut.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <!-- Search widget -->
            <div class="card mb-4">
                <div class="card-header">Cari Berita</div>
                <div class="card-body">
                    <form action="<?= base_url('berita/search') ?>" method="get" id="searchForm">
                        <div class="input-group">
                            <input class="form-control" type="text" name="q" placeholder="Masukkan kata kunci..." maxlength="100" required>
                            <button class="btn" style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Categories widget -->
            <div class="card mb-4">
                <div class="card-header">Filter Kategori</div>
                <div class="card-body">
                    <form method="get" action="<?= base_url('berita') ?>" id="kategoriFilterForm">
                        <div class="mb-3">
                            <label for="kategoriFilter" class="form-label">Pilih Kategori</label>
                            <select class="form-select" id="kategoriFilter" name="kategori" onchange="document.getElementById('kategoriFilterForm').submit();">
                                <option value="" <?= empty($selectedKategori) ? 'selected' : '' ?>>Semua Kategori</option>
                                <option value="blogger" <?= $selectedKategori == 'blogger' ? 'selected' : '' ?>>Blogger</option>
                                <option value="podcast" <?= $selectedKategori == 'podcast' ? 'selected' : '' ?>>Podcast</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Container untuk postingan lama (half width) -->
    <div class="row" id="older-posts-container">
        <?php if (!empty($beritas) && count($beritas) > 1) : ?>
            <?php
            // Hapus postingan terbaru dari array
            array_shift($beritas);
            $perPage = 4;
            $totalPosts = count($beritas);
            $totalPages = ceil($totalPosts / $perPage);
            $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $offset = ($currentPage - 1) * $perPage;
            $paginatedPosts = array_slice($beritas, $offset, $perPage);

            foreach ($paginatedPosts as $post) :
                // Validate and sanitize data
                $post['nama_kegiatan'] = isset($post['nama_kegiatan']) ? htmlspecialchars($post['nama_kegiatan'], ENT_QUOTES, 'UTF-8') : '';
                $post['deskripsi1'] = isset($post['deskripsi1']) ? htmlspecialchars($post['deskripsi1'], ENT_QUOTES, 'UTF-8') : '';
            ?>
                <div class="col-lg-3 mb-4">
                    <div class="card h-100">
                        <?php if (isset($post['gambar'])) : ?>
                            <?php
                            $imagePath = 'uploads/berita/' . $post['gambar'];
                            if (file_exists(FCPATH . $imagePath) && is_file(FCPATH . $imagePath)) : ?>
                                <img class="card-img-top" src="<?= base_url($imagePath) ?>" alt="<?= esc($post['nama_kegiatan']) ?>" style="max-height: 200px; object-fit: cover;" loading="lazy" decoding="async" onerror="this.src='<?= base_url('assets/img/default-image.jpg') ?>';">
                            <?php else : ?>
                                <img class="card-img-top" src="<?= base_url('assets/img/default-image.jpg') ?>" alt="Default image" style="max-height: 200px; object-fit: cover;" loading="lazy" decoding="async">
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="small text-muted">
                                <?= isset($post['tanggal']) ? date('d F Y', strtotime($post['tanggal'])) : '' ?> |
                                <?= isset($post['nama_fakultas']) ? esc($post['nama_fakultas']) : '' ?>
                            </div>
                            <h2 class="card-title h5"><?= esc($post['nama_kegiatan']) ?></h2>
                            <p class="card-text"><?= substr(esc($post['deskripsi1']), 0, 100) ?>...</p>
                            <a class="btn btn-sm" style="background-color: #A01D1D; border-color: #A01D1D; color: #fff;" href="<?= base_url('berita/detail/' . (isset($post['id']) ? encrypt_berita_id((int)$post['id']) : '')) ?>">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1) : ?>
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

        // Preserve query parameters (seperti kategori)
        $queryParams = $_GET;
        unset($queryParams['page']); // Hapus page dari query params untuk dibangun ulang
        ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination pagination-custom justify-content-center">
                <!-- Previous Button -->
                <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => max(1, $currentPage - 1)])) ?>" tabindex="-1">
                        <span class="pagination-text">Previous</span>
                        <span class="pagination-icon">‹</span>
                    </a>
                </li>

                <!-- First Page -->
                <?php if ($startPage > 1) : ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => 1])) ?>">1</a>
                    </li>
                    <?php if ($startPage > 2) : ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = $startPage; $i <= $endPage; $i++) : ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => $i])) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <!-- Last Page -->
                <?php if ($endPage < $totalPages) : ?>
                    <?php if ($endPage < $totalPages - 1) : ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => $totalPages])) ?>"><?= $totalPages ?></a>
                    </li>
                <?php endif; ?>

                <!-- Next Button -->
                <li class="page-item <?= ($currentPage == $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($queryParams, ['page' => min($totalPages, $currentPage + 1)])) ?>">
                        <span class="pagination-text">Next</span>
                        <span class="pagination-icon">›</span>
                    </a>
                </li>
            </ul>
        </nav>

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
</div>

<script nonce="<?= bin2hex(random_bytes(16)) ?>">
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk mengatur urutan postingan berdasarkan ID
        function rearrangePostsByID() {
            const latestContainer = document.getElementById('latest-posts-container');
            const olderContainer = document.getElementById('older-posts-container');
            const allPosts = Array.from(olderContainer.querySelectorAll('.col-lg-3.mb-4'));

            // Jika tidak ada postingan, tidak perlu diatur
            if (allPosts.length === 0) return;

            // Urutkan postingan berdasarkan ID (dari terakhir ke awal)
            allPosts.sort((a, b) => {
                const idA = parseInt(a.querySelector('a').href.split('/').pop());
                const idB = parseInt(b.querySelector('a').href.split('/').pop());
                return idB - idA;
            });

            // Kosongkan container lama
            olderContainer.innerHTML = '';

            // Format dan tambahkan kembali postingan yang sudah diurutkan
            allPosts.forEach(post => {
                olderContainer.appendChild(post);
            });
        }

        // Panggil fungsi untuk mengatur ulang postingan
        rearrangePostsByID();

        // Fungsi untuk mengatur tinggi card yang seragam
        function setEqualCardHeights() {
            const cards = document.querySelectorAll('#older-posts-container .card');
            let maxHeight = 0;

            // Reset height agar bisa menghitung ulang
            cards.forEach(card => {
                card.style.height = 'auto';
            });

            // Cari tinggi maksimum
            cards.forEach(card => {
                if (card.offsetHeight > maxHeight) {
                    maxHeight = card.offsetHeight;
                }
            });

            // Set tinggi yang seragam
            cards.forEach(card => {
                card.style.height = `${maxHeight}px`;
            });
        }

        // Panggil fungsi saat pertama kali load dan saat resize window
        setEqualCardHeights();
        window.addEventListener('resize', setEqualCardHeights);

        // Animasi saat hover card
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });
        });

        // CSRF Protection for AJAX requests
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.method.toLowerCase() === 'post') {
                const csrfToken = document.getElementById('csrf_token').value;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '<?= csrf_token() ?>';
                input.value = csrfToken;
                form.appendChild(input);
            }
        });
    });
</script>
<?= $this->endSection() ?>