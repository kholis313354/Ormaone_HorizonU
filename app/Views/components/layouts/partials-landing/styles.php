<!-- Favicons -->
<link href="<?= base_url('dist/landing/assets/img/logo111.png') ?>" rel="icon">
<link href="<?= base_url('dist/landing/assets/img/logo111.png') ?>" rel="apple-touch-icon">
<!-- Ganti dengan logo OrmaOne -->
<link href="<?= base_url('dist/landing/assets/img/logo111.png') ?>" rel="icon" sizes="192x192">
<link href="<?= base_url('dist/landing/assets/img/logo111.png') ?>" rel="apple-touch-icon">
<link href="<?= base_url('dist/landing/assets/img/logo111.png') ?>" rel="icon" sizes="32x32">
<link href="<?= base_url('dist/landing/assets/img/logo111.png') ?>" rel="icon" sizes="16x16">
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
<link
href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
rel="stylesheet">

<!-- Preconnect untuk CDN (optimasi network) -->
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
<link rel="dns-prefetch" href="https://fonts.googleapis.com">
<link rel="dns-prefetch" href="https://fonts.gstatic.com">

<!-- Vendor CSS Files -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" crossorigin>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" crossorigin>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


<!-- Main CSS File -->
<link href="<?= base_url('dist/landing/assets/css/main1.css') ?>" rel="stylesheet">
<style>
/* loading css */
.loading-container {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    background-color: #ffffff;
    z-index: 9999999;
}

.container2 {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}

.loader {
    width: 500px;
    height: 500px;
    border: 0px solid white;
    position: absolute;
}
</style>


<!-- script loading menggunakan ajax awal - defer untuk non-blocking -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js" defer></script>
<script defer>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery !== 'undefined') {
        jQuery(window).on("load", function () {
            jQuery(".loading-container").fadeOut(1000);
        });
    } else {
        // Fallback jika jQuery belum load
        window.addEventListener('load', function() {
            const loader = document.querySelector('.loading-container');
            if (loader) {
                loader.style.transition = 'opacity 1s';
                loader.style.opacity = '0';
                setTimeout(() => loader.remove(), 1000);
            }
        });
    }
});
</script>
<!-- script loading akhir -->