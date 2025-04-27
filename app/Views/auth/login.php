<?= $this->extend('components/layouts/auth') ?>
<?= $this->section('title') ?>
    Login
<?= $this->endSection() ?>
<?= $this->section('content') ?>
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
                    <img src="<?= base_url('dist/ormaone/img/logo1.png'); ?>" alt="">
                    <div class="logo-text">ormaone</div>
                </div>
                <h4>MASUK</h4>
            </div>
            <form action="<?= url_to('login.post') ?>" method="POST">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" placeholder="Masukkan Email Anda" name="email" value="<?= old('email') ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" placeholder="******" name="password">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-login">Masuk</button>
                </div>
            </form>
        </div>
    </div>
<?= $this->endSection() ?>
