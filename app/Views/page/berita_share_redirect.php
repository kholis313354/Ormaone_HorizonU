<?= $this->extend('components/layouts/landing') ?>

<?= $this->section('title') ?>
<?= isset($berita) ? htmlspecialchars($berita['nama_kegiatan'], ENT_QUOTES, 'UTF-8') . ' - Blog Ormaone' : 'Berita - Blog Ormaone' ?>
<?= $this->endSection() ?>

<?= $this->section('extra_head') ?>
<!-- Meta Tags for Social Sharing -->
<?php
if (isset($berita) && $berita) {
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
        if (defined('FCPATH') && file_exists(FCPATH . $imagePath) && is_file(FCPATH . $imagePath)) {
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
} else {
    $judul = 'Berita - Blog Ormaone';
    $deskripsi = 'Baca artikel berita terbaru dari Blog Ormaone';
    $image = base_url('assets/img/default-image.jpg');
    // Pastikan URL gambar adalah absolute URL
    if (strpos($image, 'http') !== 0) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? parse_url(base_url(), PHP_URL_HOST);
        $imagePath = ltrim(parse_url($image, PHP_URL_PATH) ?? 'assets/img/default-image.jpg', '/');
        $image = $scheme . '://' . $host . '/' . $imagePath;
    }
    $url = current_url();
}
?>

<!-- Open Graph Tags -->
<meta property="og:title" content="<?= $judul ?>">
<meta property="og:description" content="<?= $deskripsi ?>">
<meta property="og:image" content="<?= $image ?>">
<meta property="og:image:secure_url" content="<?= str_replace('http://', 'https://', $image) ?>">
<meta property="og:url" content="<?= $url ?>">
<meta property="og:type" content="article">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:site_name" content="Blog Ormaone - Organisasi Mahasiswa">

<!-- Twitter Card Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= $judul ?>">
<meta name="twitter:description" content="<?= $deskripsi ?>">
<meta name="twitter:image" content="<?= $image ?>">

<!-- Redirect setelah 0.5 detik untuk memastikan Open Graph tags sudah di-fetch -->
<meta http-equiv="refresh" content="0.5;url=<?= htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body py-5">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h4 class="mb-3">Mengarahkan ke artikel...</h4>
                    <p class="text-muted">Anda akan diarahkan ke halaman artikel dalam beberapa saat.</p>
                    <p class="mt-3">
                        <a href="<?= htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-primary">
                            Klik di sini jika tidak otomatis diarahkan
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Redirect dengan JavaScript sebagai fallback
    setTimeout(function() {
        window.location.href = <?= json_encode($redirectUrl, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT) ?>;
    }, 500);
</script>
<?= $this->endSection() ?>

