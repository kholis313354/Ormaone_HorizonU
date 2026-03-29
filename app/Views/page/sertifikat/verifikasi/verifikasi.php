<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?></title>
    <style>
        .verification-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .verified {
            color: green;
        }

        .not-verified {
            color: red;
        }
    </style>
</head>

<body>
    <div class="verification-container">
        <h1>Verifikasi Sertifikat</h1>

        <?php if ($verified): ?>
            <div class="verified">
                <h2>Sertifikat Valid</h2>
                <p>Nama Penerima: <?= $sertifikat['nama_penerima'] ?></p>
                <p>Nama Kegiatan: <?= $sertifikat['nama_kegiatan'] ?></p>
                <p>NIM: <?= $sertifikat['nim'] ?></p>
                <p>Tanggal Terbit: <?= date('d F Y', strtotime($sertifikat['created_at'])) ?></p>
            </div>
        <?php else: ?>
            <div class="not-verified">
                <h2><?= $message ?></h2>
                <p>Sertifikat tidak dapat ditemukan atau tidak valid.</p>
            </div>
        <?php endif; ?>

        <a href="<?= base_url() ?>">Kembali ke Beranda</a>
    </div>
</body>

</html>