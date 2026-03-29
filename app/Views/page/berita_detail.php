<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= htmlspecialchars($title ?? '', ENT_QUOTES, 'UTF-8') ?>
<?= $this->endSection() ?>

<?= $this->section('extra_head') ?>
<!-- Meta Tags for Social Sharing -->
<?php
// Siapkan data untuk meta tags
$judul = isset($berita['nama_kegiatan']) ? htmlspecialchars($berita['nama_kegiatan'], ENT_QUOTES, 'UTF-8') : 'Default Title';
$deskripsi = isset($berita['deskripsi1']) ? strip_tags($berita['deskripsi1']) : 'Default Description';
$deskripsi = htmlspecialchars($deskripsi, ENT_QUOTES, 'UTF-8');
$deskripsi = strlen($deskripsi) > 200 ? substr($deskripsi, 0, 197) . '...' : $deskripsi;
$url = current_url();

// Tentukan gambar - PASTIKAN menggunakan gambar dari field "gambar"
$image = base_url('assets/img/default-image.jpg');
if (isset($berita['gambar']) && !empty($berita['gambar'])) {
    $imagePath = 'uploads/berita/' . $berita['gambar'];
    if (file_exists(FCPATH . $imagePath)) {
        // Pastikan URL gambar adalah absolute URL lengkap
        $image = base_url($imagePath);
        // Jika base_url tidak menghasilkan absolute URL, buat manual
        if (strpos($image, 'http') !== 0) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? parse_url(base_url(), PHP_URL_HOST);
            $image = $scheme . '://' . $host . '/' . ltrim($imagePath, '/');
        }
    }
}

// Pastikan URL gambar adalah absolute URL (full URL dengan domain)
if (strpos($image, 'http') !== 0) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? parse_url(base_url(), PHP_URL_HOST);
    $imagePath = ltrim(parse_url($image, PHP_URL_PATH) ?? $image, '/');
    $image = $scheme . '://' . $host . '/' . $imagePath;
}

// Pastikan URL gambar benar-benar valid
$image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
$url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
?>

<!-- Open Graph Tags -->
<meta property="og:title" content="<?= $judul ?>">
<meta property="og:description" content="<?= $deskripsi ?>">
<meta property="og:image" content="<?= $image ?>">
<meta property="og:url" content="<?= $url ?>">
<meta property="og:type" content="article">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="Nama Situs Anda">

<!-- Twitter Card Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= $judul ?>">
<meta name="twitter:description" content="<?= $deskripsi ?>">
<meta name="twitter:image" content="<?= $image ?>">
<meta name="twitter:site" content="@username_twitter">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Security Headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; font-src 'self' https:; frame-src 'self' https://www.youtube.com https://youtube.com https://www.youtube-nocookie.com https://youtube-nocookie.com; frame-ancestors 'none';");

// CSRF Protection
$csrf = csrf_token();
?>
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token">

<!-- Hero Section -->
<header class="py-5 bg-light border-bottom mb-4">
    <div class="container">
        <div class="text-center my-5">
            <br>
            <p class="display-4 fw-bold"><?= isset($berita['nama_kegiatan']) ? esc($berita['nama_kegiatan']) : '' ?></p>
        </div>
    </div>
</header>

<!-- Blog Content -->
<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article>
                <!-- Meta Info -->
                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <?php if (isset($berita['kategori'])) : ?>
                            <span class="badge bg-primary"><?= esc($berita['kategori']) ?></span>
                        <?php endif; ?>
                        <?php if (isset($berita['nama_fakultas'])) : ?>
                            <span class="badge bg-secondary ms-2"><?= esc($berita['nama_fakultas']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="text-muted">
                        <i class="bi bi-calendar me-1"></i>
                        <?= isset($berita['tanggal']) ? date('d F Y', strtotime($berita['tanggal'])) : '' ?>
                    </div>
                </div>

                <!-- Featured Image / YouTube Video -->
                <?php
                // Fungsi untuk mengekstrak video ID dari URL YouTube - versi yang lebih sederhana dan robust
                function getYouTubeVideoId($url) {
                    if (empty($url)) return null;
                    
                    // Bersihkan URL dari spasi
                    $url = trim($url);
                    if (empty($url)) return null;
                    
                    // Hapus whitespace dan karakter tidak perlu
                    $url = preg_replace('/\s+/', '', $url);
                    
                    // Pastikan URL lengkap dengan protocol
                    if (!preg_match('/^https?:\/\//i', $url)) {
                        $url = 'https://' . ltrim($url, '/');
                    }
                    
                    // Pattern 1: youtube.com/watch?v=VIDEO_ID atau youtube.com/watch?feature=...&v=VIDEO_ID
                    if (preg_match('/(?:youtube\.com\/watch\?.*[&?])v=([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
                        return $matches[1];
                    }
                    
                    // Pattern 2: youtube.com/watch?v=VIDEO_ID (simple)
                    if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
                        return $matches[1];
                    }
                    
                    // Pattern 3: youtu.be/VIDEO_ID
                    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
                        return $matches[1];
                    }
                    
                    // Pattern 4: youtube.com/embed/VIDEO_ID
                    if (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
                        return $matches[1];
                    }
                    
                    // Pattern 5: youtube.com/v/VIDEO_ID
                    if (preg_match('/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
                        return $matches[1];
                    }
                    
                    // Pattern 6: Fallback - cari 11 karakter alphanumeric/underscore/dash setelah v=
                    if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
                        return $matches[1];
                    }
                    
                    // Pattern 7: Fallback - cari 11 karakter setelah youtube.com atau youtu.be
                    if (preg_match('/(?:youtube\.com|youtu\.be)[\/\?](?:watch\?v=|v\/|embed\/|)([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
                        return $matches[1];
                    }
                    
                    return null;
                }
                
                // Cek apakah ada link YouTube
                $youtubeVideoId = null;
                
                if (isset($berita['link']) && !empty($berita['link'])) {
                    $rawLink = $berita['link'];
                    
                    // Debug: tampilkan link yang disimpan (hapus ini setelah testing)
                    // echo "<!-- Debug: Link dari database: " . htmlspecialchars($rawLink) . " -->";
                    
                    $youtubeVideoId = getYouTubeVideoId($rawLink);
                    
                    // Validasi: video ID harus 11 karakter dan hanya mengandung karakter valid
                    if ($youtubeVideoId && strlen($youtubeVideoId) === 11 && preg_match('/^[a-zA-Z0-9_-]+$/', $youtubeVideoId)) {
                        // Video ID valid
                    } else {
                        // Video ID tidak valid
                        $youtubeVideoId = null;
                    }
                }
                ?>
                
                <?php if ($youtubeVideoId) : ?>
                    <!-- YouTube Video Embed -->
                    <div class="mb-4">
                        <div class="ratio ratio-16x9">
                            <iframe 
                                src="https://www.youtube.com/embed/<?= htmlspecialchars($youtubeVideoId, ENT_QUOTES, 'UTF-8') ?>"
                                title="<?= esc($berita['nama_kegiatan'] ?? 'Video YouTube') ?>"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                referrerpolicy="strict-origin-when-cross-origin"
                                class="rounded"></iframe>
                        </div>
                    </div>
                <?php elseif (isset($berita['gambar'])) : ?>
                    <!-- Featured Image -->
                    <?php
                    $imagePath = 'uploads/berita/' . $berita['gambar'];
                    if (file_exists(FCPATH . $imagePath) && is_file(FCPATH . $imagePath)) : ?>
                        <figure class="mb-4">
                            <img src="<?= base_url($imagePath) ?>"
                                class="img-fluid rounded"
                                alt="<?= esc($berita['nama_kegiatan'] ?? '') ?>"
                                loading="lazy"
                                decoding="async"
                                onerror="this.src='<?= base_url('assets/img/default-image.jpg') ?>';">
                        </figure>
                    <?php else : ?>
                        <figure class="mb-4">
                            <img src="<?= base_url('assets/img/default-image.jpg') ?>"
                                class="img-fluid rounded"
                                alt="Default image"
                                loading="lazy"
                                decoding="async">
                        </figure>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Content -->
                <div class="content">
                    <div class="mb-4">
                        <?= isset($berita['deskripsi1']) ? $berita['deskripsi1'] : '' ?>
                    </div>

                    <?php if (!empty($berita['deskripsi2'])) : ?>
                        <div class="mb-4">
                            <h4>Detail Kegiatan</h4>
                            <?= $berita['deskripsi2'] ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($berita['deskripsi3'])) : ?>
                        <div class="mb-4">
                            <h4>Informasi Tambahan</h4>
                            <?= $berita['deskripsi3'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Share Buttons -->
                <div class="border-top pt-4 mt-4">
                    <h5>Bagikan Artikel Ini:</h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <!-- Facebook -->
                        <a href="javascript:void(0);" onclick="shareToFacebook()" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>

                        <!-- Twitter -->
                        <a href="javascript:void(0);" onclick="shareToTwitter()" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-twitter"></i> Twitter
                        </a>

                        <!-- WhatsApp -->
                        <a href="javascript:void(0);" onclick="shareToWhatsApp()" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>

                        <!-- Instagram -->
                        <a href="javascript:void(0);" onclick="shareToInstagram()" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-instagram"></i> Instagram
                        </a>

                        <!-- LinkedIn -->
                        <a href="javascript:void(0);" onclick="shareToLinkedIn()" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-linkedin"></i> LinkedIn
                        </a>

                        <!-- Telegram -->
                        <a href="javascript:void(0);" onclick="shareToTelegram()" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-telegram"></i> Telegram
                        </a>

                        <!-- Salin Link -->
                        <a href="javascript:void(0);" onclick="copyLink()" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-link-45deg"></i> Salin Link
                        </a>
                    </div>
                </div>

                <?php
                // Siapkan data untuk share
                $shareTitle = isset($berita['nama_kegiatan']) ? htmlspecialchars($berita['nama_kegiatan'], ENT_QUOTES, 'UTF-8') : 'Artikel Berita';
                $shareDescription = isset($berita['deskripsi1']) ? strip_tags($berita['deskripsi1']) : '';
                $shareDescription = htmlspecialchars($shareDescription, ENT_QUOTES, 'UTF-8');
                $shareDescription = strlen($shareDescription) > 150 ? substr($shareDescription, 0, 147) . '...' : $shareDescription;
                
                // Siapkan URL gambar untuk share - PASTIKAN menggunakan gambar dari field "gambar"
                $shareImage = base_url('assets/img/default-image.jpg');
                if (isset($berita['gambar']) && !empty($berita['gambar'])) {
                    $imagePath = 'uploads/berita/' . $berita['gambar'];
                    if (file_exists(FCPATH . $imagePath)) {
                        // Pastikan URL gambar adalah absolute URL lengkap
                        $shareImage = base_url($imagePath);
                        // Jika base_url tidak menghasilkan absolute URL, buat manual
                        if (strpos($shareImage, 'http') !== 0) {
                            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                            $host = $_SERVER['HTTP_HOST'] ?? parse_url(base_url(), PHP_URL_HOST);
                            $shareImage = $scheme . '://' . $host . '/' . ltrim($imagePath, '/');
                        }
                    }
                }
                
                // Pastikan URL gambar adalah absolute URL (full URL dengan domain)
                if (strpos($shareImage, 'http') !== 0) {
                    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $host = $_SERVER['HTTP_HOST'] ?? parse_url(base_url(), PHP_URL_HOST);
                    $imagePath = ltrim(parse_url($shareImage, PHP_URL_PATH) ?? $shareImage, '/');
                    $shareImage = $scheme . '://' . $host . '/' . $imagePath;
                }
                
                // Enkripsi URL menggunakan base64
                $originalUrl = current_url();
                $encryptedUrl = base64_encode($originalUrl);
                $shareUrl = base_url('berita/share/' . urlencode($encryptedUrl));
                
                // Domain untuk ditampilkan
                $domain = parse_url(base_url(), PHP_URL_HOST);
                ?>

                <script nonce="<?= bin2hex(random_bytes(16)) ?>">
                    // Data untuk share
                    const shareData = {
                        title: <?= json_encode($shareTitle, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT) ?>,
                        description: <?= json_encode($shareDescription, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT) ?>,
                        image: <?= json_encode($shareImage, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT) ?>,
                        url: <?= json_encode($shareUrl, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT) ?>,
                        originalUrl: <?= json_encode($originalUrl, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT) ?>,
                        domain: <?= json_encode($domain, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT) ?>
                    };

                    // Fungsi untuk membuat template share yang menarik
                    function createShareTemplate() {
                        return `${shareData.title}\n\n${shareData.description}\n\nLihat detail selengkapnya di: ${shareData.url}`;
                    }

                    // Fungsi untuk membuat template share yang lebih menarik dengan format seperti preview
                    function createAttractiveShareTemplate() {
                        return `${shareData.title}\n\n${shareData.domain}\n\n${shareData.description}\n\nLihat detail selengkapnya di: ${shareData.url}`;
                    }

                    // Fungsi untuk berbagi ke Facebook
                    function shareToFacebook() {
                        const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareData.url)}`;
                        window.open(shareUrl, '_blank', 'width=600,height=400');
                    }

                    // Fungsi untuk berbagi ke Twitter
                    function shareToTwitter() {
                        const text = `${shareData.title}\n\nLihat detail selengkapnya di:`;
                        const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(shareData.url)}`;
                        window.open(shareUrl, '_blank', 'width=600,height=400');
                    }

                    // Fungsi untuk berbagi ke WhatsApp dengan template menarik seperti preview
                    function shareToWhatsApp() {
                        const text = `${shareData.title}\n\n${shareData.domain}\n\n${shareData.description}\n\nLihat detail selengkapnya di: ${shareData.url}`;
                        const shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
                        window.open(shareUrl, '_blank', 'width=600,height=400');
                    }

                    // Fungsi untuk berbagi ke Instagram
                    function shareToInstagram() {
                        const text = `${shareData.title}\n\n${shareData.description}\n\nLihat detail selengkapnya di: ${shareData.url}`;
                        // Instagram tidak mendukung direct share via URL, jadi kita copy ke clipboard
                        copyLink();
                        alert('Link telah disalin! Silakan tempel di Instagram untuk berbagi artikel ini.');
                    }

                    // Fungsi untuk berbagi ke LinkedIn
                    function shareToLinkedIn() {
                        const shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareData.url)}`;
                        window.open(shareUrl, '_blank', 'width=600,height=400');
                    }

                    // Fungsi untuk berbagi ke Telegram dengan template menarik seperti preview
                    function shareToTelegram() {
                        const text = `${shareData.title}\n\n${shareData.domain}\n\n${shareData.description}\n\nLihat detail selengkapnya di: ${shareData.url}`;
                        const shareUrl = `https://t.me/share/url?url=${encodeURIComponent(shareData.url)}&text=${encodeURIComponent(text)}`;
                        window.open(shareUrl, '_blank', 'width=600,height=400');
                    }

                    // Fungsi untuk menyalin link ke clipboard dengan template menarik
                    function copyLink() {
                        const shareText = createAttractiveShareTemplate();
                        navigator.clipboard.writeText(shareText).then(() => {
                            // Tampilkan pesan sukses dengan toast atau alert yang lebih menarik
                            showNotification('Link berhasil disalin! Tempelkan di sosial media untuk berbagi artikel ini.', 'success');
                        }).catch(err => {
                            console.error('Gagal menyalin: ', err);
                            // Fallback untuk browser yang tidak mendukung clipboard API
                            const textArea = document.createElement('textarea');
                            textArea.value = shareText;
                            textArea.style.position = 'fixed';
                            textArea.style.opacity = '0';
                            document.body.appendChild(textArea);
                            textArea.select();
                            try {
                                document.execCommand('copy');
                                showNotification('Link berhasil disalin!', 'success');
                            } catch (err) {
                                showNotification('Gagal menyalin link', 'error');
                            }
                            document.body.removeChild(textArea);
                        });
                    }

                    // Fungsi untuk menampilkan notifikasi
                    function showNotification(message, type) {
                        // Buat elemen notifikasi
                        const notification = document.createElement('div');
                        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
                        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                        notification.innerHTML = `
                            ${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.body.appendChild(notification);
                        
                        // Hapus notifikasi setelah 3 detik
                        setTimeout(() => {
                            notification.remove();
                        }, 3000);
                    }

                    // Fungsi untuk berbagi ke semua platform (opsional)
                    function shareToAll() {
                        if (navigator.share) {
                            navigator.share({
                                title: shareData.title,
                                text: shareData.description,
                                url: shareData.url
                            }).catch(err => {
                                console.log('Error sharing:', err);
                            });
                        } else {
                            copyLink();
                        }
                    }
                </script>
            </article>
        </div>
        <div class="col-lg-4">
            <!-- Search widget -->
            <div class="card mb-4">
                <div class="card-header">Cari Berita</div>
                <div class="card-body">
                    <form action="<?= base_url('berita/search') ?>" method="get" id="searchForm">
                        <div class="input-group">
                            <input class="form-control" type="text" name="q" placeholder="Masukkan kata kunci..." maxlength="100" required>
                            <button class="btn btn-danger" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Categories widget -->
            <div class="card mb-4">
                <div class="card-header">Kategori Berita</div>
                <div class="card-body">
                    <div class="row">
                        <?php if (!empty($kategori)) : ?>
                            <?php
                            $categories = array_chunk($kategori, ceil(count($kategori) / 2));
                            foreach ($categories as $chunk) : ?>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach ($chunk as $cat) : ?>
                                            <?php if (isset($cat['kategori'])) : ?>
                                                <li><a href="<?= base_url('berita?kategori=' . urlencode(htmlspecialchars($cat['kategori'], ENT_QUOTES, 'UTF-8'))) ?>">
                                                        <?= esc($cat['kategori']) ?>
                                                    </a></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="col-12">
                                <p>Belum ada kategori</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>