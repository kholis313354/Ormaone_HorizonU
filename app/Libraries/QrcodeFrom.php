<?php

namespace App\Libraries;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\Version;

class QrcodeFrom
{
    protected $options;

    public function __construct()
    {
        $this->options = new QROptions([
            'version' => Version::AUTO, // Auto-version untuk menyesuaikan panjang data
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_H,
            'scale' => 10,
            'imageBase64' => false,
            'imageTransparent' => false,
            'quietzoneSize' => 4,
        ]);
    }

    /**
     * Generate QR Code untuk form
     * 
     * @param string $url URL yang akan di-encode ke QR Code
     * @param string $filePath Path lengkap file untuk menyimpan QR Code
     * @return bool|string Return path relatif jika berhasil, false jika gagal
     */
    public function generateFormQR($url, $filePath)
    {
        try {
            log_message('debug', 'QrcodeFrom: Starting QR generation. URL: ' . $url . ', FilePath: ' . $filePath);
            
            // Pastikan directory ada
            $dir = dirname($filePath);
            if (!is_dir($dir)) {
                log_message('debug', 'QrcodeFrom: Directory tidak ada, membuat directory: ' . $dir);
                if (!mkdir($dir, 0777, true)) {
                    log_message('error', 'QrcodeFrom: Failed to create directory: ' . $dir);
                    return false;
                }
                log_message('debug', 'QrcodeFrom: Directory berhasil dibuat: ' . $dir);
            } else {
                // Pastikan directory writable
                if (!is_writable($dir)) {
                    log_message('error', 'QrcodeFrom: Directory tidak writable: ' . $dir);
                    // Coba ubah permission
                    @chmod($dir, 0777);
                    if (!is_writable($dir)) {
                        return false;
                    }
                }
            }

            // Pastikan URL tidak kosong
            if (empty($url)) {
                log_message('error', 'QrcodeFrom: URL tidak boleh kosong');
                return false;
            }

            // Generate QR Code
            log_message('debug', 'QrcodeFrom: Creating QRCode instance');
            $qrCode = new QRCode($this->options);
            
            // Render QR Code ke file
            log_message('debug', 'QrcodeFrom: Rendering QR Code to file: ' . $filePath);
            
            // Pastikan path menggunakan DIRECTORY_SEPARATOR yang benar untuk sistem operasi
            $normalizedFilePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);
            $normalizedFilePath = preg_replace('/[\/\\\]+/', DIRECTORY_SEPARATOR, $normalizedFilePath);
            
            log_message('debug', 'QrcodeFrom: Normalized file path: ' . $normalizedFilePath);
            
            try {
                $qrCode->render($url, $normalizedFilePath);
            } catch (\Exception $renderException) {
                log_message('error', 'QrcodeFrom: Exception saat render: ' . $renderException->getMessage());
                log_message('error', 'QrcodeFrom: Render Stack Trace: ' . $renderException->getTraceAsString());
                return false;
            }
            
            // Tunggu sebentar untuk memastikan file sudah ditulis ke disk
            usleep(100000); // 0.1 detik
            
            // Verifikasi file berhasil dibuat
            if (!file_exists($normalizedFilePath)) {
                log_message('error', 'QrcodeFrom: File QR Code tidak berhasil dibuat: ' . $normalizedFilePath);
                log_message('error', 'QrcodeFrom: Directory exists: ' . (is_dir(dirname($normalizedFilePath)) ? 'yes' : 'no'));
                log_message('error', 'QrcodeFrom: Directory writable: ' . (is_writable(dirname($normalizedFilePath)) ? 'yes' : 'no'));
                return false;
            }

            // Verifikasi file tidak kosong
            $fileSize = filesize($normalizedFilePath);
            if ($fileSize == 0) {
                log_message('error', 'QrcodeFrom: File QR Code kosong: ' . $normalizedFilePath);
                @unlink($normalizedFilePath); // Hapus file kosong
                return false;
            }

            log_message('info', 'QrcodeFrom: QR Code berhasil digenerate: ' . $normalizedFilePath . ' (Size: ' . $fileSize . ' bytes)');
            
            // Return path relatif dari FCPATH untuk web access
            // Pastikan FCPATH juga dinormalisasi
            $normalizedFCPATH = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, FCPATH);
            $normalizedFCPATH = rtrim($normalizedFCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            
            $relativePath = str_replace($normalizedFCPATH, '', $normalizedFilePath);
            
            // Normalize path separator untuk web - pastikan menggunakan forward slash
            $relativePath = str_replace('\\', '/', $relativePath);
            // Pastikan tidak ada double slash
            $relativePath = preg_replace('/\/+/', '/', $relativePath);
            // Pastikan path tidak dimulai dengan slash (untuk base_url)
            $relativePath = ltrim($relativePath, '/');
            
            log_message('debug', 'QrcodeFrom: FCPATH: ' . FCPATH);
            log_message('debug', 'QrcodeFrom: Normalized FCPATH: ' . $normalizedFCPATH);
            log_message('debug', 'QrcodeFrom: Normalized FilePath: ' . $normalizedFilePath);
            log_message('debug', 'QrcodeFrom: Returning relative path: ' . $relativePath);
            
            // Verifikasi path relatif yang dikembalikan
            $verifyPath = FCPATH . $relativePath;
            $verifyPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $verifyPath);
            if (!file_exists($verifyPath)) {
                log_message('error', 'QrcodeFrom: WARNING - Relative path tidak dapat diverifikasi: ' . $verifyPath);
            } else {
                log_message('debug', 'QrcodeFrom: Relative path verified: ' . $verifyPath);
            }
            
            return $relativePath;

        } catch (\Exception $e) {
            log_message('error', 'QrcodeFrom Error: ' . $e->getMessage());
            log_message('error', 'QrcodeFrom Stack Trace: ' . $e->getTraceAsString());
            return false;
        } catch (\Throwable $e) {
            log_message('error', 'QrcodeFrom Throwable Error: ' . $e->getMessage());
            log_message('error', 'QrcodeFrom Stack Trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Generate QR Code dengan return base64 (untuk preview)
     * 
     * @param string $url URL yang akan di-encode
     * @return string|false Base64 encoded image atau false jika gagal
     */
    public function generateFormQRBase64($url)
    {
        try {
            if (empty($url)) {
                log_message('error', 'QrcodeFrom: URL tidak boleh kosong untuk Base64');
                return false;
            }

            $options = new QROptions([
                'version' => Version::AUTO, // Auto-version untuk menyesuaikan panjang data
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_H,
                'scale' => 10,
                'imageBase64' => true,
                'imageTransparent' => false,
                'quietzoneSize' => 4,
            ]);

            $qrCode = new QRCode($options);
            $base64 = $qrCode->render($url);
            
            return $base64;

        } catch (\Exception $e) {
            log_message('error', 'QrcodeFrom Base64 Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate QR Code dengan custom options
     * 
     * @param string $url URL yang akan di-encode
     * @param string $filePath Path file untuk menyimpan
     * @param array $customOptions Custom options untuk QR Code
     * @return bool|string Path relatif jika berhasil, false jika gagal
     */
    public function generateFormQRWithOptions($url, $filePath, $customOptions = [])
    {
        try {
            // Merge dengan default options
            $options = array_merge([
                'version' => Version::AUTO, // Auto-version untuk menyesuaikan panjang data
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_H,
                'scale' => 10,
                'imageBase64' => false,
                'imageTransparent' => false,
                'quietzoneSize' => 4,
            ], $customOptions);

            $qrOptions = new QROptions($options);

            // Pastikan directory ada
            $dir = dirname($filePath);
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0777, true)) {
                    log_message('error', 'QrcodeFrom: Failed to create directory: ' . $dir);
                    return false;
                }
            }

            if (empty($url)) {
                log_message('error', 'QrcodeFrom: URL tidak boleh kosong');
                return false;
            }

            $qrCode = new QRCode($qrOptions);
            $qrCode->render($url, $filePath);

            if (!file_exists($filePath) || filesize($filePath) == 0) {
                log_message('error', 'QrcodeFrom: File QR Code tidak valid: ' . $filePath);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
                return false;
            }

            log_message('info', 'QrcodeFrom: QR Code dengan custom options berhasil digenerate: ' . $filePath);
            
            $relativePath = str_replace(FCPATH, '', $filePath);
            return $relativePath;

        } catch (\Exception $e) {
            log_message('error', 'QrcodeFrom Custom Options Error: ' . $e->getMessage());
            return false;
        }
    }
}

