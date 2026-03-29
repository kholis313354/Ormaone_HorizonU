<!DOCTYPE html>
<html lang="en">
<?php
// Security Headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; font-src 'self' https:;");
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Ormaone</title>
    <link rel="shortcut icon" href="<?= base_url('dist/landing/assets/img/logo111.png') ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url('dist/landing/assets/img/logo111.png') ?>">
    <?php include_once('partials/styles.php') ?>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <script src="<?= base_url('dist/assets/static/js/initTheme.js') ?>" defer></script>
    <div id="app">
        <div id="sidebar">
            <?php include_once('partials/sidebar.php'); ?>
        </div>
        <!-- <div id="sidebar">
            <?php include_once('partials/sidebar.php'); ?>
        </div> -->
        <div id="navbar">
            <?php include_once('partials/navbar.php'); ?>
        </div>
        <div id="main" class="d-flex flex-column min-vh-100">
            <div class="flex-grow-1">
                <?= $this->renderSection('content') ?>
            </div>

            <?php if (!isset($hideFooter) || !$hideFooter): ?>
                <?php include_once('partials/footer.php'); ?>
            <?php endif; ?>
        </div>
    </div>
    <?php include_once('partials/scripts.php'); ?>
    <?= $this->renderSection('scripts') ?>
</body>

</html>