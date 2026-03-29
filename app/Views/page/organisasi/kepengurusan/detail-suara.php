<?= $this->extend('components/layouts/app') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="<?= base_url('dist/landing/assets/css/styles4.css') ?>">
<link rel="stylesheet" href="<?= base_url('dist/landing/assets/css/styles5.css') ?>">
<link rel="stylesheet" href="<?= base_url('dist/landing/assets/lib/owlcarousel/assets/owl.carousel.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('dist/landing/assets/lib/owlcarousel/assets/owl.theme.default.min.css') ?>">
<style>
    /* Chart Styles - sama seperti voting-detail.php */
    .chart-container {
        height: 400px;
        position: relative;
    }

    .title-body-chart {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1.5rem;
    }

    .chart-arrow {
        position: relative;
    }

    .card-chart {
        border: 1px solid #e0e0e0;
        border-top: 2px solid #d0d0d0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        background: #fff;
        padding: 0;
        overflow: hidden;
    }

    .card-chart .card-body {
        padding: 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    #chart-voting {
        max-height: 400px;
    }

    /* Card wrapper untuk statistik */
    .card.mb-3 {
        border: 1px solid #e0e0e0;
        border-top: 2px solid #d0d0d0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        background: #fff;
        overflow: hidden;
    }

    .card.mb-3 .card-body {
        padding: 1.5rem;
    }

    /* Perbaikan card chart agar lebih rapi */
    .card-chart.shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    /* Owl Carousel Navigation - Posisi lebih tinggi di mobile */
    .categories-carousel-wrapper {
        position: relative;
        padding: 65px 60px 0 60px;
        overflow: visible;
    }

    /* Badge Total Suara Styling - Perbaikan untuk tampilan yang lebih rapi */
    .categories-content .badge {
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(152, 5, 23, 0.2);
        display: block;
        width: 100%;
        padding: 0.65rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        text-align: center;
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        border-radius: 0.5rem;
        background-color: #980517 !important;
        color: #fff !important;
        font-weight: 600;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .categories-item:hover .categories-content .badge {
        background-color: #7a0412 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(152, 5, 23, 0.4);
    }

    /* Container untuk badge agar tidak terpotong */
    .categories-content .text-center.mt-auto {
        padding-top: 0.75rem;
        width: 100%;
        overflow: visible;
        flex-shrink: 0;
    }

    /* Memastikan badge tidak terpotong di card */
    .categories-content {
        overflow: visible;
        padding-bottom: 1rem;
    }

    .categories-item {
        overflow: visible;
    }

    .categories-item-inner {
        overflow: visible;
    }

    .owl-carousel .owl-nav {
        position: absolute;
        top: -65px;
        transform: translateY(0);
        width: 100%;
        left: 0;
        right: 0;
        display: flex;
        justify-content: space-between;
        padding: 0;
        pointer-events: none;
        z-index: 100;
    }

    .categories-carousel .owl-stage-outer {
        margin-top: 65px;
    }

    .owl-carousel .owl-nav button {
        background: #980517 !important;
        color: #fff !important;
        border: none;
        border-radius: 50px;
        padding: 10px 35px;
        min-width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer !important;
        box-shadow: 0 4px 12px rgba(152, 5, 23, 0.3);
        transition: all 0.3s ease;
        pointer-events: auto !important;
        line-height: 1;
        z-index: 101;
        position: relative;
    }

    .owl-carousel .owl-nav button:hover {
        background: #7a0412 !important;
        transform: scale(1.15);
        box-shadow: 0 6px 20px rgba(152, 5, 23, 0.4);
    }

    .owl-carousel .owl-nav button.owl-prev {
        left: 0 !important;
        position: absolute;
    }

    .owl-carousel .owl-nav button.owl-next {
        right: 0;
        position: absolute;
    }

    .owl-carousel .owl-dots {
        text-align: center;
        margin-top: 30px;
    }

    .owl-carousel .owl-dots button.owl-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ddd;
        margin: 0 5px;
        border: none;
        transition: all 0.3s ease;
    }

    .owl-carousel .owl-dots button.owl-dot.active {
        background: #980517;
        width: 30px;
        border-radius: 6px;
    }

    /* Desktop Responsive - Perbaikan untuk tampilan desktop */
    @media (min-width: 1200px) {
        .chart-container {
            height: 450px;
        }

        .categories-carousel-wrapper {
            padding: 65px 70px 0 70px !important;
        }

        .owl-carousel .owl-nav {
            top: -65px;
            transform: translateY(0);
        }

        .categories-carousel .owl-stage-outer {
            margin-top: 65px;
        }

        .owl-carousel .owl-nav button {
            width: 55px;
            height: 55px;
            font-size: 26px;
            padding: 10px 35px;
            border-radius: 50px;
        }

        .owl-carousel .owl-nav button.owl-prev {
            left: 0 !important;
        }

        .owl-carousel .owl-nav button.owl-next {
            right: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .title-body-chart {
            font-size: 1.5rem;
        }

        .container.pb-5 {
            max-width: 1200px;
            margin: 0 auto;
        }

        .table-responsive {
            font-size: 0.95rem;
        }

        .categories-content .badge {
            font-size: 0.9rem;
            padding: 0.7rem 0.85rem;
        }

        .categories-content .badge div:first-child {
            font-size: 0.75rem;
        }

        .categories-content .badge div:last-child {
            font-size: 1.05rem;
        }

        .categories-content {
            min-height: 240px;
        }
    }

    /* Tablet Responsive - Sudah OK, tetap dipertahankan */
    @media (min-width: 768px) and (max-width: 1199px) {
        .chart-container {
            height: 350px;
        }

        .categories-carousel-wrapper {
            padding: 60px 60px 0 60px !important;
        }

        .owl-carousel .owl-nav {
            top: -60px;
            transform: translateY(0);
        }

        .categories-carousel .owl-stage-outer {
            margin-top: 60px;
        }

        .owl-carousel .owl-nav button {
            width: 50px;
            height: 50px;
            font-size: 24px;
            padding: 10px 30px;
            border-radius: 50px;
        }

        .owl-carousel .owl-nav button.owl-prev {
            left: 0 !important;
        }

        .owl-carousel .owl-nav button.owl-next {
            right: 0;
        }

        .categories-content .badge {
            font-size: 0.875rem;
            padding: 0.65rem 0.8rem;
        }

        .categories-content .badge div:first-child {
            font-size: 0.7rem;
        }

        .categories-content .badge div:last-child {
            font-size: 1rem;
        }

        .categories-content {
            min-height: 230px;
        }
    }

    /* Mobile Responsive - Perbaikan untuk tampilan mobile */
    @media (max-width: 767px) {
        .chart-container {
            height: 300px;
        }

        .title-body-chart {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .owl-carousel .owl-nav {
            top: -55px !important;
            transform: translateY(0);
        }

        .categories-carousel .owl-stage-outer {
            margin-top: 55px;
        }

        .owl-carousel .owl-nav button {
            width: 45px;
            height: 45px;
            font-size: 18px;
            padding: 8px 25px;
            border-radius: 50px;
            box-shadow: 0 2px 8px rgba(152, 5, 23, 0.3);
            opacity: 0.95;
        }

        .owl-carousel .owl-nav button:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .owl-carousel .owl-nav button.owl-prev {
            left: 0 !important;
            position: absolute;
        }

        .owl-carousel .owl-nav button.owl-next {
            right: 0;
            position: absolute;
        }

        .categories-carousel-wrapper {
            padding: 55px 10px 0 10px !important;
        }

        .card-body {
            padding: 1rem;
        }

        .container.pb-5 {
            padding-left: 10px;
            padding-right: 10px;
        }

        .display-5 {
            font-size: 1.5rem;
        }

        .categories-item {
            margin-bottom: 1rem;
        }

        .categories-content .badge {
            font-size: 0.85rem;
            padding: 0.6rem 0.75rem;
        }

        .categories-content .badge div:first-child {
            font-size: 0.65rem;
        }

        .categories-content .badge div:last-child {
            font-size: 0.95rem;
        }

        .categories-content {
            min-height: 220px;
        }
    }

    @media (max-width: 576px) {
        .chart-container {
            height: 280px;
        }

        .owl-carousel .owl-nav {
            top: -50px !important;
            transform: translateY(0);
        }

        .categories-carousel .owl-stage-outer {
            margin-top: 50px;
        }

        .owl-carousel .owl-nav button {
            width: 42px;
            height: 42px;
            font-size: 16px;
            padding: 8px 22px;
            border-radius: 50px;
            box-shadow: 0 2px 6px rgba(152, 5, 23, 0.3);
            opacity: 0.95;
        }

        .owl-carousel .owl-nav button:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .owl-carousel .owl-nav button.owl-prev {
            left: 0 !important;
            position: absolute;
        }

        .owl-carousel .owl-nav button.owl-next {
            right: 0;
            position: absolute;
        }

        .categories-carousel-wrapper {
            padding: 50px 8px 0 8px !important;
        }

        .categories-img {
            height: 200px !important;
        }

        .card-body {
            padding: 0.75rem;
        }

        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-success {
            width: 100%;
            margin-bottom: 1rem;
        }

        .display-5 {
            font-size: 1.25rem;
        }

        .text-center.mx-auto.pb-5 {
            padding-bottom: 1.5rem !important;
        }

        .categories-content .badge {
            font-size: 0.8rem;
            padding: 0.55rem 0.7rem;
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .categories-content .badge div:first-child {
            font-size: 0.6rem;
            margin-bottom: 0.1rem;
        }

        .categories-content .badge div:last-child {
            font-size: 0.9rem;
        }

        .categories-content {
            min-height: 200px;
        }

        .categories-content .text-center.mt-auto {
            padding-top: 0.75rem;
        }
    }

    @media (max-width: 375px) {
        .chart-container {
            height: 250px;
        }

        .owl-carousel .owl-nav {
            top: -45px !important;
            transform: translateY(0);
        }

        .categories-carousel .owl-stage-outer {
            margin-top: 45px;
        }

        .owl-carousel .owl-nav button {
            width: 40px;
            height: 40px;
            font-size: 14px;
            padding: 7px 20px;
            border-radius: 50px;
            box-shadow: 0 2px 6px rgba(152, 5, 23, 0.3);
            opacity: 0.95;
        }

        .owl-carousel .owl-nav button:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .owl-carousel .owl-nav button.owl-prev {
            left: 0 !important;
            position: absolute;
        }

        .owl-carousel .owl-nav button.owl-next {
            right: 0;
            position: absolute;
        }

        .categories-carousel-wrapper {
            padding: 45px 5px 0 5px !important;
        }

        .categories-img {
            height: 180px !important;
        }

        .card-body {
            padding: 0.5rem;
        }

        .display-5 {
            font-size: 1.1rem;
        }

        .title-body-chart {
            font-size: 1rem;
        }

        .categories-content .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.65rem;
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.4;
        }

        .categories-content .badge div:first-child {
            font-size: 0.55rem;
            margin-bottom: 0.1rem;
        }

        .categories-content .badge div:last-child {
            font-size: 0.85rem;
        }

        .categories-content {
            min-height: 190px;
        }

        .categories-content .text-center.mt-auto {
            padding-top: 0.5rem;
        }
    }

    /* Styling untuk tabel dengan border tebal */
    #datatable {
        border: 2px solid #dee2e6 !important;
    }

    #datatable th,
    #datatable td {
        border: 1px solid #dee2e6 !important;
    }

    #datatable thead th {
        border-bottom: 2px solid #dee2e6 !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* Styling untuk header dengan hamburger dan icons */
    header.mb-3 {
        padding: 0.75rem 0;
    }

    .burger-btn {
        color: #4e73df !important;
        text-decoration: none;
        padding: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .burger-btn:hover {
        color: #2e59d9 !important;
    }

    .burger-btn i {
        font-size: 1.5rem;
    }

    .modern-search {
        position: relative;
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        width: 320px;
    }

    .modern-search:focus-within {
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .modern-search .search-icon {
        color: #9ca3af;
        margin-right: 0.5rem;
        font-size: 1rem;
    }

    .modern-search input {
        border: none;
        outline: none;
        background: transparent;
        flex: 1;
        padding: 0;
        font-size: 0.875rem;
        color: #111827;
    }

    .modern-search input::placeholder {
        color: #9ca3af;
    }

    .modern-search .search-shortcut {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        margin-left: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .modern-search .search-shortcut:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }

    .icon-btn-wrapper {
        position: relative;
    }

    .icon-btn {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: #f3f4f6;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }

    .icon-btn:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .icon-btn i {
        font-size: 1.125rem;
    }

    .notification-badge {
        position: absolute;
        top: -0.125rem;
        right: -0.125rem;
        width: 0.75rem;
        height: 0.75rem;
        background: #f97316;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    /* Chat badge untuk unread pesan */
    .chat-badge {
        position: absolute;
        top: -0.25rem;
        right: -0.25rem;
        min-width: 1.25rem;
        height: 1.25rem;
        background: #dc3545;
        border: 2px solid #fff;
        border-radius: 0.625rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6875rem;
        font-weight: 600;
        color: #fff;
        padding: 0 0.375rem;
    }

    .chat-badge.hidden {
        display: none;
    }

    .user-dropdown-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        background: transparent;
        border: none;
        cursor: pointer;
        text-decoration: none;
        color: #111827;
        transition: all 0.2s;
        border-radius: 0.5rem;
    }

    .user-dropdown-btn:hover {
        background: #f3f4f6;
    }

    .user-avatar {
        width: 2.5rem;
        /* Ukuran sedang seperti search bar */
        height: 2.5rem;
        /* Ukuran sedang seperti search bar */
        min-width: 2.5rem;
        min-height: 2.5rem;
        border-radius: 50%;
        background: #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        overflow: hidden;
        flex-shrink: 0;
        position: relative;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        position: absolute;
        top: 0;
        left: 0;
    }

    .user-avatar i {
        font-size: 1.5rem;
        /* Ukuran sedang */
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-name {
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
    }

    .user-chevron {
        color: #9ca3af;
        font-size: 0.75rem;
    }

    header .d-flex {
        width: 100%;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-left: auto;
    }

    .settings-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #fff;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .settings-section h4 {
        margin-bottom: 1.5rem;
        color: #1f2937;
        font-weight: 600;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.75rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #374151;
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }

    .form-group input:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    .btn-update {
        background-color: #dc2626;
        color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-update:hover {
        background-color: #b91c1c;
    }

    .form-group small {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.75rem;
    }

    .password-strength.weak {
        color: #dc2626;
    }

    .password-strength.medium {
        color: #f59e0b;
    }

    .password-strength.strong {
        color: #10b981;
    }

    .info-box {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 1rem;
        margin-top: 1rem;
        border-radius: 0.25rem;
    }

    .info-box p {
        margin: 0;
        color: #1e40af;
        font-size: 0.875rem;
    }

    .warning-box {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 1rem;
        margin-top: 1rem;
        border-radius: 0.25rem;
    }

    .warning-box p {
        margin: 0;
        color: #92400e;
        font-size: 0.875rem;
    }

    /* Responsive untuk tablet */
    @media (max-width: 991.98px) {
        .modern-search {
            width: 280px;
        }

        .icon-btn {
            width: 2.5rem;
            height: 2.5rem;
        }

        .icon-btn i {
            font-size: 1.125rem;
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
        }

        .user-avatar i {
            font-size: 1.5rem;
        }

        .user-name {
            font-size: 0.875rem;
        }
    }

    /* Responsive untuk mobile */
    @media (max-width: 767.98px) {
        header .d-flex {
            gap: 0.75rem !important;
            flex-wrap: nowrap;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
            min-width: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .modern-search {
            flex: 1;
            min-width: 0;
            width: auto;
            max-width: none;
        }

        .icon-btn {
            width: 2.5rem;
            height: 2.5rem;
        }

        .icon-btn i {
            font-size: 1.125rem;
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
        }

        .user-avatar i {
            font-size: 1.5rem;
        }

        .user-name {
            font-size: 0.8125rem;
        }

        .settings-section {
            padding: 1rem;
        }
    }

    @media (max-width: 575.98px) {
        header .d-flex {
            gap: 0.5rem !important;
        }

        .header-left {
            gap: 0.5rem;
        }

        .header-right {
            gap: 0.375rem;
        }

        .burger-btn i {
            font-size: 1.3rem;
        }

        .modern-search {
            flex: 1;
            min-width: 0;
        }

        .modern-search .search-shortcut {
            display: none;
        }

        .icon-btn {
            width: 2.25rem;
            height: 2.25rem;
        }

        .icon-btn i {
            font-size: 1rem;
        }

        .user-avatar {
            width: 2.25rem;
            height: 2.25rem;
        }

        .user-avatar i {
            font-size: 1.25rem;
        }

        .user-name {
            font-size: 0.75rem;
        }
    }
</style>

<header class="mb-3">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <!-- Bagian Kiri: Hamburger + Search -->
        <div class="header-left">
            <!-- Hamburger Menu - Hanya untuk Mobile/Tablet -->
            <a href="#" class="burger-btn d-block d-xl-none" id="sidebarToggle">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <!-- Search Bar Modern - Pojok Kiri, Ukuran Sedang -->
            <div class="d-none d-md-inline-block">
                <div class="modern-search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search" aria-label="Search" id="modernSearchInput" />
                    <button type="button" class="search-shortcut" title="Press ⌘K to search">⌘K</button>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan: Chat, Notification, User -->
        <div class="header-right">
            <!-- Chat Dropdown -->
            <div class="dropdown icon-btn-wrapper">
                <a class="icon-btn" id="navbarDropdownChat" href="<?= url_to('pesan.index') ?>" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                    <i class="fas fa-comment-dots"></i>
                    <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                        <span class="chat-badge"><?= $unreadCount > 99 ? '99+' : $unreadCount ?></span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end bg-brown" aria-labelledby="navbarDropdownChat">
                    <li>
                        <a class="dropdown-item" href="<?= url_to('pesan.index') ?>">
                            <i class="bi bi-chat-dots me-2"></i>Aspirasi Mahasiswa
                            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                                <span class="badge bg-danger float-end"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Notification Dropdown -->
            <?= view_cell('App\Cells\NotificationCell::render') ?>

            <!-- User Dropdown -->
            <div class="dropdown">
                <a class="user-dropdown-btn" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false" data-bs-auto-close="true">
                    <div class="user-avatar">
                        <?php
                        $profilePhoto = session()->get('profile_photo');
                        if (!empty($profilePhoto) && file_exists(FCPATH . 'uploads/profile/' . $profilePhoto)): ?>
                            <img src="<?= base_url('uploads/profile/' . $profilePhoto) ?>" alt="Profile">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <span class="user-name d-none d-sm-inline"><?= session()->get('name') ?? 'User' ?></span>
                    <i class="fas fa-chevron-down user-chevron"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end bg-brown" aria-labelledby="navbarDropdownUser">
                    <li>
                        <a class="dropdown-item" href="<?= url_to('profile.edit') ?>">
                            <i class="fas fa-user-circle fa-lg me-2"></i>Profile
                        </a>
                        <hr class="dropdown-divider light" />
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url_to('settings.index') ?>">
                            <i class="fas fa-cog fa-lg me-2"></i>Pengaturan
                        </a>
                        <hr class="dropdown-divider light" />
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= url_to('logout') ?>">
                            <i class="fas fa-sign-out-alt fa-lg me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><?= $title ?></h3>
                <p class="text-subtitle text-muted">Periode: <?= $pemilihan['periode'] ?? '-' ?></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
        <?php include_once(APPPATH . 'Views/components/flash.php'); ?>

        <!-- Export Button -->
        <div class="mb-3">
            <a href="<?= url_to('organisasi.kepengurusan.exportExcel', $kepengurusan['id']) ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Export ke Excel
            </a>
        </div>

        <!-- Statistik Voting Section -->
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="card-title title-body-chart">Statistik Voting <?= $kepengurusan['organisasi_name'] ?></div>
                <div class="row g-3">
                    <!-- Bagian Chart -->
                    <div class="col-xl-5 col-lg-6 col-md-12 mb-4 mb-lg-0 chart-arrow">
                        <div id="chart-container">
                            <div class="chart" id="chart1">
                                <div class="card card-chart h-100 shadow-sm">
                                    <div class="card-body d-flex flex-column">
                                        <div class="chart-container flex-grow-1">
                                            <canvas id="chart-voting"
                                                style="width:100%; height:100%; min-height:250px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Data Voting -->
                    <div class="col-xl-7 col-lg-6 col-md-12">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <!-- Header dan Pencarian -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="fw-bold m-0" style="color: #980517;">Data Voting</h4>
                                </div>

                                <!-- Table Responsive -->
                                <div class="table-responsive flex-grow-1">
                                    <table class="table table-hover table-bordered mb-0" id="datatable" width="100%"
                                        cellspacing="0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-nowrap">No</th>
                                                <th class="text-nowrap">Nama</th>
                                                <th class="text-nowrap">NIM</th>
                                                <th class="text-nowrap">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php
                                            // Helper untuk masking NIM dan email
                                            if (!function_exists('maskNim')) {
                                                function maskNim($nim)
                                                {
                                                    $nim = (string) $nim;
                                                    $length = strlen($nim);
                                                    if ($length <= 4) {
                                                        return str_repeat('*', $length);
                                                    }
                                                    return str_repeat('*', $length - 4) . substr($nim, -4);
                                                }
                                            }

                                            if (!function_exists('maskEmailKeepDomain')) {
                                                function maskEmailKeepDomain($email)
                                                {
                                                    $email = (string) $email;
                                                    $parts = explode('@', $email, 2);
                                                    $local = $parts[0] ?? '';
                                                    // Normalisasi domain ke krw.horizon.ac.id jika termasuk domain kampus
                                                    $domain = 'krw.horizon.ac.id';
                                                    if (isset($parts[1]) && !preg_match('/krw\.horizon(\.ac\.id)?/i', $parts[1])) {
                                                        // Jika domain bukan domain kampus, tetap tampilkan domain aslinya
                                                        $domain = $parts[1];
                                                    }
                                                    $maskedLocal = str_repeat('*', max(3, strlen($local)));
                                                    return $maskedLocal . '@' . $domain;
                                                }
                                            }
                                            ?>
                                            <?php foreach ($votingData as $s): ?>
                                                <tr>
                                                    <td style="color: #6c757d;"><?= $no ?></td>
                                                    <td style="color: #6c757d;"><?= $s['name'] ?></td>
                                                    <td style="color: #6c757d;"><?= esc($s['nim']) ?></td>
                                                    <td style="color: #6c757d;"><?= esc($s['email']) ?></td>
                                                </tr>
                                                <?php $no++; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Carousel untuk Saingan -->
        <div class="container-fluid categories pb-5" style="padding-left: 0; padding-right: 0;">
            <div class="container pb-5">
                <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                    <h1 class="display-5 text-capitalize mb-3">Kandidat <span
                            style="color: #980517;"><?= $kepengurusan['organisasi_name'] ?></span></h1>
                </div>

                <div class="categories-carousel-wrapper">
                    <div class="categories-carousel owl-carousel wow fadeInUp" data-wow-delay="0.1s">
                        <?php foreach ($allCandidates as $c): ?>
                            <div class="categories-item p-4">
                                <div class="categories-item-inner d-flex flex-column h-100">
                                    <div class="categories-img rounded-top"
                                        style="background-color: #980517; height: 250px; overflow: hidden;">
                                        <img src="<?= base_url('uploads/' . esc($c['gambar_1'] ?? '')) ?>"
                                            class="img-fluid w-100 h-100 object-fit-cover rounded-top" alt="">
                                    </div>
                                    <div class="categories-content rounded-bottom p-4 d-flex flex-column flex-grow-1">
                                        <a href="#" class="h4 d-block mb-3 text-truncate">
                                            <?= esc($c['anggota_1_name'] ?? '') ?>    <?= !empty($c['anggota_2_name']) ? ' - ' . esc($c['anggota_2_name']) : '' ?>
                                        </a>
                                        <div class="categories-review mb-4 flex-grow-1">
                                            <div class="card-description" style="
                                            display: -webkit-box;
                                            -webkit-line-clamp: 4;
                                            -webkit-box-orient: vertical;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                            min-height: 96px;">
                                                <?= esc(strip_tags($c['description'] ?? '')) ?>
                                            </div>
                                        </div>
                                        <?php
                                        $candidateName = $c['anggota_1_name'];
                                        if (!empty($c['anggota_2_name'])) {
                                            $candidateName .= ' & ' . $c['anggota_2_name'];
                                        }
                                        $suaraCount = isset($totalSuarasWithPercent[$candidateName]) ? $totalSuarasWithPercent[$candidateName]['count'] : 0;
                                        $suaraPercent = isset($totalSuarasWithPercent[$candidateName]) ? $totalSuarasWithPercent[$candidateName]['percent'] : 0;
                                        ?>
                                        <div class="text-center mt-auto">
                                            <div class="badge">
                                                <div style="font-size: 0.8rem; opacity: 0.95; margin-bottom: 0.15rem;">Total
                                                    Suara</div>
                                                <div style="font-size: 1rem; font-weight: 700;">
                                                    <?= $suaraCount ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- jQuery must be loaded first -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url('dist/landing/assets/lib/wow/wow.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/easing/easing.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/waypoints/waypoints.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/counterup/counterup.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/lib/owlcarousel/owl.carousel.min.js') ?>"></script>
<script src="<?= base_url('dist/landing/assets/js/main.js') ?>"></script>
<script>
    // Wait for DOM and all scripts to load
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize WOW.js
        if (typeof WOW !== 'undefined') {
            new WOW().init();
        }

        // Initialize DataTable
        if (typeof $ !== 'undefined' && $('#datatable').length) {
            $('#datatable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _TOTAL_ total entri)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        }
    });

    // Chart.js Pie Chart - Initialize after Chart.js is loaded
    window.addEventListener('load', function () {
        // Wait a bit to ensure all scripts are loaded
        setTimeout(function () {
            var chartCanvas = document.getElementById('chart-voting');
            if (!chartCanvas) {
                console.error('Chart canvas not found');
                return;
            }

            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                return;
            }

            var ctxY = chartCanvas.getContext('2d');
            var labels = [];
            var datas = [];

            <?php if (!empty($totalSuarasWithPercent)): ?>
                <?php foreach ($totalSuarasWithPercent as $name => $data): ?>
                    // Bersihkan nama kandidat dengan menghapus bagian setelah '-' jika ada
                    var cleanLabel = '<?= addslashes($name) ?>'.split('-')[0].trim();
                    // Hapus teks 'Total suara:' jika ada
                    cleanLabel = cleanLabel.replace('Total suara:', '').trim();
                    labels.push(cleanLabel);
                    datas.push(<?= $data['count'] ?>);
                <?php endforeach; ?>
            <?php else: ?>
                // No data available
                labels.push('Tidak ada data');
                datas.push(0);
            <?php endif; ?>

            // Define colors for the pie chart
            var backgroundColors = [
                '#980517', // Maroon (original color)
                '#4A6FDC', // Blue
                '#2E8B57', // Sea Green
                '#FF8C00', // Dark Orange
                '#9932CC', // Dark Orchid
                '#FF6347', // Tomato
                '#20B2AA', // Light Sea Green
                '#FFD700', // Gold
                '#9370DB', // Medium Purple
                '#3CB371', // Medium Sea Green
            ];

            // Assign colors to each segment
            var pieColors = [];
            for (var i = 0; i < labels.length; i++) {
                pieColors.push(backgroundColors[i % backgroundColors.length]);
            }

            var chartSuara4 = new Chart(ctxY, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: datas,
                        backgroundColor: pieColors,
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: window.innerWidth < 576 ? 'bottom' : 'right',
                            labels: {
                                font: {
                                    size: window.innerWidth < 576 ? 10 : 12,
                                    weight: 'bold'
                                },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                generateLabels: function (chart) {
                                    var data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map(function (label, i) {
                                            return {
                                                text: label + '\nTotal suara: ' + data.datasets[0].data[i],
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                hidden: false,
                                                lineWidth: 1,
                                                strokeStyle: '#ffffff',
                                                pointStyle: 'circle',
                                                font: {
                                                    size: window.innerWidth < 576 ? 10 : 12,
                                                    weight: 'bold'
                                                }
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: {
                                size: window.innerWidth < 768 ? 10 : 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: window.innerWidth < 768 ? 9 : 11
                            },
                            callbacks: {
                                label: function (context) {
                                    return 'Total suara: ' + context.raw;
                                },
                                afterLabel: function (context) {
                                    return '';
                                }
                            }
                        }
                    },
                    cutout: window.innerWidth < 576 ? '50%' : '30%',
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Handle window resize
            function handleResize() {
                const isMobile = window.innerWidth < 576;
                const isTablet = window.innerWidth < 768;

                if (chartSuara4) {
                    chartSuara4.options.plugins.legend.position = isMobile ? 'bottom' : 'right';
                    chartSuara4.options.plugins.legend.labels.font.size = isMobile ? 10 : (isTablet ? 11 : 12);
                    chartSuara4.options.plugins.tooltip.titleFont.size = isTablet ? 10 : 12;
                    chartSuara4.options.plugins.tooltip.bodyFont.size = isTablet ? 9 : 11;
                    chartSuara4.options.cutout = isMobile ? '50%' : '30%';
                    chartSuara4.update();
                }
            }

            window.addEventListener('resize', handleResize);
            window.addEventListener('orientationchange', handleResize);
        }, 500); // Wait 500ms for all scripts to load
    });

    // Initialize Owl Carousel for categories - Sama seperti voting.php
    window.addEventListener('load', function () {
        setTimeout(function () {
            if (typeof $ !== 'undefined' && typeof $.fn.owlCarousel !== 'undefined') {
                $('.categories-carousel').each(function () {
                    var $carousel = $(this);
                    if (!$carousel.hasClass('owl-loaded')) {
                        $carousel.owlCarousel({
                            autoplay: true,
                            autoplayTimeout: 5000, // 5 detik
                            autoplayHoverPause: true,
                            smartSpeed: 1000,
                            dots: false,
                            loop: true, // Selalu loop agar kembali ke awal setelah habis
                            margin: 25,
                            nav: true,
                            navText: [
                                '<i class="bi bi-chevron-left"></i>',
                                '<i class="bi bi-chevron-right"></i>'
                            ],
                            responsiveClass: true,
                            responsive: {
                                0: {
                                    items: 1,
                                    nav: true,
                                    dots: false,
                                    margin: 15
                                },
                                576: {
                                    items: 1,
                                    nav: true,
                                    dots: false,
                                    margin: 20
                                },
                                768: {
                                    items: 1,
                                    nav: true,
                                    dots: false,
                                    margin: 25
                                },
                                992: {
                                    items: 2,
                                    nav: true,
                                    dots: false,
                                    margin: 25
                                },
                                1200: {
                                    items: 3,
                                    nav: true,
                                    dots: false,
                                    margin: 25
                                }
                            }
                        });

                        // Memastikan tombol panah bisa diklik dan berfungsi
                        $carousel.on('initialized.owl.carousel', function () {
                            var $navButtons = $carousel.find('.owl-nav button');
                            $navButtons.css({
                                'pointer-events': 'auto',
                                'cursor': 'pointer',
                                'z-index': '101'
                            });

                            // Event handler untuk tombol panah kiri
                            $carousel.find('.owl-nav .owl-prev').off('click').on('click', function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                $carousel.trigger('prev.owl.carousel');
                                // Pause autoplay saat user klik manual
                                $carousel.trigger('stop.owl.autoplay');
                                setTimeout(function () {
                                    $carousel.trigger('play.owl.autoplay');
                                }, 5000); // Resume autoplay setelah 5 detik
                                return false;
                            });

                            // Event handler untuk tombol panah kanan
                            $carousel.find('.owl-nav .owl-next').off('click').on('click', function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                $carousel.trigger('next.owl.carousel');
                                // Pause autoplay saat user klik manual
                                $carousel.trigger('stop.owl.autoplay');
                                setTimeout(function () {
                                    $carousel.trigger('play.owl.autoplay');
                                }, 5000); // Resume autoplay setelah 5 detik
                                return false;
                            });
                        });

                        // Fallback jika event initialized tidak terpanggil
                        setTimeout(function () {
                            var $navButtons = $carousel.find('.owl-nav button');
                            if ($navButtons.length > 0) {
                                $navButtons.css({
                                    'pointer-events': 'auto',
                                    'cursor': 'pointer',
                                    'z-index': '101'
                                });

                                $carousel.find('.owl-nav .owl-prev').off('click').on('click', function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    $carousel.trigger('prev.owl.carousel');
                                    $carousel.trigger('stop.owl.autoplay');
                                    setTimeout(function () {
                                        $carousel.trigger('play.owl.autoplay');
                                    }, 5000);
                                    return false;
                                });

                                $carousel.find('.owl-nav .owl-next').off('click').on('click', function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    $carousel.trigger('next.owl.carousel');
                                    $carousel.trigger('stop.owl.autoplay');
                                    setTimeout(function () {
                                        $carousel.trigger('play.owl.autoplay');
                                    }, 5000);
                                    return false;
                                });
                            }
                        }, 1000);
                    }
                });
            }
        }, 600); // Wait a bit longer for all scripts
    });
</script>
<?= $this->endSection() ?>