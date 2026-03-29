<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .verification-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .verified {
            color: #28a745;
        }
        .not-verified {
            color: #dc3545;
        }
        .certificate-details {
            text-align: left;
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .certificate-logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
        }
        @media (max-width: 576px) {
            .certificate-logo {
                max-width: 100px;
            }
            .verification-container {
                padding: 15px;
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-container">
            <h1 class="mb-4">Verifikasi Sertifikat</h1>
            
            <?php if ($verified): ?>
                <!-- Tambahkan gambar logo di sini -->
                <img src="<?= base_url('uploads/sertifikat/' . esc($sertifikat['file'])) ?>" 
                     alt="Logo Sertifikat" 
                     class="certificate-logo img-fluid"
                     onerror="this.style.display='none'">
                
                <div class="verified mb-4">
                    <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    <h2 class="mt-3">Sertifikat Valid</h2>
                </div>
                
                <div class="certificate-details">
                    <h4><i class="bi bi-person-badge"></i> Detail Penerima:</h4>
                    <p><strong>Nama Penerima:</strong> <?= esc($sertifikat['nama_penerima']) ?></p>
                    <p><strong>Nama Kegiatan:</strong> <?= esc($sertifikat['nama_kegiatan']) ?></p>
                    <p><strong>Tanggal Terbit:</strong> <?= date('d F Y', strtotime($sertifikat['created_at'])) ?></p>
                </div>
            <?php else: ?>
                <div class="not-verified mb-4">
                    <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                    <h2 class="mt-3"><?= esc($message) ?></h2>
                    <p>Sertifikat tidak dapat ditemukan atau tidak valid.</p>
                </div>
            <?php endif; ?>

            <div class="mt-4" >
                <a href="<?= base_url() ?>" class="btn" style="background-color: #980517; color:#fff;">
                    <i class="bi bi-house-door" ></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>