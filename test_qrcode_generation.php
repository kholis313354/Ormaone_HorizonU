<?php
/**
 * Test script untuk memverifikasi QR Code generation
 * Jalankan via browser: http://localhost/test_qrcode_generation.php
 */

// Define FCPATH (sama seperti di index.php)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

echo "<h1>Test QR Code Generation</h1>";
echo "<pre>";

echo "FCPATH: " . FCPATH . "\n\n";

// Test 1: Cek folder uploads/qrcode
$qrcodeDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'qrcode';
echo "1. Checking folder: " . $qrcodeDir . "\n";
echo "   Exists: " . (is_dir($qrcodeDir) ? "YES" : "NO") . "\n";
echo "   Writable: " . (is_writable($qrcodeDir) ? "YES" : "NO") . "\n\n";

// Test 2: Cek library
echo "2. Checking QR Code library:\n";
if (class_exists('chillerlan\QRCode\QRCode')) {
    echo "   ✓ chillerlan/php-qrcode library found\n";
} else {
    echo "   ✗ chillerlan/php-qrcode library NOT found\n";
}
echo "\n";

// Test 3: Cek GD extension
echo "3. Checking PHP extensions:\n";
if (extension_loaded('gd')) {
    echo "   ✓ GD extension loaded\n";
    $gdInfo = gd_info();
    echo "   GD Version: " . $gdInfo['GD Version'] . "\n";
} else {
    echo "   ✗ GD extension NOT loaded\n";
}
echo "\n";

// Test 4: Coba generate QR Code
echo "4. Testing QR Code generation:\n";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    $testUrl = 'https://ormaone.com/form/public/test123';
    $testFile = $qrcodeDir . DIRECTORY_SEPARATOR . 'test_qrcode.png';
    
    $options = new \chillerlan\QRCode\QROptions([
        'version' => 5,
        'outputType' => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
        'eccLevel' => \chillerlan\QRCode\QRCode::ECC_H,
        'scale' => 10,
        'imageBase64' => false,
        'imageTransparent' => false,
        'quietzoneSize' => 4,
    ]);
    
    $qrCode = new \chillerlan\QRCode\QRCode($options);
    $qrCode->render($testUrl, $testFile);
    
    if (file_exists($testFile) && filesize($testFile) > 0) {
        echo "   ✓ QR Code generated successfully!\n";
        echo "   File: " . $testFile . "\n";
        echo "   Size: " . filesize($testFile) . " bytes\n";
        echo "   URL: " . base_url('uploads/qrcode/test_qrcode.png') . "\n";
    } else {
        echo "   ✗ QR Code generation failed - file not created or empty\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    echo "   Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n";
echo "5. Base URL test:\n";
echo "   base_url('uploads/qrcode/test.png'): " . base_url('uploads/qrcode/test.png') . "\n";

echo "</pre>";

function base_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $base = $protocol . '://' . $host;
    $path = ltrim($path, '/');
    return $base . '/' . $path;
}

