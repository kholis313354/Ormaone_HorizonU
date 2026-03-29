<?= $this->extend('components/layouts/app') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Styling untuk header dengan hamburger dan icons */
    header.mb-3 {
        padding: 0.75rem 0;
    }

    /* Styling for inline images in fields */
    .field-inline-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 10px;
        display: block;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
        height: 2.5rem;
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

    /* Google Forms-like styling */
    .form-builder-container {
        max-width: 100%;
        margin: 0 auto;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .form-builder-header {
        background: #fff;
        border-bottom: 1px solid #dadce0;
        padding: 16px 24px;
    }

    .form-builder-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 500;
        color: #202124;
    }

    .form-builder-content {
        background: #fff;
        max-width: 900px;
        margin: 0 auto;
        padding: 24px;
        min-height: calc(100vh - 200px);
    }

    .form-title-section {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid #dadce0;
    }

    .form-title-input {
        font-size: 32px;
        font-weight: 400;
        border: none;
        outline: none;
        width: 100%;
        padding: 8px 0;
        color: #202124;
        background: transparent;
    }

    .form-title-input:empty:before {
        content: attr(data-placeholder);
        color: #80868b;
    }

    .form-title-input:focus:empty:before {
        content: attr(data-placeholder);
        color: #80868b;
    }

    .form-description-input {
        font-size: 14px;
        border: none;
        outline: none;
        width: 100%;
        padding: 8px 0;
        color: #5f6368;
        background: transparent;
        margin-top: 8px;
    }

    .form-description-input:empty:before {
        content: attr(data-placeholder);
        color: #80868b;
    }

    .form-description-input:focus:empty:before {
        content: attr(data-placeholder);
        color: #80868b;
    }

    /* Formatting Toolbar */
    .formatting-toolbar {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px 0;
        margin-top: 8px;
        border-top: 1px solid #dadce0;
    }

    .formatting-toolbar.hidden {
        display: none;
    }

    .format-btn {
        background: none;
        border: none;
        padding: 6px 8px;
        cursor: pointer;
        color: #5f6368;
        border-radius: 4px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        width: 32px;
        height: 32px;
    }

    .format-btn:hover {
        background: #f1f3f4;
        color: #202124;
    }

    .format-btn.active {
        background: #e8eaed;
        color: #1a73e8;
    }

    .format-btn i {
        font-size: 16px;
    }

    .format-divider {
        width: 1px;
        height: 20px;
        background: #dadce0;
        margin: 0 4px;
    }

    .form-field-card {
        background: #fff;
        border: 1px solid #dadce0;
        border-radius: 8px;
        margin-bottom: 12px;
        padding: 16px;
        position: relative;
        transition: all 0.2s;
    }

    .form-field-card:hover {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-field-card.editing {
        border-color: #1a73e8;
        box-shadow: 0 2px 8px rgba(26, 115, 232, 0.2);
    }

    .field-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .field-label-input {
        flex: 1;
        font-size: 14px;
        font-weight: 500;
        border: none;
        outline: none;
        padding: 4px 0;
        color: #202124;
        background: transparent;
    }

    .field-label-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .field-label-container {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .field-image-btn {
        background: none;
        border: none;
        padding: 4px;
        cursor: pointer;
        color: #5f6368;
        border-radius: 4px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .field-image-btn:hover {
        background: #f1f3f4;
        color: #202124;
    }

    .field-type-select {
        border: 1px solid #dadce0;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 14px;
        background: #fff;
        cursor: pointer;
    }

    .field-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f1f3f4;
    }

    .field-action-btn {
        background: none;
        border: none;
        padding: 8px;
        cursor: pointer;
        color: #5f6368;
        border-radius: 4px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 14px;
    }

    .field-action-btn:hover {
        background: #f1f3f4;
        color: #202124;
    }

    .field-preview {
        margin-top: 12px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .field-preview input,
    .field-preview textarea,
    .field-preview select {
        width: 100%;
        padding: 8px;
        border: 1px solid #dadce0;
        border-radius: 4px;
        font-size: 14px;
    }

    .field-options {
        margin-top: 12px;
    }

    .field-option-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .field-option-input {
        flex: 1;
        padding: 6px 8px;
        border: 1px solid #dadce0;
        border-radius: 4px;
        font-size: 14px;
    }

    .required-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .toggle-switch {
        position: relative;
        width: 40px;
        height: 20px;
        background: #ccc;
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .toggle-switch.active {
        background: #34a853;
    }

    .toggle-switch::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        background: #fff;
        border-radius: 50%;
        top: 2px;
        left: 2px;
        transition: transform 0.3s;
    }

    .toggle-switch.active::after {
        transform: translateX(20px);
    }

    .floating-toolbox {
        position: fixed;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        background: #fff;
        border: 1px solid #dadce0;
        border-radius: 8px;
        padding: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .toolbox-btn {
        width: 48px;
        height: 48px;
        border: none;
        background: #f8f9fa;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #5f6368;
        transition: all 0.2s;
        position: relative;
    }

    .toolbox-btn:hover {
        background: #e8eaed;
        color: #202124;
    }

    .toolbox-btn i {
        font-size: 20px;
    }

    .toolbox-btn .tooltip {
        position: absolute;
        right: 60px;
        background: #202124;
        color: #fff;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
    }

    .toolbox-btn:hover .tooltip {
        opacity: 1;
    }

    .form-meta-section {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        margin-top: 24px;
    }

    .form-meta-row {
        display: flex;
        gap: 24px;
        margin-bottom: 16px;
    }

    .form-meta-item {
        flex: 1;
    }

    .form-meta-item label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #5f6368;
        margin-bottom: 8px;
    }

    .form-meta-item select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #dadce0;
        border-radius: 4px;
        font-size: 14px;
        background: #fff;
    }

    .drag-handle {
        cursor: move;
        color: #5f6368;
        padding: 4px;
    }

    .drag-handle:hover {
        color: #202124;
    }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #80868b;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .floating-toolbox {
            right: 12px;
            padding: 4px;
        }

        .toolbox-btn {
            width: 40px;
            height: 40px;
        }

        .form-builder-content {
            padding: 16px;
        }

        .field-actions {
            flex-wrap: wrap;
            justify-content: space-between;
            /* Distribute items better */
        }

        .required-toggle {
            margin-left: auto;
            /* Ensure it stays on the right or wraps cleanly */
            padding-left: 10px;
            border-left: 1px solid #dadce0;
            /* Add separator for clarity */
        }

        [data-bs-theme="dark"] .required-toggle {
            border-left-color: #4b4b5a;
        }
    }

    /* Dark Mode Improvements */
    [data-bs-theme="dark"] .form-builder-container {
        background: #151521;
    }

    [data-bs-theme="dark"] .form-builder-header,
    [data-bs-theme="dark"] .form-builder-content,
    [data-bs-theme="dark"] .form-field-card,
    [data-bs-theme="dark"] .floating-toolbox,
    [data-bs-theme="dark"] .modern-search {
        background: #1e1e2d;
        border-color: #4b4b5a;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .form-builder-header h4,
    [data-bs-theme="dark"] .form-title-input,
    [data-bs-theme="dark"] .field-label-input {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .form-builder-header {
        border-bottom-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .form-title-section {
        border-bottom-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .form-title-input::placeholder,
    [data-bs-theme="dark"] .form-description-input::placeholder,
    [data-bs-theme="dark"] .form-description-input {
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .formatting-toolbar {
        border-top-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .format-divider {
        background: #4b4b5a;
    }

    [data-bs-theme="dark"] .format-btn,
    [data-bs-theme="dark"] .field-image-btn,
    [data-bs-theme="dark"] .field-action-btn,
    [data-bs-theme="dark"] .drag-handle,
    [data-bs-theme="dark"] .empty-state {
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .format-btn:hover,
    [data-bs-theme="dark"] .field-image-btn:hover,
    [data-bs-theme="dark"] .field-action-btn:hover,
    [data-bs-theme="dark"] .drag-handle:hover {
        background: #2b2b40;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .format-btn.active {
        background: #36364e;
        color: #6993ff;
    }

    [data-bs-theme="dark"] .toolbox-btn {
        background: #2b2b40;
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .toolbox-btn:hover {
        background: #36364e;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .form-meta-section,
    [data-bs-theme="dark"] .field-preview {
        background: #2b2b40;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .field-type-select,
    [data-bs-theme="dark"] .field-preview input,
    [data-bs-theme="dark"] .field-preview textarea,
    [data-bs-theme="dark"] .field-preview select,
    [data-bs-theme="dark"] .field-option-input,
    [data-bs-theme="dark"] .form-meta-item select {
        background: #1e1e2d;
        border-color: #4b4b5a;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .field-actions {
        border-top-color: #4b4b5a;
    }

    /* Header components dark mode */
    [data-bs-theme="dark"] .modern-search input {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .modern-search .search-shortcut {
        background: #2b2b40;
        border-color: #4b4b5a;
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .icon-btn {
        background: #1e1e2d;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .icon-btn:hover {
        background: #2b2b40;
    }

    [data-bs-theme="dark"] .user-name,
    [data-bs-theme="dark"] .user-chevron {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .user-dropdown-btn:hover {
        background: #1e1e2d;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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
                    <input type="text" placeholder="Search..." aria-label="Search" id="modernSearchInput" />
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
                        <a class="dropdown-item" href="#!">
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

<div class="form-builder-container">
    <div class="form-builder-header">
        <h4>Questions</h4>
    </div>

    <div class="form-builder-content">
        <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
        <?php include_once(APPPATH . 'Views/components/flash.php'); ?>

        <form id="formBuilderForm" action="<?= url_to('form.store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Header Image Section -->
            <div class="form-field-card"
                style="margin-bottom: 24px; text-align: center; border-top: 5px solid #4e73df;">
                <div style="position: relative; padding: 10px;">
                    <img id="headerImagePreview" src="<?= base_url('images/Horizon_University_Indonesia_Logo.png') ?>"
                        style="max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 4px;"
                        alt="Header Image">

                    <div style="margin-top: 15px;">
                        <input type="file" name="header_image" id="header_image" class="d-none" style="display: none;"
                            accept="image/*" onchange="previewHeaderImage(this)">
                        <label for="header_image" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-image me-2"></i> Ganti Header Image
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Title & Description -->
            <div class="form-title-section">
                <div contenteditable="true" id="formTitle" class="form-title-input" data-placeholder="Untitled form"
                    style="min-height: 40px;" onfocus="handleTitleFocus()" onblur="handleTitleBlur()"
                    oninput="updateFormTitle()">Untitled form</div>
                <input type="hidden" name="title" id="formTitleHidden" value="Untitled form" required>

                <div class="formatting-toolbar hidden" id="titleFormattingToolbar"
                    onmousedown="event.preventDefault(); return false;">
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formTitle', 'bold'); return false;"
                        onclick="event.preventDefault(); return false;" title="Bold">
                        <i class="bi bi-type-bold"></i>
                    </button>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formTitle', 'italic'); return false;"
                        onclick="event.preventDefault(); return false;" title="Italic">
                        <i class="bi bi-type-italic"></i>
                    </button>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formTitle', 'underline'); return false;"
                        onclick="event.preventDefault(); return false;" title="Underline">
                        <i class="bi bi-type-underline"></i>
                    </button>
                    <div class="format-divider"></div>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formTitle', 'insertLink'); return false;"
                        onclick="event.preventDefault(); return false;" title="Insert link">
                        <i class="bi bi-link-45deg"></i>
                    </button>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formTitle', 'strikeThrough'); return false;"
                        onclick="event.preventDefault(); return false;" title="Strikethrough">
                        <i class="bi bi-type-strikethrough"></i>
                    </button>
                </div>

                <div contenteditable="true" id="formDescription" class="form-description-input"
                    data-placeholder="Form description" style="min-height: 24px; margin-top: 8px;"
                    onfocus="handleDescriptionFocus()" onblur="handleDescriptionBlur()"
                    oninput="updateFormDescription()">Form description</div>
                <input type="hidden" name="description" id="formDescriptionHidden" value="Form description">

                <div class="formatting-toolbar hidden" id="descriptionFormattingToolbar"
                    onmousedown="event.preventDefault(); return false;">
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formDescription', 'bold'); return false;"
                        onclick="event.preventDefault(); return false;" title="Bold">
                        <i class="bi bi-type-bold"></i>
                    </button>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formDescription', 'italic'); return false;"
                        onclick="event.preventDefault(); return false;" title="Italic">
                        <i class="bi bi-type-italic"></i>
                    </button>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formDescription', 'underline'); return false;"
                        onclick="event.preventDefault(); return false;" title="Underline">
                        <i class="bi bi-type-underline"></i>
                    </button>
                    <div class="format-divider"></div>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formDescription', 'insertUnorderedList'); return false;"
                        onclick="event.preventDefault(); return false;" title="Bullet list">
                        <i class="bi bi-list-ul"></i>
                    </button>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formDescription', 'insertOrderedList'); return false;"
                        onclick="event.preventDefault(); return false;" title="Numbered list">
                        <i class="bi bi-list-ol"></i>
                    </button>
                    <div class="format-divider"></div>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formDescription', 'insertLink'); return false;"
                        onclick="event.preventDefault(); return false;" title="Insert link">
                        <i class="bi bi-link-45deg"></i>
                    </button>
                    <button type="button" class="format-btn"
                        onmousedown="event.preventDefault(); event.stopPropagation(); formatText('formDescription', 'removeFormat'); return false;"
                        onclick="event.preventDefault(); return false;" title="Clear formatting">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            </div>

            <!-- Dynamic Fields Container -->
            <div id="fieldsContainer">
                <!-- Fields will be added here dynamically -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="empty-state" style="display: none;">
                <i class="bi bi-file-earmark-text"></i>
                <p>Click the + button to add your first question</p>
            </div>

            <!-- Form Meta (Type, Organization, Status) -->
            <div class="form-meta-section">
                <div class="form-meta-row">
                    <div class="form-meta-item">
                        <label for="form_type">Form Type</label>
                        <select name="form_type" id="form_type" required>
                            <option value="">--Pilih Tipe Form--</option>
                            <option value="absensi">Absensi</option>
                            <option value="quiz">Quiz</option>
                            <option value="survey">Survey</option>
                            <option value="pendaftaran">Pendaftaran</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>
                    <div class="form-meta-item">
                        <label for="organisasi_id">Organisasi</label>
                        <select name="organisasi_id" id="organisasi_id">
                            <option value="">--Pilih Organisasi (Opsional)--</option>
                            <?php if (isset($organisasis)): ?>
                                <?php foreach ($organisasis as $organisasi): ?>
                                    <option value="<?= $organisasi['id'] ?>"><?= esc($organisasi['name']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-meta-item">
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Hidden input untuk menyimpan fields data -->
            <input type="hidden" name="fields_data" id="fieldsData">

            <div style="margin-top: 24px; text-align: right;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <i class="bi bi-save"></i> Simpan Form
                </button>
                <a href="<?= url_to('form.index') ?>" class="btn btn-secondary"
                    style="padding: 10px 24px; margin-left: 8px;">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Floating Toolbox -->
    <div class="floating-toolbox">
        <button type="button" class="toolbox-btn" onclick="addField('text')" title="Add question">
            <i class="bi bi-plus-lg"></i>
            <span class="tooltip">Add question</span>
        </button>
        <button type="button" class="toolbox-btn" onclick="addField('textarea')" title="Add paragraph">
            <i class="bi bi-text-paragraph"></i>
            <span class="tooltip">Add paragraph</span>
        </button>
        <button type="button" class="toolbox-btn" onclick="addField('select')" title="Add dropdown">
            <i class="bi bi-list-ul"></i>
            <span class="tooltip">Add dropdown</span>
        </button>
        <button type="button" class="toolbox-btn" onclick="addField('radio')" title="Add multiple choice">
            <i class="bi bi-circle"></i>
            <span class="tooltip">Add multiple choice</span>
        </button>
        <button type="button" class="toolbox-btn" onclick="addField('checkbox')" title="Add checkboxes">
            <i class="bi bi-check-square"></i>
            <span class="tooltip">Add checkboxes</span>
        </button>
        <button type="button" class="toolbox-btn" onclick="addField('file')" title="Add file upload">
            <i class="bi bi-upload"></i>
            <span class="tooltip">Add file upload</span>
        </button>
        <div style="width: 1px; height: 1px; background: #dadce0; margin: 4px 0;"></div>
        <button type="button" class="toolbox-btn" onclick="addTitleAndDescription()" title="Add title and description">
            <i class="bi bi-type-h1"></i>
            <span class="tooltip">Add title and description</span>
        </button>
        <button type="button" class="toolbox-btn" onclick="addFormImage()" title="Add image">
            <i class="bi bi-image"></i>
            <span class="tooltip">Add image</span>
        </button>
        <button type="button" class="toolbox-btn" onclick="addFormVideo()" title="Add video">
            <i class="bi bi-play-circle"></i>
            <span class="tooltip">Add video</span>
        </button>
        <button type="button" class="toolbox-btn" onclick="addSection()" title="Add section">
            <i class="bi bi-layout-split"></i>
            <span class="tooltip">Add section</span>
        </button>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
        // Ensure functions from script.php are available and initialized
        if (typeof updateEmptyState === 'function') {
            updateEmptyState();
        }

        // Set initial placeholder style for contenteditable elements
        const titleElement = document.getElementById('formTitle');
        const descElement = document.getElementById('formDescription');

        if (titleElement) {
            if (titleElement.textContent.trim() === 'Untitled form') {
                titleElement.style.color = '#80868b';
            } else {
                titleElement.style.color = '#202124';
            }
        }

        if (descElement) {
            if (descElement.textContent.trim() === 'Form description') {
                descElement.style.color = '#80868b';
            } else {
                descElement.style.color = '#5f6368';
            }
        }
    });
</script>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<?= view('page/form/script') ?>
<?= $this->endSection() ?>