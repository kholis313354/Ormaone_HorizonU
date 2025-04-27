<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?= $this->renderSection('title') ?>- Organisasi Mahasiswa</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <?php include_once 'partials-landing/styles.php'; ?>
    <?= $this->renderSection('styles') ?>
</head>

<body class="index-page">
    <!-- loading awal-->
    <div class="loading-container">
        <div class="container2">
            <img src="<?= base_url('dist/landing/assets/img/Loading2.gif') ?>" class="loader" alt="">
        </div>
    </div>
    <!-- loading akhir -->
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="#" class="logo d-flex align-items-center me-auto">
                <img src="<?= base_url('dist/landing/assets/img/logo1.png') ?>" alt="">
                <h1 class="sitename" style="color: #A01D1D;">OrmaOne</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <?php include_once 'partials-landing/navigation.php'; ?>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="<?= url_to('login') ?>">Login</a>

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
</body>

</html>
