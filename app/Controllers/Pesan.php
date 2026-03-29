<?php

namespace App\Controllers;

use App\Models\PesanModel;
use CodeIgniter\Email\Email;

class Pesan extends BaseController
{
    protected $pesanModel;

    public function __construct()
    {
        $this->pesanModel = new PesanModel();
        helper(['form', 'url']);
    }

    /**
     * Check if user is logged in and has access
     */
    private function checkAccess()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'))->with('error', 'Silakan login terlebih dahulu');
        }

        $level = session()->get('level');
        // Hanya level 0 dan 2 yang bisa akses
        if (!in_array($level, [0, 2])) {
            return redirect()->to(url_to('dashboard'))->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return null;
    }

    /**
     * Display list of pesan
     */
    public function index()
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $builder = $this->pesanModel;

        // Apply search filter
        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('subject', $search)
                ->orLike('message', $search)
                ->groupEnd();
        }

        // Apply status filter
        if (!empty($status) && in_array($status, ['unread', 'read', 'replied'])) {
            $builder = $builder->where('status', $status);
        }

        $pesans = $builder->orderBy('created_at', 'DESC')
                         ->paginate($perPage, 'default', $currentPage);

        $pager = $this->pesanModel->pager;

        // Get unread count for notification badge
        $unreadCount = $this->pesanModel->getUnreadCount();

        $data = [
            'title' => 'Aspirasi',
            'pesans' => $pesans,
            'pager' => $pager,
            'search' => $search,
            'status' => $status,
            'level' => session()->get('level'),
            'unreadCount' => $unreadCount,
        ];

        return view('page/pesan/index', $data);
    }

    /**
     * Display form to send email reply
     */
    public function sendEmail($id = null)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        if (!$id) {
            return redirect()->to(url_to('pesan.index'))->with('error', 'ID pesan tidak valid');
        }

        $pesan = $this->pesanModel->find($id);
        if (!$pesan) {
            return redirect()->to(url_to('pesan.index'))->with('error', 'Pesan tidak ditemukan');
        }

        // Mark as read when viewing
        if ($pesan['status'] == 'unread') {
            $this->pesanModel->markAsRead($id);
        }

        // Get unread count for notification badge
        $unreadCount = $this->pesanModel->getUnreadCount();

        $data = [
            'title' => 'Balas Aspirasi',
            'pesan' => $pesan,
            'unreadCount' => $unreadCount,
        ];

        return view('page/pesan/send-email', $data);
    }

    /**
     * Process email reply
     */
    public function processReply()
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        $id = $this->request->getPost('pesan_id');
        $replyMessage = trim($this->request->getPost('reply_message') ?? '');

        // Check if AJAX request
        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';

        if (!$id || empty($replyMessage)) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pesan balasan tidak boleh kosong'
                ]);
            }
            return redirect()->back()->withInput()->with('error', 'Pesan balasan tidak boleh kosong');
        }

        $pesan = $this->pesanModel->find($id);
        if (!$pesan) {
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pesan tidak ditemukan'
                ]);
            }
            return redirect()->to(url_to('pesan.index'))->with('error', 'Pesan tidak ditemukan');
        }

        // Send email with beautiful template
        try {
            $email = \Config\Services::email();
            $email->clear();

            $emailConfig = config('Email');
            $fromEmail = $emailConfig->SMTPUser ?? 'ormaonehorizonu@ormaone.com';
            $fromName = $emailConfig->fromName ?? 'OrmaOne';

            $email->setFrom($fromEmail, $fromName);
            $email->setTo($pesan['email']);
            $email->setSubject('Re: ' . $pesan['subject']);

            // Generate beautiful email template
            $emailMessage = $this->generateReplyEmailTemplate($pesan['name'], $pesan['subject'], $pesan['message'], $replyMessage);
            $email->setMessage($emailMessage);
            $email->setMailType('html');

            $emailSent = $email->send(false);

            if ($emailSent) {
                // Mark as replied
                $this->pesanModel->markAsReplied($id);
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Email balasan berhasil dikirim',
                        'redirect' => url_to('pesan.index')
                    ]);
                }
                
                return redirect()->to(url_to('pesan.index'))->with('success', 'Email balasan berhasil dikirim');
            } else {
                $errorMsg = 'Gagal mengirim email. Silakan coba lagi.';
                log_message('error', 'Email send failed: ' . $email->printDebugger(['headers']));
                
                if ($isAjax) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMsg
                    ]);
                }
                
                return redirect()->back()->withInput()->with('error', $errorMsg);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in processReply: ' . $e->getMessage());
            $errorMsg = 'Terjadi kesalahan saat mengirim email. Silakan coba lagi.';
            
            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $errorMsg
                ]);
            }
            
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }
    }

    /**
     * Generate beautiful email template for reply
     */
    private function generateReplyEmailTemplate($name, $originalSubject, $originalMessage, $replyMessage)
    {
        $baseUrl = base_url();
        $logoUrl = $baseUrl . 'dist/landing/assets/img/logo1.png';

        // Convert line breaks to HTML
        $replyMessageHtml = nl2br(htmlspecialchars($replyMessage));
        $originalMessageHtml = nl2br(htmlspecialchars($originalMessage));

        return '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan Aspirasi - OrmaOne</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            padding: 40px 20px 20px;
            text-align: center;
            background-color: #ffffff;
        }
        .logo-container {
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 10px;
        }
        .brand-name {
            font-size: 28px;
            font-weight: bold;
            color: #333333;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .email-body {
            padding: 30px 40px;
            color: #333333;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #666666;
        }
        .main-heading {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 20px;
            line-height: 1.4;
        }
        .instruction-text {
            font-size: 15px;
            color: #666666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .reply-section {
            background-color: #f9fafb;
            border-left: 4px solid #A01D1D;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .reply-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333333;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .reply-content {
            font-size: 15px;
            color: #333333;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        .original-message-section {
            background-color: #f3f4f6;
            border-left: 4px solid #9ca3af;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .original-message-section h4 {
            font-size: 16px;
            font-weight: 600;
            color: #4b5563;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .original-message-content {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .original-subject {
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
        }
        .important-note {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .important-note strong {
            color: #92400e;
            display: block;
            margin-bottom: 8px;
        }
        .important-note p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
            line-height: 1.6;
        }
        .email-footer {
            padding: 30px 40px;
            text-align: center;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
        .footer-text {
            font-size: 12px;
            color: #9ca3af;
            margin: 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px 10px;
                border-radius: 4px;
            }
            .email-body {
                padding: 20px;
            }
            .email-footer {
                padding: 20px;
            }
            .main-heading {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo-container">
                <img src="' . $logoUrl . '" alt="OrmaOne Logo">
            </div>
            <h1 class="brand-name">OrmaOne</h1>
        </div>
        <div class="email-body">
            <p class="greeting">Halo ' . htmlspecialchars($name) . ',</p>
            <h2 class="main-heading">Balasan Aspirasi Anda</h2>
            <p class="instruction-text">
                Terima kasih telah mengirimkan aspirasi Anda kepada kami. Berikut adalah balasan dari tim OrmaOne:
            </p>
            
            <div class="reply-section">
                <h3>Balasan dari Tim OrmaOne</h3>
                <div class="reply-content">' . $replyMessageHtml . '</div>
            </div>
            
            <div class="original-message-section">
                <h4>Pesan Asli Anda:</h4>
                <div class="original-subject">Subject: ' . htmlspecialchars($originalSubject) . '</div>
                <div class="original-message-content">' . $originalMessageHtml . '</div>
            </div>
            
            <div class="important-note">
                <strong>Penting:</strong>
                <p>Jika Anda memiliki pertanyaan lebih lanjut atau membutuhkan bantuan, jangan ragu untuk menghubungi kami kembali melalui form aspirasi di website OrmaOne.</p>
            </div>
        </div>
        <div class="email-footer">
            <p class="footer-text">2025 © OrmaOne E-Voting System. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>';
    }

    /**
     * Delete pesan
     */
    public function delete($id = null)
    {
        if ($redirect = $this->checkAccess()) return $redirect;

        if (!$id) {
            return redirect()->to(url_to('pesan.index'))->with('error', 'ID pesan tidak valid');
        }

        $pesan = $this->pesanModel->find($id);
        if (!$pesan) {
            return redirect()->to(url_to('pesan.index'))->with('error', 'Pesan tidak ditemukan');
        }

        if ($this->pesanModel->delete($id)) {
            return redirect()->to(url_to('pesan.index'))->with('success', 'Pesan berhasil dihapus');
        } else {
            return redirect()->to(url_to('pesan.index'))->with('error', 'Gagal menghapus pesan');
        }
    }
}

