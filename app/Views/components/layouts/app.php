<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Ormaone</title>
    <link rel="shortcut icon"
        href="<?= base_url('/public/logo1.png') ?>"
        type="image/x-icon">
    <link rel="shortcut icon"
        href="<?= base_url('/public/dist/ormaone/img/logo1.png') ?>">
    <?php include_once('partials/styles.php') ?>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <script src="<?= base_url('dist/assets/static/js/initTheme.js') ?>"></script>
    <div id="app">
        <div id="sidebar">
            <?php include_once('partials/sidebar.php'); ?>
        </div>
        <div id="main">
            <?= $this->renderSection('content') ?>
            <?php // include_once('partials/footer.php'); 
            ?>
        </div>
    </div>
    <?php include_once('partials/scripts.php'); ?>
    <?= $this->renderSection('scripts') ?>
</body>

</html>