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
    <meta name="google-site-verification" content="FwN1D-Z9GMGAv86tIcl-fSvB-c2yR6eXTjdgJ5BmVbQ" />
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= $this->renderSection('title') ?> - Organisasi Mahasiswa</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="google-site-verification" content="lbNnbbqG1ghIdFl6l92j9OOhdeY-JgHY4pNdVnJ7_YI" />
    <?php include_once 'partials-landing/styles.php'; ?>
    <?= $this->renderSection('styles') ?>
</head>
<style>
    /* Loading Screen Full Width & Height */
    .loading-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        z-index: 999999;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.5s ease-out, visibility 0.5s;
    }

    /* Optional: Ensure body doesn't scroll while loading if handled by JS, 
       but basic CSS is enough for visual coverage */
</style>

<body class="index-page">
    <!-- loading awal-->
    <div class="loading-container">
        <div class="container2">
            <img src="<?= base_url('dist/landing/assets/img/loading55.gif') ?>" class="loader" alt="Loading"
                loading="eager" decoding="async">
        </div>
    </div>
    <!-- loading akhir -->
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="<?= url_to('home') ?>" class="logo d-flex align-items-center me-auto">
                <img src="<?= base_url('dist/landing/assets/img/logo111.png') ?>" alt="OrmaOne Logo" width="60"
                    height="60" loading="eager" decoding="async">
                <h1 class="sitename" style="color: #A01D1D; width: 20;">OrmaOne</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <?php include_once 'partials-landing/navigation.php'; ?>
            </nav>

            <a class="btn-getstarted" href="<?= url_to('login') ?>">Login</a>

            <i class="mobile-nav-toggle d-xl-none bi bi-list"
                style="cursor: pointer !important; z-index: 99999 !important; position: relative !important; pointer-events: auto !important;"></i>

        </div>
    </header>

    <main class="main">
        <?= $this->renderSection('content') ?>
    </main>

    <?php include_once 'partials-landing/footer.php'; ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short text-white"></i></a>
    <!-- Preloader -->
    <!-- <div id="preloader"></div> -->

    <?php include_once 'partials-landing/scripts.php'; ?>
    <?= $this->renderSection('scripts') ?>
    <!-- Floating Language Switcher -->
    <div class="floating-lang-switcher">
        <div class="lang-btn" onclick="changeLanguage('id')" title="Bahasa Indonesia">
            <img src="https://flagcdn.com/w80/id.png" alt="Indonesia">
        </div>
        <div class="lang-btn" onclick="changeLanguage('en')" title="English">
            <img src="https://flagcdn.com/w80/gb.png" alt="English">
        </div>
    </div>

    <?php include_once 'partials-landing/translate_script.php'; ?>
</body>

</html>