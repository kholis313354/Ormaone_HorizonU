<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Base Site URL
     * --------------------------------------------------------------------------
     */
    public string $baseURL = 'http://localhost:8080';

    /**
     * --------------------------------------------------------------------------
     * Allowed Hostnames
     * --------------------------------------------------------------------------
     */
    public array $allowedHostnames = [];

    /**
     * --------------------------------------------------------------------------
     * Index File
     * --------------------------------------------------------------------------
     */
    public string $indexPage = '';

    /**
     * --------------------------------------------------------------------------
     * URI PROTOCOL
     * --------------------------------------------------------------------------
     */
    public string $uriProtocol = 'REQUEST_URI';

    /**
     * --------------------------------------------------------------------------
     * Allowed URL Characters
     * --------------------------------------------------------------------------
     */
    public string $permittedURIChars = 'a-z 0-9~%.:_\-';

    /**
     * --------------------------------------------------------------------------
     * Default Locale
     * --------------------------------------------------------------------------
     */
    public string $defaultLocale = 'id';
    // ... (lines 50-62 omitted) ...
    public array $supportedLocales = ['id', 'en'];

    /**
     * --------------------------------------------------------------------------
     * Negotiate Locale
     * --------------------------------------------------------------------------
     *
     * If true, the current Request object will automatically determine the
     * language to use based on the value of the Accept-Language header.
     *
     * If false, no automatic detection will be performed.
     */
    public bool $negotiateLocale = true;

    /**
     * --------------------------------------------------------------------------
     * Application Timezone
     * --------------------------------------------------------------------------
     */
    public string $appTimezone = 'Asia/Jakarta'; // Diubah ke zona waktu Indonesia

    /**
     * --------------------------------------------------------------------------
     * Default Character Set
     * --------------------------------------------------------------------------
     */
    public string $charset = 'UTF-8';

    /**
     * --------------------------------------------------------------------------
     * Force Global Secure Requests
     * --------------------------------------------------------------------------
     */
    public bool $forceGlobalSecureRequests = false;

    /**
     * --------------------------------------------------------------------------
     * Reverse Proxy IPs
     * --------------------------------------------------------------------------
     */
    public array $proxyIPs = [];

    /**
     * --------------------------------------------------------------------------
     * Content Security Policy
     * --------------------------------------------------------------------------
     */
    public bool $CSPEnabled = true; // Diaktifkan untuk keamanan tambahan

    /**
     * --------------------------------------------------------------------------
     * Session Configuration
     * --------------------------------------------------------------------------
     * Ditambahkan untuk keamanan session
     */
    public $session = [
        'driver' => 'CodeIgniter\Session\Handlers\FileHandler',
        'cookieName' => 'ci_session',
        'expiration' => 7200, // 2 jam
        'savePath' => WRITEPATH . 'session',
        'matchIP' => false,
        'timeToUpdate' => 300,
        'regenerateDestroy' => true,
        'cookieDomain' => '',
        'cookieSecure' => (ENVIRONMENT === 'production'),
        'cookieHTTPOnly' => true,
        'cookieSameSite' => 'Lax',
        'sidRegexp' => '[0-9a-f]{40}'
    ];

    /**
     * --------------------------------------------------------------------------
     * Cookie Configuration
     * --------------------------------------------------------------------------
     * Ditambahkan untuk keamanan cookie
     */
    public $cookie = [
        'prefix' => '',
        'domain' => '',
        'path' => '/',
        'secure' => (ENVIRONMENT === 'production'),
        'httponly' => true,
        'samesite' => 'Lax',
    ];
}