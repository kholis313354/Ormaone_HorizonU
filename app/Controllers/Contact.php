<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PesanModel;
use App\Models\ContactSecurityModel;

class Contact extends Controller
{
    protected $pesanModel;
    protected $securityModel;

    public function __construct()
    {
        $this->pesanModel = new PesanModel();
        $this->securityModel = new ContactSecurityModel();
    }

    public function index()
    {
        return redirect()->to('/#contact');
    }

    public function send()
    {
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent()->getAgentString();

        // ========== LAYER 1: Rate Limiting ==========
        $throttler = \Config\Services::throttler();
        $safeIp = str_replace([':', '.'], '-', $ipAddress);

        // Rate limit: maksimal 3 pesan per 1 jam per IP
        if ($throttler->check('contact-' . $safeIp, 3, HOUR) === false) {
            log_message('warning', 'Rate limit exceeded for contact form from IP: ' . $ipAddress);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terlalu banyak percobaan. Silakan tunggu 1 jam sebelum mengirim pesan lagi.');
        }

        // ========== LAYER 2: IP & Email Blocking ==========
        if ($this->securityModel->isIpBlocked($ipAddress)) {
            log_message('warning', 'Blocked IP attempted contact form: ' . $ipAddress);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akses ditolak. Silakan hubungi administrator.');
        }

        // ========== LAYER 3: Honeypot Check ==========
        $honeypot = $this->request->getPost('website') ?? '';
        if (!empty($honeypot)) {
            // Bot detected - log and block
            log_message('warning', 'Honeypot triggered from IP: ' . $ipAddress);
            $this->securityModel->block($ipAddress, null, 86400, 'Honeypot triggered');
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pesan tidak valid.');
        }

        // ========== LAYER 4: Time-based Validation ==========
        $formStartTime = $this->request->getPost('form_start_time') ?? 0;
        $currentTime = time();
        $timeElapsed = $currentTime - $formStartTime;

        // Form harus diisi minimal 5 detik (mencegah bot yang terlalu cepat)
        if ($timeElapsed < 5) {
            log_message('warning', 'Form submitted too quickly from IP: ' . $ipAddress . ' (Time: ' . $timeElapsed . 's)');
            $this->securityModel->recordAttempt($ipAddress, null, 5, 3600);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Form dikirim terlalu cepat. Silakan isi form dengan lebih teliti.');
        }

        // Form tidak boleh diisi terlalu lama (lebih dari 1 jam)
        if ($timeElapsed > 3600) {
            log_message('warning', 'Form submitted after timeout from IP: ' . $ipAddress);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sesi form telah berakhir. Silakan refresh halaman dan coba lagi.');
        }

        // ========== LAYER 5: Input Sanitization ==========
        $name = trim($this->request->getPost('name') ?? '');
        $fromEmail = trim($this->request->getPost('email') ?? '');
        $subject = trim($this->request->getPost('subject') ?? '');
        $message = trim($this->request->getPost('message') ?? '');

        // Sanitize input
        $name = htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8');
        $fromEmail = filter_var($fromEmail, FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars(strip_tags($subject), ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars(strip_tags($message), ENT_QUOTES, 'UTF-8');

        // Check if email is from campus domain
        if (!str_ends_with($fromEmail, '@krw.horizon.ac.id')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gunakan Email Kampus. Email harus menggunakan domain @krw.horizon.ac.id');
        }

        // ========== LAYER 6: Spam Detection ==========
        // Check for spam keywords
        $spamKeywords = [
            'viagra',
            'casino',
            'lottery',
            'winner',
            'click here',
            'buy now',
            'limited time',
            'act now',
            'urgent',
            'free money',
            'get rich',
            'work from home',
            'make money',
            'guaranteed',
            'no risk',
            '100% free',
            'congratulations',
            'you have won'
        ];
        $messageLower = strtolower($message);
        $subjectLower = strtolower($subject);

        $spamCount = 0;
        foreach ($spamKeywords as $keyword) {
            if (strpos($messageLower, $keyword) !== false || strpos($subjectLower, $keyword) !== false) {
                $spamCount++;
            }
        }

        // Jika terlalu banyak spam keywords, block
        if ($spamCount >= 3) {
            log_message('warning', 'Spam detected from IP: ' . $ipAddress . ' (Keywords: ' . $spamCount . ')');
            $this->securityModel->block($ipAddress, $fromEmail, 86400, 'Spam keywords detected');
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pesan terdeteksi sebagai spam. Silakan gunakan bahasa yang lebih formal.');
        }

        // Check for suspicious email patterns
        $suspiciousEmailPatterns = ['/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i'];
        $emailDomain = substr(strrchr($fromEmail, "@"), 1);
        $suspiciousDomains = ['tempmail', 'guerrillamail', 'mailinator', '10minutemail', 'throwaway'];

        foreach ($suspiciousDomains as $domain) {
            if (stripos($emailDomain, $domain) !== false) {
                log_message('warning', 'Suspicious email domain from IP: ' . $ipAddress . ' (Email: ' . $fromEmail . ')');
                // Don't block, but log it
            }
        }

        // ========== LAYER 7: Validation ==========
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => [
                'label' => 'Nama',
                'rules' => 'required|min_length[2]|max_length[100]|regex_match[/^[a-zA-Z\s\.\-\']+$/]',
                'errors' => [
                    'required' => 'Nama harus diisi',
                    'min_length' => 'Nama minimal 2 karakter',
                    'max_length' => 'Nama maksimal 100 karakter',
                    'regex_match' => 'Nama hanya boleh mengandung huruf, spasi, titik, tanda hubung, dan apostrof'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[255]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'max_length' => 'Email maksimal 255 karakter'
                ]
            ],
            'subject' => [
                'label' => 'Subject',
                'rules' => 'required|min_length[3]|max_length[200]',
                'errors' => [
                    'required' => 'Subject harus diisi',
                    'min_length' => 'Subject minimal 3 karakter',
                    'max_length' => 'Subject maksimal 200 karakter'
                ]
            ],
            'message' => [
                'label' => 'Pesan',
                'rules' => 'required|min_length[10]|max_length[2000]',
                'errors' => [
                    'required' => 'Pesan harus diisi',
                    'min_length' => 'Pesan minimal 10 karakter',
                    'max_length' => 'Pesan maksimal 2000 karakter'
                ]
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Record failed attempt
            $this->securityModel->recordAttempt($ipAddress, $fromEmail, 5, 3600);

            // Ambil error messages
            $errors = $validation->getErrors();
            $errorMessage = 'Mohon perbaiki kesalahan berikut:<br>';
            foreach ($errors as $error) {
                $errorMessage .= '• ' . $error . '<br>';
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }

        // ========== LAYER 8: Email Blocking Check ==========
        if ($this->securityModel->isEmailBlocked($fromEmail)) {
            log_message('warning', 'Blocked email attempted contact form: ' . $fromEmail);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ini telah diblokir. Silakan gunakan email lain.');
        }

        // ========== LAYER 9: Record Attempt ==========
        $attemptResult = $this->securityModel->recordAttempt($ipAddress, $fromEmail, 5, 3600);
        if ($attemptResult['blocked']) {
            log_message('warning', 'IP blocked due to too many attempts: ' . $ipAddress);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terlalu banyak percobaan. IP Anda telah diblokir selama 24 jam.');
        }

        // ========== LAYER 10: Save to Database ==========
        try {
            $this->pesanModel->insert([
                'name' => $name,
                'email' => $fromEmail,
                'subject' => $subject,
                'message' => $message,
                'status' => 'unread'
            ]);

            // Log successful submission
            log_message('info', 'Contact form submitted successfully from IP: ' . $ipAddress . ' Email: ' . $fromEmail);
        } catch (\Exception $e) {
            log_message('error', 'Error saving pesan: ' . $e->getMessage() . ' IP: ' . $ipAddress);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pesan. Silakan coba lagi.');
        }

        // ========== LAYER 11: Send Email (Optional) ==========
        try {
            $email = \Config\Services::email();
            $email->setFrom($fromEmail, $name);
            $email->setTo('ormaonehorizonu@ormaone.com');
            $email->setSubject($subject);
            $email->setMessage($message);
            $emailSent = $email->send();
        } catch (\Exception $e) {
            log_message('error', 'Error sending email: ' . $e->getMessage());
            $emailSent = false;
        }

        // Success - pesan sudah tersimpan di database
        if ($emailSent) {
            return redirect()->back()->with('success', 'Terima kasih atas aspirasi Anda. Pesan Anda telah berhasil kami terima dan akan segera kami balas melalui email yang Anda berikan.');
        } else {
            return redirect()->back()->with('success', 'Terima kasih atas aspirasi Anda. Pesan Anda telah berhasil kami terima dan akan segera kami balas melalui email yang Anda berikan.');
        }
    }
}
