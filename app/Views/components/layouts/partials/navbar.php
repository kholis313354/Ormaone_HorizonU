

<style>
    /* Memastikan dropdown menu terlihat dengan z-index tinggi */
    .navbar .dropdown-menu {
        z-index: 1050 !important;
        min-width: 200px;
    }
    
    /* Style untuk dropdown yang terbuka */
    .navbar .dropdown-menu.show {
        display: block !important;
    }
    
    /* Responsive Navbar Styles */
    .sb-topnav {
        padding: 0.5rem 1rem;
        min-height: 56px;
    }
    
    /* Mobile Styles (max-width: 767px) */
    @media (max-width: 767.98px) {
        .sb-topnav {
            padding: 0.5rem 0.75rem;
        }
        
        .sb-topnav .navbar-nav {
            flex-direction: row;
        }
        
        .sb-topnav .nav-link {
            padding: 0.5rem !important;
            font-size: 0.875rem;
        }
        
        .sb-topnav .nav-link i {
            font-size: 1.1rem !important;
        }
        
        .sb-topnav .dropdown-menu {
            min-width: 180px;
            font-size: 0.875rem;
        }
        
        .sb-topnav .text-nav {
            font-size: 0.875rem;
        }
        
        /* Hamburger button styling */
        #sidebarToggle {
            padding: 0.5rem;
            margin-right: 0.5rem;
        }
        
        #sidebarToggle i {
            font-size: 1.25rem;
        }
    }
    
    /* Extra Small Mobile (max-width: 575px) - Hanya icon */
    @media (max-width: 575.98px) {
        .sb-topnav {
            padding: 0.5rem 0.5rem;
        }
        
        .sb-topnav .nav-link {
            padding: 0.4rem !important;
        }
        
        .sb-topnav .nav-link i {
            font-size: 1rem !important;
        }
        
        .sb-topnav .dropdown-menu {
            min-width: 160px;
            font-size: 0.8125rem;
        }
        
        #sidebarToggle {
            padding: 0.4rem;
            margin-right: 0.25rem;
        }
        
        #sidebarToggle i {
            font-size: 1.1rem;
        }
    }
    
    /* Tablet Styles (768px - 991px) */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .sb-topnav {
            padding: 0.5rem 1rem;
        }
        
        .sb-topnav .nav-link {
            padding: 0.5rem 0.75rem;
        }
        
        .sb-topnav .dropdown-menu {
            min-width: 200px;
        }
        
        /* Hamburger button untuk tablet */
        #sidebarToggle {
            padding: 0.5rem;
            margin-right: 0.75rem;
        }
    }
    
    /* Desktop Styles (min-width: 992px) */
    @media (min-width: 992px) {
        .sb-topnav {
            padding: 0.75rem 1.5rem;
        }
        
        .sb-topnav .nav-link {
            padding: 0.5rem 1rem;
        }
        
        .sb-topnav .dropdown-menu {
            min-width: 220px;
        }
        
        /* Hide hamburger di desktop */
        #sidebarToggle {
            display: none !important;
        }
    }
    
    /* Flexbox untuk layout responsive */
    .sb-topnav .container-fluid {
        flex-wrap: nowrap;
    }
    
    /* Icon spacing */
    .sb-topnav .navbar-nav .nav-link span {
        white-space: nowrap;
    }
    
    /* Dropdown positioning untuk mobile */
    @media (max-width: 991.98px) {
        .sb-topnav .dropdown-menu {
            right: 0 !important;
            left: auto !important;
        }
    }
</style>

<script>
    (function() {
        'use strict';
        
        // Fungsi untuk toggle dropdown secara manual jika Bootstrap tidak tersedia
        function toggleDropdownManually(element) {
            // Cari dropdown menu - bisa jadi sibling atau di parent
            let dropdownMenu = element.nextElementSibling;
            if (!dropdownMenu || !dropdownMenu.classList.contains('dropdown-menu')) {
                dropdownMenu = element.parentElement.querySelector('.dropdown-menu');
            }
            
            if (dropdownMenu) {
                const isOpen = dropdownMenu.classList.contains('show');
                
                // Tutup semua dropdown lain
                document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                    if (menu !== dropdownMenu) {
                        menu.classList.remove('show');
                        const toggle = menu.previousElementSibling;
                        if (toggle && toggle.hasAttribute('data-bs-toggle')) {
                            toggle.setAttribute('aria-expanded', 'false');
                        } else {
                            const parentToggle = menu.parentElement.querySelector('[data-bs-toggle="dropdown"]');
                            if (parentToggle) {
                                parentToggle.setAttribute('aria-expanded', 'false');
                            }
                        }
                    }
                });
                
                // Toggle dropdown ini
                if (isOpen) {
                    dropdownMenu.classList.remove('show');
                    element.setAttribute('aria-expanded', 'false');
                } else {
                    dropdownMenu.classList.add('show');
                    element.setAttribute('aria-expanded', 'true');
                }
            }
        }
        
        // Fungsi untuk inisialisasi dropdown
        function initDropdowns() {
            // Inisialisasi dengan Bootstrap jika tersedia
            if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                // Inisialisasi dropdown Chat
                const chatDropdownElement = document.getElementById('navbarDropdownChat');
                if (chatDropdownElement && !chatDropdownElement.hasAttribute('data-dropdown-init')) {
                    chatDropdownElement.setAttribute('data-dropdown-init', 'true');
                    try {
                        const chatDropdown = new bootstrap.Dropdown(chatDropdownElement, {
                            autoClose: true
                        });
                        
                        chatDropdownElement.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            chatDropdown.toggle();
                        });
                    } catch (e) {
                        // Fallback ke manual toggle
                        chatDropdownElement.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            toggleDropdownManually(this);
                        });
                    }
                }
                
                // Inisialisasi dropdown Notification
                const notificationDropdownElement = document.getElementById('navbarDropdownNotification');
                if (notificationDropdownElement && !notificationDropdownElement.hasAttribute('data-dropdown-init')) {
                    notificationDropdownElement.setAttribute('data-dropdown-init', 'true');
                    try {
                        const notificationDropdown = new bootstrap.Dropdown(notificationDropdownElement, {
                            autoClose: true
                        });
                        
                        notificationDropdownElement.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            notificationDropdown.toggle();
                        });
                    } catch (e) {
                        // Fallback ke manual toggle
                        notificationDropdownElement.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            toggleDropdownManually(this);
                        });
                    }
                }
                
                // Inisialisasi dropdown User
                const userDropdownElement = document.getElementById('navbarDropdownUser');
                if (userDropdownElement && !userDropdownElement.hasAttribute('data-dropdown-init')) {
                    userDropdownElement.setAttribute('data-dropdown-init', 'true');
                    try {
                        const userDropdown = new bootstrap.Dropdown(userDropdownElement, {
                            autoClose: true
                        });
                        
                        userDropdownElement.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            userDropdown.toggle();
                        });
                    } catch (e) {
                        // Fallback ke manual toggle
                        userDropdownElement.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            toggleDropdownManually(this);
                        });
                    }
                }
            } else {
                // Jika Bootstrap tidak tersedia, gunakan manual toggle
                const dropdownToggles = document.querySelectorAll('[data-bs-toggle="dropdown"]:not([data-dropdown-init])');
                dropdownToggles.forEach(function(toggle) {
                    toggle.setAttribute('data-dropdown-init', 'true');
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        toggleDropdownManually(this);
                    });
                });
            }
        }
        
        // Event listener global (hanya sekali)
        let globalListenersAdded = false;
        function addGlobalListeners() {
            if (globalListenersAdded) {
                return;
            }
            
            // Menutup dropdown saat klik di luar
            document.addEventListener('click', function(event) {
                const clickedElement = event.target;
                const isDropdownToggle = clickedElement.closest('[data-bs-toggle="dropdown"]');
                const isDropdownMenu = clickedElement.closest('.dropdown-menu');
                const isDropdownItem = clickedElement.closest('.dropdown-item');
                
                if (!isDropdownToggle && !isDropdownMenu && !isDropdownItem) {
                    // Tutup semua dropdown yang terbuka
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(openMenu) {
                        openMenu.classList.remove('show');
                        const toggle = openMenu.previousElementSibling || openMenu.parentElement.querySelector('[data-bs-toggle="dropdown"]');
                        if (toggle) {
                            toggle.setAttribute('aria-expanded', 'false');
                        }
                    });
                }
            });
            
            // Prevent default untuk link placeholder
            document.addEventListener('click', function(event) {
                if (event.target.closest('.dropdown-item[href="#!"]')) {
                    event.preventDefault();
                }
            }, true);
            
            globalListenersAdded = true;
        }
        
        // Fungsi untuk toggle sidebar (hamburger menu)
        function initSidebarToggle() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            
            if (sidebarToggle && sidebarWrapper) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Toggle class active pada sidebar
                    if (sidebarWrapper.classList.contains('active')) {
                        sidebarWrapper.classList.remove('active');
                    } else {
                        sidebarWrapper.classList.add('active');
                    }
                });
            }
            
            // Tutup sidebar saat klik di luar (untuk mobile)
            if (window.innerWidth < 992) {
                document.addEventListener('click', function(e) {
                    if (sidebarWrapper && sidebarWrapper.classList.contains('active')) {
                        const isClickInsideSidebar = e.target.closest('.sidebar-wrapper');
                        const isClickOnToggle = e.target.closest('#sidebarToggle');
                        
                        if (!isClickInsideSidebar && !isClickOnToggle) {
                            sidebarWrapper.classList.remove('active');
                        }
                    }
                });
            }
            
            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    // Di desktop, pastikan sidebar selalu visible
                    if (window.innerWidth >= 992) {
                        if (sidebarWrapper) {
                            sidebarWrapper.classList.add('active');
                        }
                    }
                }, 250);
            });
        }
        
        // Jalankan inisialisasi
        addGlobalListeners(); // Tambahkan global listeners terlebih dahulu
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initDropdowns, 100);
                initSidebarToggle();
            });
        } else {
            setTimeout(initDropdowns, 100);
            initSidebarToggle();
        }
        
        // Juga coba lagi setelah window load untuk memastikan Bootstrap ter-load
        window.addEventListener('load', function() {
            setTimeout(initDropdowns, 200);
        });
    })();
</script>
