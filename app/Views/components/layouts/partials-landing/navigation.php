<?php
// Ensure esc() function is available (CodeIgniter 4 default)
if (!function_exists('esc')) {
    function esc($data, $context = 'html') {
        return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// Load OrganisasiModel untuk mendapatkan data organisasi
$organisasiModel = new \App\Models\OrganisasiModel();
$organisasis = $organisasiModel->findAll();

// Function untuk mendapatkan priority berdasarkan prefix nama
function getPriority($name) {
    $nameUpper = strtoupper(trim($name));
    
    // HUSC di urutan 1 (paling atas)
    if (strpos($nameUpper, 'HUSC') === 0) {
        return 1;
    }
    // FSC di urutan 2
    if (strpos($nameUpper, 'FSC') === 0) {
        return 2;
    }
    // PR di urutan 3
    if (strpos($nameUpper, 'PR') === 0) {
        return 3;
    }
    // Lainnya di urutan 4
    return 4;
}

// Sort organisasi: HUSC -> FSC -> PR -> lainnya
usort($organisasis, function($a, $b) {
    $aPriority = getPriority($a['name']);
    $bPriority = getPriority($b['name']);
    
    // Jika priority berbeda, sort berdasarkan priority
    if ($aPriority !== $bPriority) {
        return $aPriority <=> $bPriority;
    }
    
    // Jika priority sama, sort berdasarkan name
    return strcmp($a['name'], $b['name']);
});
?>
<ul>
    <li><a href="<?= url_to('home') ?>" class="<?= set_active('home') ?>">Home</a></li>
    <li><a href="<?= url_to('berita') ?>" class="<?= set_active('berita') ?>">Berita</a></li>
    <li><a href="<?= url_to('sertifikat') ?>" class="<?= set_active('sertifikat') ?>">E-Sertifikat</a></li>
    <li><a href="<?= url_to('voting') ?>" class="<?= set_active('voting') ?>">E-Voting</a></li>
    <li class="dropdown">
        <a href="#" class="<?= set_active('struktur') ?> organisasi-link">
            <span>Organisasi</span>
            <i class="bi bi-chevron-down toggle-dropdown mobile-only" id="organisasi-toggle"></i>
        </a>
        <ul id="organisasi-dropdown" class="mobile-dropdown">
            <?php foreach ($organisasis as $org): ?>
                <li><a href="<?= url_to('struktur') ?>?org=<?= $org['id'] ?>"><?= esc($org['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </li>
</ul>

<style>
/* Toggle dropdown hanya muncul di mobile dan tablet */
.mobile-only {
    display: none;
}

@media (max-width: 991.98px) {
    .mobile-only {
        display: inline-block;
        margin-left: 0.5rem;
        font-size: 0.875rem;
        transition: transform 0.3s ease;
        cursor: pointer;
    }
    
    .mobile-only.active {
        transform: rotate(180deg);
    }
    
    /* Dropdown tersembunyi secara default di mobile */
    .mobile-dropdown {
        display: none;
    }
    
    .mobile-dropdown.show {
        display: block;
    }
    
    /* Prevent default link behavior saat klik toggle di mobile */
    .organisasi-link {
        position: relative;
    }
}

/* Desktop tetap hover */
@media (min-width: 992px) {
    .mobile-dropdown {
        display: none;
    }
    
    .dropdown:hover .mobile-dropdown {
        display: block;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('organisasi-toggle');
    const dropdown = document.getElementById('organisasi-dropdown');
    const link = document.querySelector('.organisasi-link');
    
    if (toggle && dropdown) {
        // Toggle dropdown saat icon diklik (mobile/tablet)
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            dropdown.classList.toggle('show');
            toggle.classList.toggle('active');
        });
        
        // Di mobile, prevent default link saat klik link "Organisasi"
        if (window.innerWidth <= 991.98) {
            link.addEventListener('click', function(e) {
                // Jika klik pada icon toggle, sudah di-handle di atas
                if (e.target === toggle || toggle.contains(e.target)) {
                    return;
                }
                // Jika klik pada span atau link, toggle dropdown juga
                dropdown.classList.toggle('show');
                toggle.classList.toggle('active');
                e.preventDefault();
            });
        }
        
        // Close dropdown saat klik di luar (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 991.98) {
                if (!link.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                    toggle.classList.remove('active');
                }
            }
        });
    }
});
</script>