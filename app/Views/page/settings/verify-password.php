<?= $this->extend('components/layouts/app') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .verify-password-container {
        max-width: 600px;
        margin: 3rem auto;
        padding: 2rem;
    }

    .verify-card {
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        text-align: center;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .success-icon i {
        font-size: 2.5rem;
        color: #fff;
    }

    .verify-card h2 {
        color: #1f2937;
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .verify-card p {
        color: #6b7280;
        font-size: 1rem;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .btn-dashboard {
        background-color: #dc2626;
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 2rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .btn-dashboard:hover {
        background-color: #b91c1c;
        color: #ffffff;
    }

    .info-section {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 1rem;
        margin-top: 2rem;
        border-radius: 0.25rem;
        text-align: left;
    }

    .info-section p {
        margin: 0;
        color: #1e40af;
        font-size: 0.875rem;
    }

    /* Dark Mode Support */
    [data-bs-theme="dark"] .verify-card {
        background: #1e1e2d;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
    }

    [data-bs-theme="dark"] .verify-card h2 {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .verify-card p {
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .info-section {
        background: #1e3a5f;
        border-left-color: #60a5fa;
    }

    [data-bs-theme="dark"] .info-section p {
        color: #93c5fd;
    }
</style>

<div class="verify-password-container">
    <div class="verify-card">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h2>Password Berhasil Diubah!</h2>
        <p>Password Anda telah berhasil diubah. Silakan gunakan password baru Anda untuk login ke akun Anda.</p>

        <a href="<?= url_to('dashboard') ?>" class="btn-dashboard">
            <i class="fas fa-home me-2"></i>Kembali ke Dashboard
        </a>

        <div class="info-section">
            <p><i class="fas fa-shield-alt me-2"></i><strong>Tips Keamanan:</strong> Pastikan Anda tidak membagikan
                password Anda kepada siapapun. Jika Anda merasa akun Anda tidak aman, segera ubah password Anda lagi.
            </p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>