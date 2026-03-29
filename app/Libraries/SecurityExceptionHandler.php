<?php

namespace App\Libraries;

use CodeIgniter\Debug\ExceptionHandler;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;
use App\Models\SecurityLogModel;

class SecurityExceptionHandler implements ExceptionHandlerInterface
{
    protected $defaultHandler;

    public function __construct($config)
    {
        // Instantiate the default handler to delegate rendering to it
        $this->defaultHandler = new ExceptionHandler($config);
    }

    public function handle(Throwable $exception, RequestInterface $request, ResponseInterface $response, int $statusCode, int $exitCode): void
    {
        // Log to database first
        $this->logToDatabase($exception);

        // Then call default handler to display error page
        $this->defaultHandler->handle($exception, $request, $response, $statusCode, $exitCode);
    }

    protected function logToDatabase(Throwable $exception)
    {
        try {
            // Filter only specific security-related exceptions or suspicious ones
            $exceptionName = get_class($exception);

            // List of exceptions we consider "Security Events" or "Bad Requests"
            $securityExceptions = [
                'CodeIgniter\HTTP\Exceptions\BadRequestException', // URI disallowed chars
                'CodeIgniter\Exceptions\PageNotFoundException',     // 404
                'CodeIgniter\View\Exceptions\ViewException',       // Invalid file
            ];

            // Determine Event Type
            $eventType = 'Error';
            $isSecurityEvent = false;

            if (in_array($exceptionName, $securityExceptions)) {
                $isSecurityEvent = true;
                if (strpos($exceptionName, 'BadRequestException') !== false) {
                    $eventType = 'Blocked Attack'; // Disallowed chars
                } elseif (strpos($exceptionName, 'PageNotFoundException') !== false) {
                    $eventType = '404 Not Found';
                } elseif (strpos($exceptionName, 'ViewException') !== false) {
                    $eventType = 'Invalid View';
                }
            } else {
                // For other errors (500), usually we don't log to security log unless we want to monitor application health too
                // User asked for "health of website", so logging critical errors is good.
                $eventType = 'System Error';
                $isSecurityEvent = true;
            }

            if ($isSecurityEvent) {
                // Get Request Info
                $request = service('request');
                $ip = $request->getIPAddress();
                $agent = (string) $request->getUserAgent();
                $url = (string) current_url();
                $method = $request->getMethod();

                // --- TOOL DETECTION START ---
                $toolName = '';
                $uaLower = strtolower($agent);
                if (strpos($uaLower, 'nikto') !== false)
                    $toolName = '[Nikto]';
                elseif (strpos($uaLower, 'sqlmap') !== false)
                    $toolName = '[Sqlmap]';
                elseif (strpos($uaLower, 'nmap') !== false)
                    $toolName = '[Nmap]';
                elseif (strpos($uaLower, 'dirbuster') !== false)
                    $toolName = '[DirBuster]';
                elseif (strpos($uaLower, 'gobuster') !== false)
                    $toolName = '[Gobuster]';
                elseif (strpos($uaLower, 'hydra') !== false)
                    $toolName = '[Hydra]';
                elseif (strpos($uaLower, 'python') !== false)
                    $toolName = '[Script (Python)]';
                elseif (strpos($uaLower, 'curl') !== false)
                    $toolName = '[Curl]';
                // Pattern based detection
                if (strpos($url, '.php') === false && strpos($url, '.') !== false) {
                    // Suspicious extensions like .asp, .exe, .pl often used by scanners
                    $ext = pathinfo($url, PATHINFO_EXTENSION);
                    if (in_array($ext, ['asp', 'aspx', 'jsp', 'cgi', 'pl', 'exe', 'bat', 'sh', 'env', 'config'])) {
                        $toolName .= ' [Scanner Probe]';
                    }
                }
                // --- TOOL DETECTION END ---

                // --- OPTIMIZATION START ---
                // IMPROVED: Remove URL from cache key!
                // If IP 1.2.3.4 triggers "404 Not Found", we log it ONCE.
                // We don't care if they check /admin, /login, /test -> it's all "404 Not Found" spam.
                $cacheKey = 'sec_log_' . md5($ip . $eventType); // URL removed from key

                if (!cache()->get($cacheKey)) {
                    // Only log to DB if not in cache

                    $description = $exception->getMessage();
                    if ($toolName) {
                        $description = $toolName . ' ' . $description;
                    }

                    // Instantiate Model and Log
                    $model = new SecurityLogModel();
                    $model->logEvent($ip, $agent, $url, $method, $eventType, $description);

                    // Set cache for 5 minutes (300 seconds)
                    // Allows logging same error type from same IP only once every 5 mins
                    cache()->save($cacheKey, true, 300);
                }
                // --- OPTIMIZATION END ---
            }

        } catch (\Throwable $e) {
            // Fail silently if DB logging fails to avoid infinite loops
            log_message('error', 'Failed to log security event to DB: ' . $e->getMessage());
        }
    }
}
