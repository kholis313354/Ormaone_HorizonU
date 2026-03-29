<?= $this->extend('components/layouts/auth') ?>
<?= $this->section('title') ?>
Login
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?php $prefillEmail = session()->getFlashdata('login_prefill_email'); ?>
<div class="container d-flex justify-content-right align-items-center">
    <div class="text">
        <span>Selamat datang</span>
        <span>di Orma<strong>One</strong></span>
    </div>
</div>
<div class="container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo">
                <a href="<?= url_to('home') ?>" class="logo-text text-decoration-none" style="color: #980517;">
                    <img src="<?= base_url('dist/landing/assets/img/logo111.png'); ?>" alt="">
                    <div class="logo-text text-decoration-none" style="color: #980517;">⬅️Home</div>
                </a>
                <h4>MASUK</h4>
            </div>
        </div>
        <form action="<?= url_to('login.post') ?>" method="POST" autocomplete="off">
            <?php include_once(APPPATH . 'Views/components/flash.php'); ?>
            <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control" placeholder="Email" name="email" 
                       value="<?= esc(old('email') ?: ($prefillEmail ?? '')) ?>" autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control" placeholder="******" 
                       name="password" autocomplete="off">
            </div>
            <div class="d-grid">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-login" style="background-color: #980517; color:#fff;">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>