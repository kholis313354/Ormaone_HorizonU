<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative bg-color-red">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo-container" style="text-align: center; padding: 20px 0;">
                <a href="<?= base_url() ?>" class="logo-link">
                    <img src="<?= base_url('dist/landing/assets/img/logo1.png') ?>" alt="OrmaOne" class="sidebar-logo"
                        style="width: 65px; height: auto; max-height: 150px; object-fit: contain; font-size:medium; color:#980517 !important;"><span
                        style="color:#980517 !important;">OrmaOne</span>
                </a>
            </div>
            <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--system-uicons" width="20" height="20"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                    <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                            opacity=".3"></path>
                        <g transform="translate(-210 -1)">
                            <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                            <circle cx="220.5" cy="11.5" r="4"></circle>
                            <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                            </path>
                        </g>
                    </g>
                </svg>
                <div class="form-check form-switch fs-6">
                    <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                    <label class="form-check-label"></label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                    viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                    </path>
                </svg>
            </div>
            <div class="sidebar-toggler  x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    <style>
        .sidebar-wrapper .menu .sidebar-item.active>.sidebar-link {
            background-color: #8a1919;
            /* Active Red */
        }

        /* Red for active submenu or specifically requested items */
        .sidebar-wrapper .menu .submenu-item.active>.submenu-link {
            background-color: #8a1919 !important;
            /* Solid Red */
            color: #ffffff !important;
            /* White Text */
            font-weight: bold;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(138, 25, 25, 0.3);
            transition: all 0.3s ease;
        }

        .sidebar-wrapper .menu .submenu-item.active>.submenu-link:hover {
            background-color: #8a1919 !important;
        }

        /* If the user wants the main parent to be red */
        .sidebar-wrapper .menu .sidebar-item.active>.sidebar-link {
            background-color: #8a1919 !important;
            /* Active Red */
            box-shadow: 0 5px 10px rgba(138, 25, 25, 0.3);
        }
    </style>
    <div class="sidebar-menu">
        <?php
        function isActive(array $uri)
        {
            $current_url = (string) current_url(true);
            foreach ($uri as $u) {
                // Check exact match first
                // Use urldecode to handle cases like AD/ART becoming AD%2FART
                if (urldecode($current_url) == urldecode($u)) {
                    return 'active submenu-open';
                }

                // Also check if the URL matches but might have extra query params (though for exact highlighting of submenu we usually want exact match of that param)
                // But for "Document" parent logic:
                if (strpos($u, '?') === false) {
                    // Parent menu logic: match path prefix
                    if (strpos(urldecode($current_url), urldecode($u)) === 0) {
                        return 'active submenu-open';
                    }
                }
            }
            return '';
        }

        // Ensure esc() function is available
        if (!function_exists('esc')) {
            function esc($data, $context = 'html')
            {
                return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
            }
        }

        $menus = [
            'dashboard' => [
                'icon' => 'bi bi-speedometer2',
                'title' => 'Dashboard',
                'url' => url_to('dashboard'),
            ],
            'Daftar' => [
                'icon' => 'bi bi-building',
                'title' => 'Daftar',
                'url' => '#',
                'submenu' => [
                    'list' => [
                        'title' => 'Organisasi',
                        'url' => url_to('organisasi.item.index'),
                    ],
                    'fakultas' => [
                        'title' => 'Fakultas',
                        'url' => url_to('fakultas.index'),
                    ],

                ],
            ],
            'E-Voting' => [
                'icon' => 'bi bi-clipboard-check',
                'title' => 'E-Voting', // Diubah dari 'Daftar' menjadi 'E-Voting'
                'url' => '#',
                'submenu' => [
                    'list' => [
                        'title' => 'Kandidat',
                        'url' => url_to('organisasi.anggota.index'),
                    ],
                    'pemilihan' => [
                        'title' => 'E-Voting Waktu',
                        'url' => url_to('pemilihan.index'),
                    ],
                    'Mahasiswa Voting' => [
                        'title' => 'Mahasiswa Voting',
                        'url' => url_to('organisasi.mahasiswa.index'),
                    ],
                    'Pemilihan Calon' => [
                        'title' => 'E-voting Kandidat',
                        'url' => url_to('pemilihan.calon.index'),
                    ],
                    'kepengurusan' => [
                        'title' => 'Kepengurusan',
                        'url' => url_to('organisasi.kepengurusan.index'),
                    ],
                ],
            ],
            'Document' => [
                'icon' => 'bi bi-archive-fill',
                'title' => 'Document',
                'url' => url_to('document.index'),
                'submenu' => [
                    'AD/ART' => [
                        'title' => 'AD/ART',
                        'url' => url_to('document.index') . '?kategori=AD/ART',
                    ],
                    'PB' => [
                        'title' => 'PB',
                        'url' => url_to('document.index') . '?kategori=PB',
                    ],
                    'CA' => [
                        'title' => 'CA',
                        'url' => url_to('document.index') . '?kategori=CA',
                    ],
                    'PRS' => [
                        'title' => 'PRS',
                        'url' => url_to('document.index') . '?kategori=PRS',
                    ],
                    'POA' => [
                        'title' => 'POA',
                        'url' => url_to('document.index') . '?kategori=POA',
                    ],
                    'KPI' => [
                        'title' => 'KPI',
                        'url' => url_to('document.index') . '?kategori=KPI',
                    ],
                ],
            ],
            'Kalender' => [
                'icon' => 'bi bi-calendar-event',
                'title' => 'Kalender',
                'url' => url_to('kalender.index'),
            ],
            'E-sertifikat' => [
                'icon' => 'bi bi-award-fill',
                'title' => 'E-Sertifikat',
                'url' => url_to('sertifikat.index'),
            ],
            'Blogger' => [
                'icon' => 'bi bi-journal-text',
                'title' => 'Blogger',
                'url' => base_url('page/berita'),
            ],
            'Struktur' => [
                'icon' => 'bi bi-diagram-3-fill',
                'title' => 'Struktur Organisasi',
                'url' => url_to('struktur.index'), // Default untuk admin
                'url_public' => null, // Akan di-set dinamis
            ],
            'Gform' => [
                'icon' => 'bi bi-file-earmark-text',
                'title' => 'Gform',
                'url' => url_to('form.index'),
            ],
            'Users' => [
                'icon' => 'bi bi-people-fill',
                'title' => 'Users',
                'url' => url_to('user.index'),
            ],
            'Logout' => [
                'icon' => 'bi bi-box-arrow-right',
                'title' => 'Logout',
                'url' => url_to('logout'),
            ],
            'Keamanan' => [
                'icon' => 'bi bi-shield-lock-fill',
                'title' => 'Keamanan',
                'url' => url_to('admin.security.index'),
            ],
        ];
        ?>

        <ul class="menu">
            <?php if (!session()->get('isLoggedIn')): ?>
                <li class="sidebar-title">Menu</li>
                <li class="sidebar-item <?= isActive([url_to('login')]) ?>">
                    <a href="<?= url_to('login') ?>" class="sidebar-link">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login</span>
                    </a>
                </li>
            <?php else: ?>
                <?php
                $level = session()->get('level');
                $organisasiId = session()->get('organisasi_id');

                // Load OrganisasiModel untuk mendapatkan data organisasi
                $organisasiModel = new \App\Models\OrganisasiModel();
                $organisasi = null;
                if ($organisasiId) {
                    $organisasi = $organisasiModel->find($organisasiId);
                }

                // Set menu allowed berdasarkan level
                $menus_allowed = [];
                if ($level == 1) { // SuperAdmin
                    $menus_allowed = [
                        'dashboard',
                        'Daftar',
                        'E-Voting',
                        'Gform',
                        'Logout'
                    ];
                    $menuTitle = 'Menu SuperAdmin';
                } elseif ($level == 2) { // Admin
                    $menus_allowed = [
                        'dashboard',
                        'Daftar',
                        'Kalender',
                        'Aspirasi',
                        'E-sertifikat',
                        'Blogger',
                        'Document',
                        'Struktur',
                        'Gform',
                        'Keamanan',
                        'Users',
                        'Logout'
                    ];
                    $menuTitle = 'Menu Admin';
                } elseif ($level == 0) { // Anggota Organisasi
                    $menus_allowed = [
                        'dashboard', // Ditambahkan dashboard sesuai permintaan
                        'Document',
                        'Kalender',
                        'Aspirasi',
                        'E-sertifikat',
                        'Blogger',
                        'Struktur', // Tambahkan menu Struktur untuk anggota organisasi
                        'Gform',
                        'Logout'
                    ];
                    $menuTitle = 'Menu Ormawa';

                    // Set URL struktur untuk anggota organisasi (link ke admin struktur index)
                    if ($organisasiId) {
                        $menus['Struktur']['url'] = url_to('struktur.index');
                    }
                } else {
                    $menuTitle = 'Menu';
                }
                ?>

                <li class="sidebar-title"><?= $menuTitle ?></li>

                <?php if ($organisasi && !empty($organisasi['name'])): ?>
                    <li class="sidebar-item"
                        style="padding: 10px 20px; background-color: rgba(160, 29, 29, 0.1); border-left: 3px solid #A01D1D; margin: 10px 0;">
                        <div style="color: #A01D1D; font-weight: 600; font-size: 0.9rem;">
                            <i class="bi bi-building"></i>
                            <span><?= esc($organisasi['name']) ?></span>
                        </div>
                    </li>
                <?php endif; ?>

                <?php foreach ($menus as $key => $menu): ?>
                    <?php if (in_array($key, $menus_allowed)): ?>
                        <?php
                        // Tentukan URL yang akan digunakan
                        $menuUrl = $menu['url'];
                        // Untuk semua level, gunakan struktur.index (admin view)
                        // URL sudah di-set di atas berdasarkan level
                        ?>
                        <li class="sidebar-item <?= isActive([$menuUrl]) ?> <?= isset($menu['submenu']) ? 'has-sub' : '' ?>">
                            <a href="<?= $menuUrl ?>" class="sidebar-link">
                                <i class="<?= $menu['icon'] ?>"></i>
                                <span><?= $menu['title'] ?></span>
                            </a>
                            <?php if (isset($menu['submenu'])): ?>
                                <?php
                                // Tentukan menu mana yang boleh menampilkan submenu berdasarkan level
                                $showSubmenu = false;
                                if ($key == 'Document') {
                                    // Menu Document bisa ditampilkan untuk semua level yang memiliki akses
                                    $showSubmenu = in_array($key, $menus_allowed);
                                } elseif ($key == 'E-Voting') {
                                    // Menu E-Voting hanya untuk level 1 (SuperAdmin)
                                    $showSubmenu = ($level == 1);
                                } elseif (in_array($level, [1, 2])) {
                                    // Menu lain (Daftar) untuk level 1 dan 2
                                    $showSubmenu = true;
                                }
                                ?>
                                <?php if ($showSubmenu): ?>
                                    <ul class="submenu <?= isActive(array_column($menu['submenu'], 'url')) ?>">
                                        <?php foreach ($menu['submenu'] as $sub): ?>
                                            <li class="submenu-item <?= isActive([$sub['url']]) ?>">
                                                <a href="<?= $sub['url'] ?>" class="submenu-link"><?= $sub['title'] ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>