<!-- Vendor JS Files - defer untuk non-blocking render -->
<!-- Note: Bootstrap harus load dulu sebelum library lain yang depend -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<!-- Main JS File -->
<script src="<?= base_url('dist/landing/assets/js/main.js') ?>" defer></script>
<!-- jQuery sudah di-load di head dengan defer, tidak perlu duplikat -->
<!-- Ensure mobile nav toggle works -->
<script>
(function() {
    function toggleMobileNav(e) {
        e.preventDefault();
        e.stopPropagation();
        const body = document.body;
        const toggleBtn = document.querySelector('.mobile-nav-toggle');
        body.classList.toggle('mobile-nav-active');
        if (toggleBtn) {
            toggleBtn.classList.toggle('bi-list');
            toggleBtn.classList.toggle('bi-x');
        }
        return false;
    }
    
    function ensureMobileNavToggle() {
        const toggleBtn = document.querySelector('.mobile-nav-toggle');
        if (toggleBtn) {
            // Remove old listeners
            const newBtn = toggleBtn.cloneNode(true);
            toggleBtn.parentNode.replaceChild(newBtn, toggleBtn);
            
            // Add new listeners
            newBtn.addEventListener('click', toggleMobileNav, true);
            newBtn.addEventListener('touchstart', toggleMobileNav, true);
            newBtn.addEventListener('touchend', function(e) { e.preventDefault(); }, true);
            
            // Ensure it's clickable
            newBtn.style.pointerEvents = 'auto';
            newBtn.style.cursor = 'pointer';
            newBtn.style.zIndex = '99999';
            newBtn.style.position = 'relative';
            newBtn.style.display = 'inline-block';
            
            console.log('Mobile nav toggle initialized');
        } else {
            console.warn('Mobile nav toggle button not found');
        }
    }
    
    // Use event delegation as fallback
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('mobile-nav-toggle')) {
            toggleMobileNav(e);
        }
    }, true);
    
    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', ensureMobileNavToggle);
    } else {
        ensureMobileNavToggle();
    }
    
    setTimeout(ensureMobileNavToggle, 100);
    setTimeout(ensureMobileNavToggle, 500);
    window.addEventListener('load', ensureMobileNavToggle);
})();
</script>
<!-- DataTables - hanya load jika diperlukan (conditional loading) -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css"></noscript>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.min.js" defer></script>
<script defer>
    document.addEventListener('DOMContentLoaded', function() {
        // Tunggu jQuery dan DataTables load
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
            if (jQuery('#datatable').length) {
                jQuery('#datatable').DataTable();
            }
        } else {
            // Retry jika belum ready
            setTimeout(function() {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined' && jQuery('#datatable').length) {
                    jQuery('#datatable').DataTable();
                }
            }, 500);
        }
    });
</script>
<!-- Schema.org Organization -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "OrmaOne",
  "url": "<?= base_url() ?>",
  "logo": "<?= base_url('dist/landing/assets/img/ormaone-logo.png') ?>",
  "sameAs": [
    "https://www.instagram.com/ormaone_official",
    "https://twitter.com/ormaone"
  ]
}
</script>