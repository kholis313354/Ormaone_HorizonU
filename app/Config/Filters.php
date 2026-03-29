<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

// Tambahan untuk filter rate limit
use App\Filters\RateLimitFilter;

class Filters extends BaseFilters
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf' => CSRF::class,
        'toolbar' => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'invalidchars' => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'ipBlocker' => \App\Filters\IpBlocker::class, // Custom IP Blocker
        'cors' => Cors::class,
        'forcehttps' => ForceHTTPS::class,
        'pagecache' => PageCache::class,
        'performance' => PerformanceMetrics::class,
        'locale' => \App\Filters\LocaleFilter::class,
        'ratelimit' => \App\Filters\RateLimitFilter::class, // Filter Pembatas Kecepatan
    ];

    /**
     * List of special required filters.
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            //'toolbar', // Aktifkan hanya saat development
        ],
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, list<string>>
     */
    public array $globals = [
        'before' => [
            'ipBlocker', // Check IP Block first!
            // 'locale', // Temporarily disabled
            'csrf' => [
                'except' => [
                    'sertifikat/save',
                    'sertifikat/importBatch',
                    'sertifikat/uploadBaseFile',
                    'organisasi/mahasiswa/importBatch',
                    'vote/request-otp',
                    'vote/verify-otp',
                    'vote/*' // Wildcard untuk semua route vote
                ]
            ], // Disable CSRF untuk route tertentu
            'invalidchars',
            'ratelimit' => [
                'except' => [
                    'vote/*', // Jangan batasi request OTP (agar tidak gagal kirim)
                    'uploads/*', // Jangan batasi load gambar/file
                ]
            ], // Aktifkan Rate Limit dengan pengecualian
            'honeypot',
        ],
        'after' => [
            'secureheaders',
            'honeypot',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [
        'csrf' => [
            'except' => [
                'api/*',
                'auth/login',
                'vote/request-otp',
                'vote/verify-otp',
            ],
        ],
    ];
}
