<?php

namespace App\Libraries;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Config\Services;

class QrcodeGenerator
{
    protected $options;

    public function __construct()
    {
        $this->options = new QROptions([
            'version' => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_H,
            'scale' => 10,
            'imageBase64' => false,
            'imageTransparent' => false,
            'quietzoneSize' => 4,
        ]);
    }

    public function generateVerificationQR($url, $filePath)
    {
        try {
            $qrCode = new QRCode($this->options);
            $qrCode->render($url, $filePath);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'QR Generation Error: ' . $e->getMessage());
            return false;
        }
    }

    public function addRecipientName($imagePath, $text, $options = [])
    {
        try {
            // Debug info
            log_message('debug', 'Memproses penambahan teks: ' . $text);
            log_message('debug', 'Pada file: ' . $imagePath);

            // Verifikasi file gambar
            if (!file_exists($imagePath)) {
                throw new \RuntimeException("File gambar tidak ditemukan");
            }

            // Verifikasi teks tidak kosong
            if (empty(trim($text))) {
                throw new \RuntimeException("Nama penerima tidak boleh kosong");
            }

            // Penanganan font dengan fallback
            $fontPath = $this->getFontPath($options['fontPath'] ?? null);
            
            // Default options dengan penyesuaian untuk visibilitas
            $textOptions = [
                'color' => $options['color'] ?? '#000000',
                'fontSize' => $options['fontSize'] ?? 20,
                'xOffset' => $options['xOffset'] ?? 20,
                'yOffset' => $options['yOffset'] ?? 250,
                'angle' => $options['angle'] ?? 0,
            ];

            // Baca gambar
            $image = imagecreatefromstring(file_get_contents($imagePath));
            if (!$image) {
                throw new \RuntimeException("Gagal memuat gambar");
            }

            // Konversi warna hex ke RGB
            $color = sscanf($textOptions['color'], "#%02x%02x%02x");
            $textColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);

            // Hitung bounding box untuk teks
            $textBox = imagettfbbox($textOptions['fontSize'], $textOptions['angle'], $fontPath, $text);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[7] - $textBox[1];

            // Hitung posisi x dan y (pusatkan di koordinat yang diberikan)
            $x = $textOptions['xOffset'] - ($textWidth / 2);
            $y = $textOptions['yOffset'] + ($textHeight / 250); // Karena y adalah baseline

            // Tambahkan teks ke gambar
            imagettftext(
                $image,
                $textOptions['fontSize'],
                $textOptions['angle'],
                (int)$x,
                (int)$y,
                $textColor,
                $fontPath,
                $text
            );

            // Simpan gambar
            imagepng($image, $imagePath);
            imagedestroy($image);

            log_message('debug', 'Teks berhasil ditambahkan');
            return true;

        } catch (\Exception $e) {
            log_message('error', 'Error addRecipientName: ' . $e->getMessage());
            return false;
        }
    }

    private function getFontPath($customPath = null)
    {
        // Prioritas 1: Font custom dari parameter
        if ($customPath && file_exists($customPath)) {
            log_message('debug', 'Menggunakan font custom: ' . $customPath);
            return $customPath;
        }

        // Prioritas 2: Font default project
        $defaultFont = FCPATH . 'fonts/PinyonScript-Regular.ttf';
        if (file_exists($defaultFont)) {
            log_message('debug', 'Menggunakan font default: ' . $defaultFont);
            return $defaultFont;
        }

        // Prioritas 3: Font bawaan CI4
        $ciFont = FCPATH . 'vendor/codeigniter4/framework/system/Fonts/arial.ttf';
        if (file_exists($ciFont)) {
            log_message('debug', 'Menggunakan font CI4: ' . $ciFont);
            return $ciFont;
        }

        // Prioritas 4: Font sistem Linux
        $systemFont = '/usr/share/fonts/truetype/freefont/FreeSansBold.ttf';
        if (file_exists($systemFont)) {
            log_message('debug', 'Menggunakan font sistem: ' . $systemFont);
            return $systemFont;
        }

        throw new \RuntimeException('Tidak ada font yang valid ditemukan');
    }

    public function mergeQRWithCertificate($certificatePath, $qrCodePath, $originalExt = 'png')
    {
        try {
            // Load certificate based on extension
            switch(strtolower($originalExt)) {
                case 'jpg':
                case 'jpeg':
                    $certificate = imagecreatefromjpeg($certificatePath);
                    $saveFunction = 'imagejpeg';
                    $quality = 90;
                    break;
                case 'png':
                default:
                    $certificate = imagecreatefrompng($certificatePath);
                    $saveFunction = 'imagepng';
                    $quality = 9;
                    break;
            }

            if (!$certificate) {
                throw new \RuntimeException("Gagal memuat gambar sertifikat");
            }

            // Load QR Code (always PNG)
            $qrCode = imagecreatefrompng($qrCodePath);
            if (!$qrCode) {
                throw new \RuntimeException("Gagal memuat QR Code");
            }

            // Get dimensions
            $certWidth = imagesx($certificate);
            $certHeight = imagesy($certificate);
            $qrWidth = imagesx($qrCode);
            $qrHeight = imagesy($qrCode);

            // Position QR code (adjust these values as needed)
            $x = $certWidth - $qrWidth - 100; // 100px/2250 dari kanan
            $y = $certHeight - $qrHeight - 100; // 100px/1600 dari bawah

            // Merge images
            imagecopy($certificate, $qrCode, $x, $y, 0, 0, $qrWidth, $qrHeight);

            // Save based on original format
            $saveFunction($certificate, $certificatePath, $quality);

            // Clean up
            imagedestroy($certificate);
            imagedestroy($qrCode);

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Merge Error: ' . $e->getMessage());
            return false;
        }
    }

    public function mergeLogoToQR($qrCodePath, $logoPath)
    {
        try {
            $qrCode = imagecreatefrompng($qrCodePath);
            if (!$qrCode) {
                throw new \RuntimeException("Gagal memuat QR Code");
            }

            $logo = imagecreatefrompng($logoPath);
            if (!$logo) {
                throw new \RuntimeException("Gagal memuat logo");
            }

            $qrSize = imagesx($qrCode);
            $logoWidth = imagesx($logo);
            $logoHeight = imagesy($logo);

            $newSize = $qrSize * 0.2; // Logo size 20% of QR
            $resizedLogo = imagescale($logo, $newSize, $newSize);

            $x = ($qrSize - $newSize) / 2;
            $y = ($qrSize - $newSize) / 2;

            imagecopymerge($qrCode, $resizedLogo, $x, $y, 0, 0, $newSize, $newSize, 100);
            imagepng($qrCode, $qrCodePath);

            imagedestroy($qrCode);
            imagedestroy($logo);
            imagedestroy($resizedLogo);

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Logo Merge Error: ' . $e->getMessage());
            return false;
        }
    }
}