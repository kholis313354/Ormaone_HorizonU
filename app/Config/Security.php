<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    /**
     * =============================================
     * KONFIGURASI CSRF (Cross-Site Request Forgery)
     * =============================================
     */

    /**
     * Metode Penyimpanan Token CSRF
     * - 'cookie': Simpan token di HTTP Cookie (kurang aman)
     * - 'session': Simpan token di Server Session (direkomendasikan)
     */
    public string $csrfProtection = 'session';

    /**
     * Nama Input/Token CSRF
     * Nama field yang akan digunakan untuk token CSRF di form
     */
    public string $tokenName = 'csrf_token';

    /**
     * Nama Header CSRF
     * Digunakan untuk request AJAX/API
     */
    public string $headerName = 'X-CSRF-TOKEN';

    /**
     * Nama Cookie CSRF
     * Hanya digunakan jika $csrfProtection = 'cookie'
     */
    public string $cookieName = 'csrf_cookie';

    /**
     * Masa Berlaku Token CSRF (dalam detik)
     * Default: 2 jam (7200 detik)
     */
    public int $expires = 7200;

    /**
     * Regenerasi Token setelah Submit
     * - true: Buat token baru setiap submit (lebih aman)
     * - false: Gunakan token yang sama selama belum expired
     */
    public bool $regenerate = true;

    /**
     * Redirect saat CSRF Validation Gagal
     * - true: Redirect ke halaman sebelumnya
     * - false: Tampilkan error page (direkomendasikan di production)
     */
    public bool $redirect = (ENVIRONMENT === 'development');

    /**
     * =============================================
     * KONFIGURASI KEAMANAN TAMBAHAN
     * =============================================
     */

    /**
     * SameSite Cookie Setting
     * Perlindungan terhadap serangan CSRF via cookie
     * Nilai: 'Lax', 'Strict', 'None'
     */
    public string $cookieSameSite = 'Lax';

    /**
     * Daftar URL yang Dikecualikan dari CSRF Protection
     * Contoh: Webhook, API endpoint tertentu
     */
    public array $excludeURIs = [
        'vote/request-otp',
        'vote/verify-otp'
    ];

    /**
     * Randomisasi Token CSRF
     * Menambahkan entropy tambahan untuk token
     */
    public bool $tokenRandomize = true;

    /**
     * Panjang Token CSRF (dalam karakter)
     */
    public int $tokenLength = 32;

    /**
     * =============================================
     * KONFIGURASI FILTER GLOBAL
     * =============================================
     */

    /**
     * Auto-sanitize Global Data
     * Membersihkan input GET/POST/COOKIE secara otomatis
     */
    public bool $autoSanitize = true;

    /**
     * Daftar Karakter yang Dianggap Berbahaya
     * Akan di-filter jika autoSanitize aktif
     */
    public string $dangerousCharacters = '~<>{}|`^\\\\';

    /**
     * =============================================
     * KONFIGURASI HEADER KEAMANAN
     * =============================================
     */

    /**
     * HTTP Strict Transport Security (HSTS)
     * Perlindungan terhadap serangan SSL stripping
     */
    public bool $enableHSTS = true;
    public int $hstsMaxAge = 31536000; // 1 tahun
    public bool $hstsIncludeSubDomains = true;
    public bool $hstsPreload = false;

    /**
     * X-Content-Type-Options
     * Mencegah MIME sniffing
     */
    public bool $enableXContentTypeOptions = true;

    /**
     * X-Frame-Options
     * Perlindungan terhadap clickjacking
     * Nilai: 'DENY', 'SAMEORIGIN', 'ALLOW-FROM uri'
     */
    public string $xFrameOptions = 'SAMEORIGIN';

    /**
     * Content Security Policy (CSP)
     * Kontrol sumber daya yang boleh dimuat
     */
    public bool $enableCSP = false;
    public string $cspDefaultSrc = "'self'";
    public string $cspScriptSrc = "'self' 'unsafe-inline'";
    public string $cspStyleSrc = "'self' 'unsafe-inline'";
    public string $cspImgSrc = "'self' data:";
    public string $cspConnectSrc = "'self'";
    public string $cspReportURI = '';

    /**
     * =============================================
     * KONFIGURASI LAINNYA
     * =============================================
     */

    /**
     * Batas Ukuran Upload File (dalam bytes)
     */
    public int $maxUploadSize = 2048000; // 2MB

    /**
     * Ekstensi File yang Dianggap Berbahaya
     */
    public array $dangerousFileExtensions = [
        'php',
        'php3',
        'php4',
        'php5',
        'php7',
        'phtml',
        'html',
        'htm',
        'js',
        'exe'
    ];
}
