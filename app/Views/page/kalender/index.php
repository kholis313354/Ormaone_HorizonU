<?= $this->extend('components/layouts/app') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css' rel='stylesheet' />
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
            min-width: 2.5rem;
            min-height: 2.5rem;
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
            min-width: 2.5rem;
            min-height: 2.5rem;
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
            min-width: 2.25rem;
            min-height: 2.25rem;
        }

        .user-avatar i {
            font-size: 1.25rem;
        }

        .user-name {
            font-size: 0.75rem;
        }
    }

    .calendar-container {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .calendar-nav {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        flex: 1;
    }

    .calendar-controls {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .calendar-nav-btn {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .calendar-nav-btn:hover {
        background: #e5e7eb;
    }

    .calendar-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .calendar-view-toggle {
        display: flex;
        gap: 5px;
        background: #f3f4f6;
        border-radius: 6px;
        padding: 4px;
    }

    .calendar-view-btn {
        padding: 8px 16px;
        border: none;
        background: transparent;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        transition: all 0.2s;
    }

    .calendar-view-btn.active {
        background: #fff;
        color: #111827;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .add-event-btn {
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 20px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .add-event-btn:hover {
        background: #0056b3;
    }

    #calendar {
        margin-top: 20px;
    }

    .fc-event {
        cursor: pointer;
        font-weight: 500;
        border-radius: 4px;
        padding: 2px 4px;
        margin: 1px 0;
        border: none !important;
        min-height: 20px;
        display: block !important;
    }

    .fc-event-main {
        color: #ffffff !important;
        padding: 2px 4px;
    }

    .fc-event-title {
        color: #ffffff !important;
        font-weight: 500;
        font-size: 0.875rem;
        line-height: 1.4;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Memastikan event title terlihat di semua view */
    .fc-daygrid-event .fc-event-title,
    .fc-timegrid-event .fc-event-title,
    .fc-list-event .fc-event-title {
        color: #ffffff !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* Memastikan event ditampilkan dengan warna yang benar */
    .fc-daygrid-event,
    .fc-timegrid-event {
        border-radius: 4px;
    }

    /* Memastikan event terlihat dengan warna yang benar - override semua style */
    .fc-event.fc-event-start,
    .fc-event.fc-event-end,
    .fc-event.fc-daygrid-event,
    .fc-event.fc-timegrid-event {
        border: none !important;
    }

    /* Warna untuk event primary (biru) - dengan selector yang lebih spesifik */
    .fc-event[data-color="primary"],
    .fc-event[style*="rgb(0, 123, 255)"],
    .fc-event[style*="#007bff"],
    .fc-daygrid-event[data-color="primary"],
    .fc-timegrid-event[data-color="primary"] {
        background-color: #007bff !important;
        border-color: #007bff !important;
    }

    /* Warna untuk event success (hijau) */
    .fc-event[data-color="success"],
    .fc-event[style*="rgb(40, 167, 69)"],
    .fc-event[style*="#28a745"],
    .fc-daygrid-event[data-color="success"],
    .fc-timegrid-event[data-color="success"] {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }

    /* Warna untuk event danger (merah) */
    .fc-event[data-color="danger"],
    .fc-event[style*="rgb(220, 53, 69)"],
    .fc-event[style*="#dc3545"],
    .fc-daygrid-event[data-color="danger"],
    .fc-timegrid-event[data-color="danger"] {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    /* Warna untuk event warning (kuning) */
    .fc-event[data-color="warning"],
    .fc-event[style*="rgb(255, 193, 7)"],
    .fc-event[style*="#ffc107"],
    .fc-daygrid-event[data-color="warning"],
    .fc-timegrid-event[data-color="warning"] {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
    }

    /* Memastikan event dot indicator terlihat */
    .fc-daygrid-event-dot {
        display: none !important;
    }

    /* Memastikan event time terlihat */
    .fc-event-time {
        color: #ffffff !important;
        font-weight: 500;
    }

    /* Memastikan event di dayGridMonth terlihat dengan benar */
    .fc-daygrid-event {
        border: none !important;
        margin: 1px 0 !important;
        padding: 2px 4px !important;
        min-height: 20px !important;
        cursor: pointer !important;
    }

    /* Memastikan event di timeGrid terlihat dengan benar */
    .fc-timegrid-event {
        border: none !important;
        margin: 1px 0 !important;
        padding: 2px 4px !important;
        min-height: 20px !important;
        cursor: pointer !important;
    }

    /* Memastikan event frame terlihat */
    .fc-event-frame {
        padding: 2px 4px !important;
    }

    /* Override untuk memastikan warna tidak di-override oleh FullCalendar default */
    .fc-h-event {
        border: none !important;
    }

    /* Memastikan event terlihat di semua view */
    .fc-daygrid-day-events .fc-event,
    .fc-timegrid-col-events .fc-event {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* Memastikan event block terlihat di dayGridMonth */
    .fc-daygrid-day .fc-daygrid-event {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    /* Memastikan event segment terlihat */
    .fc-daygrid-event-harness,
    .fc-timegrid-event-harness {
        display: block !important;
        visibility: visible !important;
    }

    .user-event-group {
        margin-bottom: 20px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
        border-left: 4px solid #007bff;
    }

    .user-event-header {
        font-weight: 600;
        color: #111827;
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .user-event-info {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .realtime-clock {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 8px;
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 20px;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
    }

    .modal-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 5px;
    }

    .modal-body {
        padding: 20px;
    }

    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .form-control,
    .form-select {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 10px 12px;
        width: 100%;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    /* Dark Mode Improvements */
    [data-bs-theme="dark"] .modern-search {
        background: #1e1e2d;
        border-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .modern-search input {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .modern-search input::placeholder {
        color: #a0a0a0;
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

    [data-bs-theme="dark"] .user-dropdown-btn {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .user-dropdown-btn:hover {
        background: #1e1e2d;
    }

    [data-bs-theme="dark"] .user-name,
    [data-bs-theme="dark"] .user-chevron {
        color: #e2e2e2 !important;
    }

    [data-bs-theme="dark"] .calendar-container {
        background: #1e1e2d;
        color: #e2e2e2;
        border: 1px solid #4b4b5a;
    }

    [data-bs-theme="dark"] .calendar-title {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .calendar-nav-btn {
        background: #2b2b40;
        border-color: #4b4b5a;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .calendar-nav-btn:hover {
        background: #36364e;
    }

    [data-bs-theme="dark"] .calendar-view-toggle {
        background: #2b2b40;
    }

    [data-bs-theme="dark"] .calendar-view-btn {
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .calendar-view-btn.active {
        background: #1e1e2d;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .user-event-group {
        background: #2b2b40;
        border-left-color: #007bff;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .user-event-header {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .user-event-info {
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .modal-content {
        background: #1e1e2d;
        border-color: #4b4b5a;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .modal-header {
        border-bottom-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .modal-title {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .modal-subtitle {
        color: #a0a0a0;
    }

    [data-bs-theme="dark"] .form-label {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background-color: #2b2b40;
        border-color: #4b4b5a;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background-color: #36364e;
        border-color: #007bff;
        color: #e2e2e2;
    }

    /* FullCalendar Dark Mode Overrides */
    [data-bs-theme="dark"] .fc-theme-standard td,
    [data-bs-theme="dark"] .fc-theme-standard th {
        border-color: #4b4b5a;
    }

    [data-bs-theme="dark"] .fc-col-header-cell-cushion,
    [data-bs-theme="dark"] .fc-daygrid-day-number {
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] .fc-day-today {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }

    [data-bs-theme="dark"] .fc-list-day-cushion {
        background-color: #2b2b40;
    }

    [data-bs-theme="dark"] .fc-list-event:hover td {
        background-color: #36364e;
    }

    [data-bs-theme="dark"] .fc-scrollgrid-section-header>*,
    [data-bs-theme="dark"] .fc-col-header-cell,
    [data-bs-theme="dark"] .fc-col-header {
        background-color: #2b2b40 !important;
        color: #e2e2e2;
    }

    [data-bs-theme="dark"] a.fc-col-header-cell-cushion {
        color: #e2e2e2 !important;
        text-decoration: none;
    }

    .color-radio-group {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .color-radio {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .color-radio input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .color-preview {
        width: 30px;
        height: 30px;
        border-radius: 4px;
        border: 2px solid #e5e7eb;
    }

    .color-preview.danger {
        background: #dc3545;
    }

    .color-preview.success {
        background: #28a745;
    }

    .color-preview.primary {
        background: #007bff;
    }

    .color-preview.warning {
        background: #ffc107;
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 15px 20px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-close {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        color: #374151;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-close:hover {
        background: #e5e7eb;
    }

    .btn-primary {
        background: #007bff;
        color: #fff;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    /* Dark Mode Styles untuk Kalender */
    body.dark-mode .calendar-container,
    [data-theme="dark"] .calendar-container {
        background: #1f2937;
        border-color: #374151;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    body.dark-mode .calendar-title,
    [data-theme="dark"] .calendar-title {
        color: #f9fafb;
    }

    body.dark-mode .realtime-clock,
    [data-theme="dark"] .realtime-clock {
        color: #d1d5db;
    }

    body.dark-mode .calendar-nav-btn,
    [data-theme="dark"] .calendar-nav-btn {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }

    body.dark-mode .calendar-nav-btn:hover,
    [data-theme="dark"] .calendar-nav-btn:hover {
        background: #4b5563;
        border-color: #6b7280;
    }

    body.dark-mode .calendar-view-toggle,
    [data-theme="dark"] .calendar-view-toggle {
        background: #374151;
    }

    body.dark-mode .calendar-view-btn,
    [data-theme="dark"] .calendar-view-btn {
        color: #d1d5db;
    }

    body.dark-mode .calendar-view-btn.active,
    [data-theme="dark"] .calendar-view-btn.active {
        background: #1f2937;
        color: #f9fafb;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }

    body.dark-mode .add-event-btn,
    [data-theme="dark"] .add-event-btn {
        background: #007bff;
        color: #fff;
    }

    body.dark-mode .add-event-btn:hover,
    [data-theme="dark"] .add-event-btn:hover {
        background: #0056b3;
    }

    body.dark-mode .user-event-group,
    [data-theme="dark"] .user-event-group {
        background: #111827;
        border-left-color: #007bff;
    }

    body.dark-mode .user-event-header,
    [data-theme="dark"] .user-event-header {
        color: #f9fafb;
    }

    body.dark-mode .user-event-info,
    [data-theme="dark"] .user-event-info {
        color: #d1d5db;
    }

    /* Dark Mode untuk FullCalendar */
    body.dark-mode .fc,
    [data-theme="dark"] .fc {
        color: #f9fafb;
    }

    body.dark-mode .fc-theme-standard td,
    body.dark-mode .fc-theme-standard th,
    [data-theme="dark"] .fc-theme-standard td,
    [data-theme="dark"] .fc-theme-standard th {
        border-color: #374151;
    }

    body.dark-mode .fc-col-header-cell,
    [data-theme="dark"] .fc-col-header-cell {
        background: #111827;
        color: #f9fafb;
    }

    body.dark-mode .fc-daygrid-day,
    [data-theme="dark"] .fc-daygrid-day {
        background: #1f2937;
    }

    body.dark-mode .fc-daygrid-day.fc-day-today,
    [data-theme="dark"] .fc-daygrid-day.fc-day-today {
        background: #1e3a8a;
    }

    body.dark-mode .fc-daygrid-day-number,
    [data-theme="dark"] .fc-daygrid-day-number {
        color: #f9fafb;
    }

    body.dark-mode .fc-daygrid-day.fc-day-today .fc-daygrid-day-number,
    [data-theme="dark"] .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        color: #fff;
        font-weight: 600;
    }

    body.dark-mode .fc-timegrid-col,
    [data-theme="dark"] .fc-timegrid-col {
        background: #1f2937;
    }

    body.dark-mode .fc-timegrid-slot,
    [data-theme="dark"] .fc-timegrid-slot {
        border-color: #374151;
    }

    body.dark-mode .fc-timegrid-slot-label,
    [data-theme="dark"] .fc-timegrid-slot-label {
        color: #9ca3af;
    }

    body.dark-mode .fc-event,
    [data-theme="dark"] .fc-event {
        border-color: transparent;
    }

    body.dark-mode .fc-event-title,
    [data-theme="dark"] .fc-event-title {
        color: #fff;
    }

    body.dark-mode .fc-scrollgrid,
    [data-theme="dark"] .fc-scrollgrid {
        border-color: #374151;
    }

    body.dark-mode .fc-scrollgrid-section-header>td,
    [data-theme="dark"] .fc-scrollgrid-section-header>td {
        border-color: #374151;
    }

    body.dark-mode .fc-timegrid-axis,
    [data-theme="dark"] .fc-timegrid-axis {
        color: #9ca3af;
        border-color: #374151;
    }

    body.dark-mode .fc-timegrid-now-indicator-line,
    [data-theme="dark"] .fc-timegrid-now-indicator-line {
        border-color: #007bff;
    }

    body.dark-mode .fc-button,
    [data-theme="dark"] .fc-button {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }

    body.dark-mode .fc-button:hover,
    [data-theme="dark"] .fc-button:hover {
        background: #4b5563;
        border-color: #6b7280;
    }

    body.dark-mode .fc-button-primary:not(:disabled):active,
    body.dark-mode .fc-button-primary:not(:disabled).fc-button-active,
    [data-theme="dark"] .fc-button-primary:not(:disabled):active,
    [data-theme="dark"] .fc-button-primary:not(:disabled).fc-button-active {
        background: #1f2937;
        border-color: #374151;
    }

    /* Dark Mode untuk Modal */
    body.dark-mode .modal-content,
    [data-theme="dark"] .modal-content {
        background: #1f2937;
        border-color: #374151;
    }

    body.dark-mode .modal-header,
    [data-theme="dark"] .modal-header {
        border-bottom-color: #374151;
        background: #111827;
    }

    body.dark-mode .modal-title,
    [data-theme="dark"] .modal-title {
        color: #f9fafb;
    }

    body.dark-mode .modal-subtitle,
    [data-theme="dark"] .modal-subtitle {
        color: #d1d5db;
    }

    body.dark-mode .modal-body,
    [data-theme="dark"] .modal-body {
        background: #1f2937;
    }

    body.dark-mode .modal-footer,
    [data-theme="dark"] .modal-footer {
        border-top-color: #374151;
        background: #1f2937;
    }

    body.dark-mode .form-label,
    [data-theme="dark"] .form-label {
        color: #e5e7eb;
    }

    body.dark-mode .form-control,
    body.dark-mode .form-select,
    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select {
        background: #111827;
        border-color: #4b5563;
        color: #f9fafb;
    }

    body.dark-mode .form-control:focus,
    body.dark-mode .form-select:focus,
    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
        background: #111827;
        color: #f9fafb;
    }

    body.dark-mode .form-control::placeholder,
    [data-theme="dark"] .form-control::placeholder {
        color: #6b7280;
    }

    body.dark-mode .color-preview,
    [data-theme="dark"] .color-preview {
        border-color: #4b5563;
    }

    body.dark-mode .btn-close,
    [data-theme="dark"] .btn-close {
        background: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }

    body.dark-mode .btn-close:hover,
    [data-theme="dark"] .btn-close:hover {
        background: #4b5563;
        border-color: #6b7280;
    }

    body.dark-mode .btn-primary,
    [data-theme="dark"] .btn-primary {
        background: #007bff;
        color: #fff;
    }

    body.dark-mode .btn-primary:hover,
    [data-theme="dark"] .btn-primary:hover {
        background: #0056b3;
    }

    /* Dark Mode untuk Page Heading */
    body.dark-mode .page-heading h3,
    [data-theme="dark"] .page-heading h3 {
        color: #f9fafb;
    }

    body.dark-mode .page-heading .text-subtitle,
    [data-theme="dark"] .page-heading .text-subtitle {
        color: #d1d5db;
    }

    /* Dark Mode untuk Card */
    body.dark-mode .card,
    [data-theme="dark"] .card {
        background: #1f2937;
        border-color: #374151;
    }

    body.dark-mode .card-body,
    [data-theme="dark"] .card-body {
        color: #f9fafb;
    }

    /* Dark Mode untuk Realtime Clock Background di Mobile */
    @media (max-width: 767.98px) {

        body.dark-mode .realtime-clock,
        [data-theme="dark"] .realtime-clock {
            background: #111827;
            border: 1px solid #374151;
        }
    }

    /* Responsive Styles untuk Kalender */

    /* Tablet (768px - 991px) */
    @media (max-width: 991.98px) {
        .calendar-container {
            padding: 15px;
        }

        .calendar-header {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }

        .calendar-nav {
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .calendar-title {
            font-size: 1.25rem;
            flex: 1;
            text-align: center;
        }

        .realtime-clock {
            font-size: 0.75rem;
            text-align: center;
            width: 100%;
            margin-top: 5px;
        }

        .calendar-header>div:last-child {
            justify-content: center;
            flex-wrap: wrap;
        }

        .calendar-view-toggle {
            flex: 1;
            min-width: 200px;
        }

        .add-event-btn {
            flex: 1;
            min-width: 150px;
            justify-content: center;
        }

        .user-event-group {
            padding: 12px;
        }

        .modal-dialog {
            max-width: 90%;
        }
    }

    /* Mobile (max-width: 767px) */
    @media (max-width: 767.98px) {
        .calendar-container {
            padding: 10px;
            border-radius: 4px;
        }

        .calendar-header {
            flex-direction: column;
            gap: 10px;
            margin-bottom: 15px;
        }

        .calendar-nav {
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        .calendar-nav-btn {
            padding: 6px 10px;
            font-size: 0.875rem;
        }

        .calendar-title {
            font-size: 1rem;
            text-align: center;
            width: 100%;
            order: 2;
        }

        .realtime-clock {
            font-size: 0.7rem;
            text-align: center;
            width: 100%;
            order: 3;
            margin-top: 5px;
            padding: 5px;
            background: #f3f4f6;
            border-radius: 4px;
        }

        .calendar-header>div:last-child {
            flex-direction: column;
            width: 100%;
            gap: 10px;
        }

        .calendar-view-toggle {
            width: 100%;
            justify-content: center;
        }

        .calendar-view-btn {
            padding: 6px 12px;
            font-size: 0.75rem;
            flex: 1;
        }

        .add-event-btn {
            width: 100%;
            padding: 10px;
            justify-content: center;
            font-size: 0.875rem;
        }

        .add-event-btn i {
            font-size: 1rem;
        }

        /* FullCalendar Mobile Adjustments */
        #calendar {
            margin-top: 10px;
            overflow-x: auto;
        }

        .fc {
            font-size: 0.75rem;
        }

        .fc-toolbar {
            flex-direction: column;
            gap: 10px;
        }

        .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .fc-col-header-cell {
            font-size: 0.7rem;
            padding: 5px 2px;
        }

        .fc-daygrid-day {
            font-size: 0.7rem;
        }

        .fc-timegrid-slot {
            font-size: 0.65rem;
        }

        .fc-event {
            font-size: 0.7rem;
            padding: 2px 4px;
        }

        .fc-event-title {
            font-size: 0.7rem;
        }

        .user-event-group {
            padding: 10px;
            margin-bottom: 15px;
        }

        .user-event-header {
            font-size: 0.875rem;
        }

        .user-event-info {
            font-size: 0.75rem;
        }

        /* Modal Mobile */
        .modal-dialog {
            max-width: 100%;
            margin: 0;
            height: 100vh;
        }

        .modal-content {
            height: 100vh;
            border-radius: 0;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 15px;
            flex-shrink: 0;
        }

        .modal-title {
            font-size: 1.125rem;
        }

        .modal-subtitle {
            font-size: 0.75rem;
        }

        .modal-body {
            padding: 15px;
            overflow-y: auto;
            flex: 1;
        }

        .modal-footer {
            padding: 15px;
            flex-shrink: 0;
            flex-direction: column;
            gap: 10px;
        }

        .modal-footer button {
            width: 100%;
        }

        .color-radio-group {
            flex-direction: column;
            gap: 10px;
        }

        .color-radio {
            width: 100%;
            justify-content: flex-start;
        }

        .form-control,
        .form-select {
            padding: 12px;
            font-size: 16px;
            /* Prevents zoom on iOS */
        }

        .page-heading {
            padding: 10px 0;
        }

        .page-title h3 {
            font-size: 1.25rem;
        }

        .page-title .text-subtitle {
            font-size: 0.75rem;
        }
    }

    /* Small Mobile (max-width: 575px) */
    @media (max-width: 575.98px) {
        .calendar-container {
            padding: 8px;
        }

        .calendar-title {
            font-size: 0.9rem;
        }

        .realtime-clock {
            font-size: 0.65rem;
        }

        .calendar-view-btn {
            padding: 5px 8px;
            font-size: 0.7rem;
        }

        .add-event-btn {
            padding: 8px;
            font-size: 0.8rem;
        }

        .fc {
            font-size: 0.65rem;
        }

        .fc-col-header-cell {
            font-size: 0.65rem;
            padding: 3px 1px;
        }

        .fc-daygrid-day-number {
            font-size: 0.7rem;
            padding: 2px;
        }

        .fc-event {
            font-size: 0.65rem;
            padding: 1px 3px;
        }

        .modal-header {
            padding: 12px;
        }

        .modal-title {
            font-size: 1rem;
        }

        .modal-body {
            padding: 12px;
        }

        .modal-footer {
            padding: 12px;
        }
    }

    /* Desktop Large (min-width: 1200px) */
    @media (min-width: 1200px) {
        .calendar-container {
            padding: 25px;
        }

        .calendar-header {
            gap: 20px;
        }

        .calendar-title {
            font-size: 1.75rem;
        }

        .realtime-clock {
            font-size: 1rem;
        }
    }

    /* FullCalendar Responsive Overrides */
    @media (max-width: 767.98px) {
        .fc-scroller {
            overflow-x: auto !important;
            overflow-y: auto !important;
        }

        .fc-daygrid-body,
        .fc-timeGrid-body {
            min-width: 600px;
        }

        .fc-timegrid-cols {
            min-width: 600px;
        }
    }

    /* SweetAlert2 Responsive Styles */
    .swal2-popup {
        width: 90% !important;
        max-width: 600px !important;
        padding: 1.5rem !important;
        font-size: 1rem !important;
    }

    .swal2-title {
        font-size: 1.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .swal2-html-container {
        font-size: 0.875rem !important;
        margin: 1rem 0 !important;
    }

    .swal2-input,
    .swal2-textarea,
    .swal2-select {
        width: 100% !important;
        margin: 0.5rem 0 !important;
        padding: 0.75rem !important;
        font-size: 0.875rem !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.5rem !important;
    }

    .swal2-input:focus,
    .swal2-textarea:focus,
    .swal2-select:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1) !important;
        outline: none !important;
    }

    .swal2-actions {
        margin-top: 1rem !important;
        gap: 0.5rem !important;
    }

    .swal2-confirm,
    .swal2-cancel {
        padding: 0.75rem 1.5rem !important;
        font-size: 0.875rem !important;
        border-radius: 0.5rem !important;
        font-weight: 500 !important;
    }

    .swal2-color-radio-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin: 1rem 0;
        justify-content: center;
    }

    .swal2-color-radio {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        transition: all 0.2s;
        min-width: 80px;
    }

    .swal2-color-radio:hover {
        border-color: #007bff;
        background: #f3f4f6;
    }

    .swal2-color-radio input[type="radio"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        margin: 0;
    }

    .swal2-color-radio input[type="radio"]:checked+.swal2-color-preview {
        border-width: 3px;
        border-color: #007bff;
    }

    .swal2-color-preview {
        width: 40px;
        height: 40px;
        border-radius: 0.375rem;
        border: 2px solid #e5e7eb;
        transition: all 0.2s;
    }

    .swal2-color-preview.danger {
        background: #dc3545;
    }

    .swal2-color-preview.success {
        background: #28a745;
    }

    .swal2-color-preview.primary {
        background: #007bff;
    }

    .swal2-color-preview.warning {
        background: #ffc107;
    }

    .swal2-color-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: #374151;
        text-align: center;
    }

    /* Responsive untuk Tablet */
    @media (max-width: 991.98px) {
        .swal2-popup {
            width: 85% !important;
            max-width: 550px !important;
            padding: 1.25rem !important;
        }

        .swal2-title {
            font-size: 1.25rem !important;
        }

        .swal2-color-radio-group {
            gap: 0.75rem;
        }

        .swal2-color-radio {
            min-width: 70px;
            padding: 0.5rem;
        }

        .swal2-color-preview {
            width: 35px;
            height: 35px;
        }
    }

    /* Responsive untuk Mobile */
    @media (max-width: 767.98px) {
        .swal2-popup {
            width: 95% !important;
            max-width: 100% !important;
            padding: 1rem !important;
            margin: 0.5rem !important;
        }

        .swal2-title {
            font-size: 1.125rem !important;
            margin-bottom: 0.5rem !important;
        }

        .swal2-html-container {
            font-size: 0.8125rem !important;
            margin: 0.75rem 0 !important;
        }

        .swal2-input,
        .swal2-textarea,
        .swal2-select {
            padding: 0.875rem !important;
            font-size: 16px !important;
            /* Prevents zoom on iOS */
            margin: 0.5rem 0 !important;
        }

        .swal2-actions {
            flex-direction: column !important;
            width: 100% !important;
            margin-top: 1rem !important;
        }

        .swal2-confirm,
        .swal2-cancel {
            width: 100% !important;
            padding: 0.875rem !important;
            font-size: 0.875rem !important;
        }

        .swal2-color-radio-group {
            gap: 0.5rem;
            margin: 0.75rem 0;
        }

        .swal2-color-radio {
            flex: 1;
            min-width: 0;
            padding: 0.5rem 0.25rem;
        }

        .swal2-color-preview {
            width: 30px;
            height: 30px;
        }

        .swal2-color-label {
            font-size: 0.7rem;
        }
    }

    /* Small Mobile */
    @media (max-width: 575.98px) {
        .swal2-popup {
            width: 98% !important;
            padding: 0.75rem !important;
            margin: 0.25rem !important;
        }

        .swal2-title {
            font-size: 1rem !important;
        }

        .swal2-html-container {
            font-size: 0.75rem !important;
        }

        .swal2-input,
        .swal2-textarea,
        .swal2-select {
            padding: 0.75rem !important;
            font-size: 16px !important;
        }

        .swal2-color-radio-group {
            gap: 0.375rem;
        }

        .swal2-color-radio {
            padding: 0.375rem 0.125rem;
        }

        .swal2-color-preview {
            width: 25px;
            height: 25px;
        }

        .swal2-color-label {
            font-size: 0.65rem;
        }
    }

    /* Dark Mode untuk SweetAlert2 */
    body.dark-mode .swal2-popup,
    [data-theme="dark"] .swal2-popup {
        background: #1f2937 !important;
        color: #f9fafb !important;
    }

    body.dark-mode .swal2-title,
    [data-theme="dark"] .swal2-title {
        color: #f9fafb !important;
    }

    body.dark-mode .swal2-html-container,
    [data-theme="dark"] .swal2-html-container {
        color: #d1d5db !important;
    }

    body.dark-mode .swal2-input,
    body.dark-mode .swal2-textarea,
    body.dark-mode .swal2-select,
    [data-theme="dark"] .swal2-input,
    [data-theme="dark"] .swal2-textarea,
    [data-theme="dark"] .swal2-select {
        background: #111827 !important;
        border-color: #4b5563 !important;
        color: #f9fafb !important;
    }

    body.dark-mode .swal2-input:focus,
    body.dark-mode .swal2-textarea:focus,
    body.dark-mode .swal2-select:focus,
    [data-theme="dark"] .swal2-input:focus,
    [data-theme="dark"] .swal2-textarea:focus,
    [data-theme="dark"] .swal2-select:focus {
        border-color: #007bff !important;
        background: #111827 !important;
        color: #f9fafb !important;
    }

    body.dark-mode .swal2-color-radio,
    [data-theme="dark"] .swal2-color-radio {
        border-color: #4b5563 !important;
        background: #111827 !important;
    }

    body.dark-mode .swal2-color-radio:hover,
    [data-theme="dark"] .swal2-color-radio:hover {
        border-color: #007bff !important;
        background: #1f2937 !important;
    }

    body.dark-mode .swal2-color-label,
    [data-theme="dark"] .swal2-color-label {
        color: #d1d5db !important;
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
                <p class="text-subtitle text-muted">Kalender Digital dengan Waktu Indonesia</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <?php include_once(APPPATH . 'Views/components/errors.php'); ?>
                <?php include_once(APPPATH . 'Views/components/flash.php'); ?>

                <div class="calendar-container">
                    <div class="calendar-header">
                        <div class="calendar-nav">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <button class="calendar-nav-btn" id="prevBtn" aria-label="Previous">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="calendar-nav-btn" id="nextBtn" aria-label="Next">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                            <h2 class="calendar-title" id="calendarTitle"></h2>
                            <div class="realtime-clock" id="realtimeClock"></div>
                        </div>
                        <div class="calendar-controls">
                            <div class="calendar-view-toggle">
                                <button class="calendar-view-btn <?= $viewType == 'month' ? 'active' : '' ?>"
                                    data-view="month">
                                    <span class="d-none d-sm-inline">Month</span>
                                    <span class="d-sm-none">M</span>
                                </button>
                                <button class="calendar-view-btn <?= $viewType == 'week' ? 'active' : '' ?>"
                                    data-view="week">
                                    <span class="d-none d-sm-inline">Week</span>
                                    <span class="d-sm-none">W</span>
                                </button>
                                <button class="calendar-view-btn <?= $viewType == 'day' ? 'active' : '' ?>"
                                    data-view="day">
                                    <span class="d-none d-sm-inline">Day</span>
                                    <span class="d-sm-none">D</span>
                                </button>
                            </div>
                            <button class="add-event-btn" id="addEventBtn">
                                <i class="bi bi-plus-circle"></i>
                                <span class="d-none d-md-inline">Add Event</span>
                                <span class="d-md-none">Add</span>
                            </button>
                        </div>
                    </div>

                    <?php if ($level == 2 && !empty($groupedEvents)): ?>
                        <div
                            style="margin-bottom: 20px; padding: 1rem; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 0.5rem;">
                            <h5 style="margin-bottom: 10px; color: #111827; font-size: 1rem;">Event dari User Level 0 (Hanya
                                Lihat)</h5>
                            <p style="margin-bottom: 15px; color: #92400e; font-size: 0.875rem;">
                                Event di bawah ini dibuat oleh user Level 0. Anda hanya dapat melihat event ini, tidak dapat
                                mengedit atau menghapusnya.
                            </p>
                            <?php foreach ($groupedEvents as $group): ?>
                                <div class="user-event-group" style="margin-bottom: 10px;">
                                    <div class="user-event-header">
                                        <?= esc($group['user_name']) ?>
                                    </div>
                                    <div class="user-event-info">
                                        <strong>Email:</strong> <?= esc($group['user_email']) ?>
                                    </div>
                                    <div class="user-event-info">
                                        <strong>Total Event:</strong> <?= count($group['events']) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detail Event -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="eventDetailModalLabel">Detail Event</h5>
                    <p class="modal-subtitle">Informasi lengkap tentang event</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="modal-body" id="eventDetailBody">
                <!-- Content akan diisi oleh JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-close" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn-primary" id="deleteEventBtn" style="display: none;">
                    <i class="bi bi-trash"></i> Hapus Event
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Event -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                    <p class="modal-subtitle">Plan your next big moment: schedule or edit an event to stay on track</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <form id="eventForm" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="view_type" id="viewTypeInput" value="<?= $viewType ?>">
                <input type="hidden" name="current_date" id="currentDateInput" value="<?= $currentDate ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Event Title <span style="color: #dc3545;">*</span></label>
                        <input type="text" class="form-control" name="event_title" id="eventTitle"
                            placeholder="Contoh: Rapat Tim, Event Launching, Target Bulanan" maxlength="255" required>
                        <small class="form-text text-muted">Masukkan judul event yang jelas dan deskriptif (maksimal 255
                            karakter)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Event Color</label>
                        <div class="color-radio-group">
                            <div class="color-radio">
                                <input type="radio" name="event_color" id="colorDanger" value="danger" checked>
                                <label for="colorDanger"
                                    style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                    <span class="color-preview danger"></span>
                                    <span>Danger</span>
                                </label>
                            </div>
                            <div class="color-radio">
                                <input type="radio" name="event_color" id="colorSuccess" value="success">
                                <label for="colorSuccess"
                                    style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                    <span class="color-preview success"></span>
                                    <span>Success</span>
                                </label>
                            </div>
                            <div class="color-radio">
                                <input type="radio" name="event_color" id="colorPrimary" value="primary">
                                <label for="colorPrimary"
                                    style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                    <span class="color-preview primary"></span>
                                    <span>Primary</span>
                                </label>
                            </div>
                            <div class="color-radio">
                                <input type="radio" name="event_color" id="colorWarning" value="warning">
                                <label for="colorWarning"
                                    style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                    <span class="color-preview warning"></span>
                                    <span>Warning</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Enter Start Date</label>
                        <input type="datetime-local" class="form-control" name="start_date" id="startDate" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Enter End Date</label>
                        <input type="datetime-local" class="form-control" name="end_date" id="endDate" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn-primary">Add Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/id.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.2/dist/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let calendar;
        let currentView = '<?= $viewType ?>';
        let currentDate = new Date('<?= $currentDate ?>');

        // Initialize FullCalendar
        const calendarEl = document.getElementById('calendar');

        // Deteksi ukuran layar untuk menentukan view default di mobile
        const isMobile = window.innerWidth <= 767;
        let defaultView = currentView === 'month' ? 'dayGridMonth' : (currentView === 'week' ? 'timeGridWeek' : 'timeGridDay');

        // Di mobile, gunakan dayGridMonth jika view adalah week/day (karena lebih mudah dilihat)
        if (isMobile && (currentView === 'week' || currentView === 'day')) {
            // Tetap gunakan view yang dipilih user, tapi optimasi untuk mobile
            if (currentView === 'week') {
                defaultView = 'timeGridWeek';
            } else if (currentView === 'day') {
                defaultView = 'timeGridDay';
            }
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: defaultView,
            initialDate: currentDate,
            locale: 'id',
            timeZone: 'Asia/Jakarta',
            headerToolbar: false,
            height: 'auto',
            contentHeight: 'auto',
            events: function (fetchInfo, successCallback, failureCallback) {
                const start = fetchInfo.startStr.split('T')[0];
                const end = fetchInfo.endStr.split('T')[0];

                console.log('Fetching events for range:', start, 'to', end);

                const apiUrl = `<?= url_to('kalender.getEvents') ?>?start=${start}&end=${end}`;
                console.log('Fetching events from:', apiUrl);

                fetch(apiUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    cache: 'no-cache'
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response ok:', response.ok);
                        console.log('Response headers:', {
                            'content-type': response.headers.get('content-type'),
                            'content-length': response.headers.get('content-length')
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.status + ' ' + response.statusText);
                        }

                        // Pastikan response adalah JSON
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            console.warn('Response is not JSON, content-type:', contentType);
                        }

                        return response.json();
                    })
                    .then(data => {
                        console.log('Events fetched from API:', data);
                        console.log('Date range:', start, 'to', end);
                        console.log('Number of events:', Array.isArray(data) ? data.length : 0);

                        // Log setiap event yang diterima
                        if (Array.isArray(data) && data.length > 0) {
                            console.log('Events details:');
                            data.forEach((evt, idx) => {
                                console.log(`  Event ${idx + 1}:`, {
                                    id: evt.id,
                                    title: evt.title,
                                    start: evt.start,
                                    end: evt.end,
                                    backgroundColor: evt.backgroundColor,
                                    color: evt.color,
                                    eventColor: evt.extendedProps?.event_color
                                });
                            });
                        }

                        // Validasi dan pastikan setiap event memiliki title dan warna
                        if (Array.isArray(data)) {
                            if (data.length === 0) {
                                console.warn('No events found in date range:', start, 'to', end);
                                console.warn('Check server logs for more details');
                            } else {
                                console.log('Events found:', data.length);
                            }

                            data.forEach((event, index) => {
                                // Pastikan title tidak null atau undefined
                                if (!event.title || event.title.trim() === '') {
                                    console.warn(`Event ${index} has no title:`, event);
                                    event.title = 'Event Tanpa Judul';
                                }
                                event.title = (event.title || 'Event Tanpa Judul').trim();

                                // Pastikan warna diterapkan dengan benar - prioritas: backgroundColor > color > default
                                if (!event.backgroundColor) {
                                    if (event.color) {
                                        event.backgroundColor = event.color;
                                    } else {
                                        // Ambil dari extendedProps jika ada
                                        const eventColor = event.extendedProps?.event_color || 'primary';
                                        const colorMap = {
                                            'primary': '#007bff',
                                            'success': '#28a745',
                                            'danger': '#dc3545',
                                            'warning': '#ffc107'
                                        };
                                        event.backgroundColor = colorMap[eventColor] || '#007bff';
                                    }
                                }

                                // Pastikan borderColor sama dengan backgroundColor
                                if (!event.borderColor) {
                                    event.borderColor = event.backgroundColor;
                                }

                                // Pastikan color property ada
                                if (!event.color) {
                                    event.color = event.backgroundColor;
                                }

                                // Pastikan textColor ada
                                if (!event.textColor) {
                                    event.textColor = '#ffffff';
                                }

                                // Pastikan start dan end ada dan valid
                                if (!event.start) {
                                    console.error(`Event ${index} has no start date:`, event);
                                } else {
                                    // Pastikan start adalah string atau Date object yang valid
                                    if (typeof event.start === 'string') {
                                        const startDate = new Date(event.start);
                                        if (isNaN(startDate.getTime())) {
                                            console.error(`Event ${index} has invalid start date:`, event.start);
                                        }
                                    }
                                }

                                if (!event.end) {
                                    console.error(`Event ${index} has no end date:`, event);
                                } else {
                                    // Pastikan end adalah string atau Date object yang valid
                                    if (typeof event.end === 'string') {
                                        const endDate = new Date(event.end);
                                        if (isNaN(endDate.getTime())) {
                                            console.error(`Event ${index} has invalid end date:`, event.end);
                                        }
                                    }
                                }

                                // Pastikan allDay tidak true (kecuali memang allDay event)
                                if (event.allDay === undefined || event.allDay === null) {
                                    event.allDay = false;
                                }

                                // Pastikan display property ada
                                if (!event.display) {
                                    event.display = 'block';
                                }

                                // Pastikan extendedProps ada
                                if (!event.extendedProps) {
                                    event.extendedProps = {};
                                }

                                // Pastikan event_color ada di extendedProps
                                if (!event.extendedProps.event_color) {
                                    // Coba deteksi dari backgroundColor
                                    const bgColor = event.backgroundColor.toLowerCase();
                                    if (bgColor === '#007bff' || bgColor === 'rgb(0, 123, 255)') {
                                        event.extendedProps.event_color = 'primary';
                                    } else if (bgColor === '#28a745' || bgColor === 'rgb(40, 167, 69)') {
                                        event.extendedProps.event_color = 'success';
                                    } else if (bgColor === '#dc3545' || bgColor === 'rgb(220, 53, 69)') {
                                        event.extendedProps.event_color = 'danger';
                                    } else if (bgColor === '#ffc107' || bgColor === 'rgb(255, 193, 7)') {
                                        event.extendedProps.event_color = 'warning';
                                    } else {
                                        event.extendedProps.event_color = 'primary';
                                    }
                                }

                                console.log(`Event ${index}:`, {
                                    id: event.id,
                                    title: event.title,
                                    start: event.start,
                                    end: event.end,
                                    allDay: event.allDay,
                                    backgroundColor: event.backgroundColor,
                                    color: event.color,
                                    borderColor: event.borderColor,
                                    textColor: event.textColor,
                                    eventColor: event.extendedProps.event_color
                                });
                            });
                        } else {
                            console.error('Invalid data format received:', data);
                        }

                        // Pastikan data adalah array sebelum dipanggil successCallback
                        if (!Array.isArray(data)) {
                            console.error('Invalid response format:', data);
                            console.error('Expected array but got:', typeof data);
                            failureCallback(new Error('Invalid response format: expected array'));
                            return;
                        }

                        // Jika tidak ada event, tetap panggil successCallback dengan array kosong
                        // FullCalendar akan menampilkan kalender kosong
                        if (data.length === 0) {
                            console.warn('No events found for date range:', start, 'to', end);
                            console.warn('This could mean:');
                            console.warn('1. No events exist in this date range');
                            console.warn('2. User does not have access to events in this date range');
                            console.warn('3. Check server logs for more details');
                        }

                        successCallback(data);
                    })
                    .catch(error => {
                        console.error('Error fetching events:', error);
                        console.error('Error details:', error.message);
                        console.error('Error stack:', error.stack);

                        // Tampilkan error ke user (opsional, bisa di-comment jika tidak ingin)
                        // Swal.fire({
                        //     icon: 'error',
                        //     title: 'Error',
                        //     text: 'Gagal memuat event: ' + error.message,
                        //     confirmButtonColor: '#4e73df',
                        //     confirmButtonText: 'OK'
                        // });

                        // Panggil failureCallback dengan error
                        failureCallback(error);
                    });
            },
            dateClick: function (info) {
                // Ketika tanggal diklik, tampilkan semua event di tanggal tersebut
                const clickedDate = info.dateStr;
                showEventsForDate(clickedDate);
            },
            eventClick: function (info) {
                // Tampilkan detail event
                const event = info.event;
                showEventDetail(event);
            },
            eventDisplay: 'block',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            displayEventTime: true,
            displayEventEnd: true,
            eventTextColor: '#ffffff',
            eventBorderColor: 'transparent',
            // Pastikan event ditampilkan dengan benar
            eventDidMount: function (info) {
                // Pastikan title ditampilkan
                const title = info.event.title || info.event.extendedProps?.event_title || 'Event Tanpa Judul';

                // Dapatkan warna dari event
                const bgColor = info.event.backgroundColor || info.event.color || '#007bff';
                const borderColor = info.event.borderColor || bgColor;
                const textColor = info.event.textColor || '#ffffff';

                // Dapatkan event color name untuk data attribute
                const eventColor = info.event.extendedProps?.event_color || 'primary';

                // Terapkan warna dengan !important menggunakan setAttribute dan class
                info.el.setAttribute('data-color', eventColor);
                info.el.style.setProperty('background-color', bgColor, 'important');
                info.el.style.setProperty('border-color', borderColor, 'important');
                info.el.style.setProperty('color', textColor, 'important');
                info.el.style.setProperty('display', 'block', 'important');
                info.el.style.setProperty('visibility', 'visible', 'important');
                info.el.style.setProperty('opacity', '1', 'important');

                // Pastikan semua child elements juga memiliki warna yang benar
                const allElements = info.el.querySelectorAll('*');
                allElements.forEach(el => {
                    el.style.setProperty('color', textColor, 'important');
                });

                // Pastikan title ditampilkan di elemen yang benar
                const titleEl = info.el.querySelector('.fc-event-title');
                if (titleEl) {
                    titleEl.textContent = title;
                    titleEl.style.setProperty('color', textColor, 'important');
                    titleEl.style.setProperty('display', 'block', 'important');
                    titleEl.style.setProperty('visibility', 'visible', 'important');
                    titleEl.style.setProperty('opacity', '1', 'important');
                }

                // Pastikan event time terlihat
                const timeEl = info.el.querySelector('.fc-event-time');
                if (timeEl) {
                    timeEl.style.setProperty('color', textColor, 'important');
                }

                // Pastikan event main container terlihat
                const mainEl = info.el.querySelector('.fc-event-main');
                if (mainEl) {
                    mainEl.style.setProperty('color', textColor, 'important');
                }

                // Pastikan event visible dan tidak tersembunyi
                info.el.style.setProperty('display', 'block', 'important');
                info.el.style.setProperty('visibility', 'visible', 'important');
                info.el.style.setProperty('opacity', '1', 'important');
                info.el.style.setProperty('min-height', '20px', 'important');
                info.el.style.setProperty('padding', '2px 4px', 'important');

                // Debug: log event yang di-render
                const computedBg = window.getComputedStyle(info.el).backgroundColor;
                const computedDisplay = window.getComputedStyle(info.el).display;
                const computedVisibility = window.getComputedStyle(info.el).visibility;
                const computedOpacity = window.getComputedStyle(info.el).opacity;

                console.log('Event rendered:', {
                    id: info.event.id,
                    title: title,
                    start: info.event.start,
                    end: info.event.end,
                    backgroundColor: bgColor,
                    borderColor: borderColor,
                    color: info.event.color,
                    eventColor: eventColor,
                    element: info.el,
                    elementVisible: info.el.offsetParent !== null,
                    computedStyle: {
                        backgroundColor: computedBg,
                        display: computedDisplay,
                        visibility: computedVisibility,
                        opacity: computedOpacity
                    },
                    elementClasses: info.el.className,
                    elementDataColor: info.el.getAttribute('data-color')
                });

                // Jika event tidak terlihat, log warning
                if (computedDisplay === 'none' || computedVisibility === 'hidden' || computedOpacity === '0') {
                    console.warn('Event mungkin tidak terlihat:', {
                        id: info.event.id,
                        title: title,
                        display: computedDisplay,
                        visibility: computedVisibility,
                        opacity: computedOpacity
                    });
                }

                // Jika warna tidak sesuai, log warning
                if (computedBg !== bgColor && computedBg !== 'rgba(0, 0, 0, 0)' && computedBg !== 'transparent') {
                    console.warn('Warna event mungkin tidak sesuai:', {
                        id: info.event.id,
                        title: title,
                        expected: bgColor,
                        actual: computedBg
                    });
                }
            }
        });

        calendar.render();

        // Force refresh kalender setelah render (untuk memastikan event ditampilkan)
        setTimeout(function () {
            if (calendar) {
                console.log('Force refreshing calendar after initial render...');
                calendar.refetchEvents();
                console.log('Calendar force refreshed after render');

                // Double check: refresh lagi setelah 1 detik untuk memastikan
                setTimeout(function () {
                    if (calendar) {
                        console.log('Second refresh to ensure events are loaded...');
                        calendar.refetchEvents();
                    }
                }, 1000);
            }
        }, 500);

        // Tambahkan event listener untuk memastikan event di-refresh saat view berubah
        calendar.on('datesSet', function (dateInfo) {
            console.log('Calendar dates changed:', {
                start: dateInfo.start,
                end: dateInfo.end,
                startStr: dateInfo.startStr,
                endStr: dateInfo.endStr
            });
            // Refresh events untuk memastikan semua event ter-load
            setTimeout(function () {
                if (calendar) {
                    calendar.refetchEvents();
                    console.log('Calendar events refreshed after dates change');
                }
            }, 100);
        });

        // Tambahkan event listener untuk memastikan event di-refresh saat view type berubah
        calendar.on('viewDidMount', function (viewInfo) {
            console.log('Calendar view mounted:', {
                type: viewInfo.view.type,
                title: viewInfo.view.title,
                currentStart: viewInfo.view.currentStart,
                currentEnd: viewInfo.view.currentEnd
            });
            // Refresh events untuk memastikan semua event ter-load
            setTimeout(function () {
                if (calendar) {
                    calendar.refetchEvents();
                    console.log('Calendar events refreshed after view mount');
                }
            }, 100);
        });

        // Tambahkan event listener untuk melihat event yang di-render
        calendar.on('eventDidMount', function (info) {
            console.log('FullCalendar eventDidMount callback triggered for event:', {
                id: info.event.id,
                title: info.event.title,
                start: info.event.start,
                end: info.event.end
            });
        });

        // Update calendar title
        function updateCalendarTitle() {
            const titleEl = document.getElementById('calendarTitle');
            const view = calendar.view;
            let title = '';

            if (view.type === 'dayGridMonth') {
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const date = view.currentStart;
                title = monthNames[date.getMonth()] + ' ' + date.getFullYear();
            } else if (view.type === 'timeGridWeek') {
                const start = view.currentStart;
                const end = view.currentEnd;
                const startStr = formatDate(start);
                const endStr = formatDate(end);
                title = startStr + ' - ' + endStr;
            } else if (view.type === 'timeGridDay') {
                title = formatDate(view.currentStart);
            }

            titleEl.textContent = title;
        }

        function formatDate(date) {
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const day = date.getDate();
            const month = monthNames[date.getMonth()];
            const year = date.getFullYear();
            return `${day} ${month} ${year}`;
        }

        function formatDateForUrl(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        updateCalendarTitle();

        // Navigation buttons
        document.getElementById('prevBtn').addEventListener('click', function () {
            calendar.prev();
            updateCalendarTitle();
            updateUrl();
            // Refresh events setelah navigasi
            setTimeout(function () {
                if (calendar) {
                    calendar.refetchEvents();
                    console.log('Calendar events refreshed after prev navigation');
                }
            }, 100);
        });

        document.getElementById('nextBtn').addEventListener('click', function () {
            calendar.next();
            updateCalendarTitle();
            updateUrl();
            // Refresh events setelah navigasi
            setTimeout(function () {
                if (calendar) {
                    calendar.refetchEvents();
                    console.log('Calendar events refreshed after next navigation');
                }
            }, 100);
        });

        // View toggle buttons
        document.querySelectorAll('.calendar-view-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const view = this.dataset.view;
                currentView = view;

                // Update active state
                document.querySelectorAll('.calendar-view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Change calendar view
                let fcView = 'dayGridMonth';
                if (view === 'week') fcView = 'timeGridWeek';
                else if (view === 'day') fcView = 'timeGridDay';

                calendar.changeView(fcView);
                updateCalendarTitle();
                updateUrl();

                // Refresh events setelah view berubah
                setTimeout(function () {
                    if (calendar) {
                        calendar.refetchEvents();
                        console.log('Calendar events refreshed after view change');
                    }
                }, 100);
            });
        });

        // Add Event button - menggunakan SweetAlert2
        document.getElementById('addEventBtn').addEventListener('click', function () {
            showAddEventForm();
        });

        // Fungsi untuk menampilkan form Add Event dengan SweetAlert2
        function showAddEventForm(eventData = null) {
            const isEdit = eventData !== null;
            const now = new Date();
            const startDate = eventData ? new Date(eventData.start) : new Date(now.getTime() - (now.getTimezoneOffset() * 60000));
            const endDate = eventData ? new Date(eventData.end) : new Date(startDate.getTime() + (24 * 60 * 60 * 1000));

            // Format tanggal untuk input datetime-local
            const formatDateTimeLocal = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day}T${hours}:${minutes}`;
            };

            const defaultStartDate = formatDateTimeLocal(startDate);
            const defaultEndDate = formatDateTimeLocal(endDate);
            const defaultTitle = eventData ? eventData.title : '';
            const defaultColor = eventData ? (eventData.extendedProps?.event_color || 'primary') : 'danger';

            // Buat HTML untuk form
            const formHtml = `
            <div class="swal2-form-container">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Event Title <span style="color: #dc3545;">*</span></label>
                <input type="text" id="swal2-event-title" class="swal2-input" 
                       placeholder="Contoh: Rapat Tim, Event Launching, Target Bulanan" 
                       value="${defaultTitle}" maxlength="255" required>
                <small style="display: block; margin-top: 0.25rem; font-size: 0.75rem; color: #6b7280;">
                    Masukkan judul event yang jelas dan deskriptif (maksimal 255 karakter)
                </small>
                
                <label style="display: block; margin: 1.5rem 0 0.5rem 0; font-weight: 500; color: #374151;">Event Color</label>
                <div class="swal2-color-radio-group">
                    <div class="swal2-color-radio">
                        <input type="radio" name="swal2-event-color" id="swal2-color-danger" value="danger" ${defaultColor === 'danger' ? 'checked' : ''}>
                        <div class="swal2-color-preview danger"></div>
                        <span class="swal2-color-label">Danger</span>
                    </div>
                    <div class="swal2-color-radio">
                        <input type="radio" name="swal2-event-color" id="swal2-color-success" value="success" ${defaultColor === 'success' ? 'checked' : ''}>
                        <div class="swal2-color-preview success"></div>
                        <span class="swal2-color-label">Success</span>
                    </div>
                    <div class="swal2-color-radio">
                        <input type="radio" name="swal2-event-color" id="swal2-color-primary" value="primary" ${defaultColor === 'primary' ? 'checked' : ''}>
                        <div class="swal2-color-preview primary"></div>
                        <span class="swal2-color-label">Primary</span>
                    </div>
                    <div class="swal2-color-radio">
                        <input type="radio" name="swal2-event-color" id="swal2-color-warning" value="warning" ${defaultColor === 'warning' ? 'checked' : ''}>
                        <div class="swal2-color-preview warning"></div>
                        <span class="swal2-color-label">Warning</span>
                    </div>
                </div>
                
                <label style="display: block; margin: 1.5rem 0 0.5rem 0; font-weight: 500; color: #374151;">Enter Start Date</label>
                <input type="datetime-local" id="swal2-start-date" class="swal2-input" 
                       value="${defaultStartDate}" required>
                
                <label style="display: block; margin: 1.5rem 0 0.5rem 0; font-weight: 500; color: #374151;">Enter End Date</label>
                <input type="datetime-local" id="swal2-end-date" class="swal2-input" 
                       value="${defaultEndDate}" required>
            </div>
        `;

            Swal.fire({
                title: isEdit ? 'Edit Event' : 'Add Event',
                html: formHtml,
                width: window.innerWidth <= 767 ? '95%' : (window.innerWidth <= 991 ? '85%' : '600px'),
                padding: window.innerWidth <= 767 ? '1rem' : '1.5rem',
                showCancelButton: true,
                confirmButtonText: isEdit ? 'Update Event' : 'Add Event',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                reverseButtons: window.innerWidth <= 767,
                didOpen: () => {
                    // Focus ke input title
                    const titleInput = document.getElementById('swal2-event-title');
                    if (titleInput) {
                        setTimeout(() => {
                            titleInput.focus();
                            titleInput.select();
                        }, 100);
                    }

                    // Tambahkan event listener untuk color radio
                    document.querySelectorAll('input[name="swal2-event-color"]').forEach(radio => {
                        radio.addEventListener('change', function () {
                            document.querySelectorAll('.swal2-color-radio').forEach(div => {
                                div.style.borderColor = '#e5e7eb';
                            });
                            if (this.checked) {
                                this.closest('.swal2-color-radio').style.borderColor = '#007bff';
                            }
                        });
                    });

                    // Set border untuk color yang terpilih
                    const selectedColor = document.querySelector('input[name="swal2-event-color"]:checked');
                    if (selectedColor) {
                        selectedColor.closest('.swal2-color-radio').style.borderColor = '#007bff';
                    }
                },
                preConfirm: () => {
                    const title = document.getElementById('swal2-event-title').value.trim();
                    const startDate = document.getElementById('swal2-start-date').value;
                    const endDate = document.getElementById('swal2-end-date').value;
                    const color = document.querySelector('input[name="swal2-event-color"]:checked')?.value || 'primary';

                    // Validasi
                    if (!title || title === '') {
                        Swal.showValidationMessage('Event Title tidak boleh kosong!');
                        return false;
                    }

                    if (title.length > 255) {
                        Swal.showValidationMessage('Event Title maksimal 255 karakter!');
                        return false;
                    }

                    if (!startDate || !endDate) {
                        Swal.showValidationMessage('Tanggal mulai dan tanggal akhir harus diisi!');
                        return false;
                    }

                    const start = new Date(startDate);
                    const end = new Date(endDate);

                    if (end < start) {
                        Swal.showValidationMessage('Tanggal akhir tidak boleh lebih awal dari tanggal mulai!');
                        return false;
                    }

                    return {
                        title: title,
                        start_date: startDate,
                        end_date: endDate,
                        event_color: color
                    };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    // Submit form
                    submitEventForm(result.value, isEdit ? eventData.id : null);
                }
            });
        }

        // Fungsi untuk submit form event
        function submitEventForm(data, eventId = null) {
            // Tampilkan loading
            Swal.fire({
                title: eventId ? 'Mengupdate...' : 'Menyimpan...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Buat form untuk submit
            const form = document.createElement('form');
            form.method = 'POST';
            // Gunakan base_url untuk menghindari error route parameter
            form.action = eventId ? `<?= base_url('kalender/update') ?>/${eventId}` : '<?= url_to('kalender.store') ?>';

            // Tambahkan CSRF token
            const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]')?.value ||
                document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= csrf_token() ?>';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }

            // Tambahkan field lainnya
            const fields = {
                'event_title': data.title,
                'event_color': data.event_color,
                'start_date': data.start_date,
                'end_date': data.end_date,
                'view_type': currentView,
                'current_date': formatDateForUrl(calendar.view.currentStart)
            };

            Object.keys(fields).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = fields[key];
                form.appendChild(input);
            });

            // Submit form
            document.body.appendChild(form);
            form.submit();
        }

        // Form submit sudah di-handle oleh SweetAlert2, tidak perlu lagi

        // Refresh kalender setelah form berhasil submit
        // Karena menggunakan form submit biasa (bukan AJAX), kalender akan refresh otomatis setelah redirect
        // Tapi kita juga bisa force refresh kalender setelah redirect
        window.addEventListener('load', function () {
            // Refresh kalender setelah halaman dimuat (untuk memastikan event baru ditampilkan)
            if (calendar) {
                calendar.refetchEvents();
                console.log('Calendar refreshed on page load');
            }
        });

        // Refresh kalender setiap kali fokus kembali ke window (untuk memastikan event terbaru ditampilkan)
        window.addEventListener('focus', function () {
            if (calendar) {
                calendar.refetchEvents();
                console.log('Calendar refreshed on window focus');
            }
        });

        // Update URL
        function updateUrl() {
            const date = formatDateForUrl(calendar.view.currentStart);
            const url = new URL(window.location.href);
            url.searchParams.set('view', currentView);
            url.searchParams.set('date', date);
            window.history.pushState({}, '', url);
        }

        // Realtime clock (Waktu Indonesia)
        function updateClock() {
            const now = new Date();
            const options = {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const timeString = now.toLocaleString('id-ID', options);
            document.getElementById('realtimeClock').textContent = timeString;
        }

        updateClock();
        setInterval(updateClock, 1000);

        // Handle window resize untuk responsive
        let resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                calendar.updateSize();
                updateCalendarTitle();
            }, 250);
        });

        // Optimasi untuk touch devices
        if ('ontouchstart' in window) {
            calendarEl.classList.add('fc-touch');
        }

        // Fungsi untuk menampilkan detail event menggunakan SweetAlert2
        function showEventDetail(event) {
            const extendedProps = event.extendedProps || {};
            const userLevel = <?= $level ?>;
            const currentUserId = <?= $userId ?>;
            const eventUserId = extendedProps.user_id || null;

            // Cek apakah user bisa melihat event ini (level 0 hanya lihat event sendiri)
            if (userLevel === 0 && eventUserId && eventUserId != currentUserId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Akses Ditolak',
                    text: 'Anda tidak memiliki akses untuk melihat event ini.',
                    confirmButtonColor: '#4e73df',
                    confirmButtonText: 'OK',
                    width: window.innerWidth <= 767 ? '90%' : '500px'
                });
                return;
            }

            const startDate = new Date(event.start);
            const endDate = new Date(event.end);

            const startDateStr = formatDateTime(startDate);
            const endDateStr = formatDateTime(endDate);

            // Tentukan apakah user bisa edit/delete event ini
            let canEdit = false;
            let canDelete = false;
            const eventUserLevel = extendedProps.user_level ?? null;

            if (userLevel === 0) {
                // Level 0 hanya bisa edit/delete event sendiri
                canEdit = eventUserId == currentUserId;
                canDelete = eventUserId == currentUserId;
            } else if (userLevel === 2) {
                // Level 2 hanya bisa edit/delete event yang dibuat oleh mereka sendiri
                // Tidak bisa edit/delete event yang dibuat oleh user level 0 (hanya bisa lihat)
                canEdit = eventUserId == currentUserId;
                canDelete = eventUserId == currentUserId;
            }

            let detailHtml = `
            <div style="text-align: left;">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Judul Event:</label>
                    <p style="margin: 0; padding: 0.75rem; background: #f3f4f6; border-radius: 0.5rem; color: #111827;">${event.title}</p>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Waktu Mulai:</label>
                    <p style="margin: 0; padding: 0.75rem; background: #f3f4f6; border-radius: 0.5rem; color: #111827;">${startDateStr}</p>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Waktu Selesai:</label>
                    <p style="margin: 0; padding: 0.75rem; background: #f3f4f6; border-radius: 0.5rem; color: #111827;">${endDateStr}</p>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Warna Event:</label>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f3f4f6; border-radius: 0.5rem;">
                        <span class="color-preview" style="background: ${event.backgroundColor}; width: 30px; height: 30px; border-radius: 0.375rem;"></span>
                        <span style="color: #111827; font-weight: 500;">${getColorName(event.backgroundColor)}</span>
                    </div>
                </div>
        `;

            // Tampilkan informasi user yang membuat event (untuk level 2)
            if (userLevel === 2 && (extendedProps.user_name || extendedProps.user_email)) {
                const eventUserLevel = extendedProps.user_level ?? null;
                const isOwnEvent = eventUserId == currentUserId;
                const isLevel0Event = eventUserLevel === 0;

                detailHtml += `
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                    <label style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Dibuat Oleh:</label>
                    <div style="padding: 0.75rem; background: #f3f4f6; border-radius: 0.5rem;">
                        <p style="margin: 0 0 0.5rem 0; color: #111827;"><strong>Nama:</strong> ${extendedProps.user_name || '-'}</p>
                        <p style="margin: 0 0 0.5rem 0; color: #111827;"><strong>Email:</strong> ${extendedProps.user_email || '-'}</p>
                        <p style="margin: 0; color: #111827;"><strong>Level:</strong> ${eventUserLevel === 0 ? 'Level 0 (User)' : (eventUserLevel === 2 ? 'Level 2 (Admin)' : '-')}</p>
                    </div>
                    ${isLevel0Event && !isOwnEvent ? `
                        <div style="margin-top: 0.75rem; padding: 0.75rem; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 0.5rem;">
                            <p style="margin: 0; color: #92400e; font-size: 0.875rem;">
                                <strong>Info:</strong> Event ini dibuat oleh user Level 0. Anda hanya dapat melihat event ini, tidak dapat mengedit atau menghapusnya.
                            </p>
                        </div>
                    ` : ''}
                    ${isOwnEvent ? `
                        <div style="margin-top: 0.75rem; padding: 0.75rem; background: #d1fae5; border-left: 4px solid #10b981; border-radius: 0.5rem;">
                            <p style="margin: 0; color: #065f46; font-size: 0.875rem;">
                                <strong>Info:</strong> Event ini dibuat oleh Anda. Anda dapat mengedit atau menghapus event ini.
                            </p>
                        </div>
                    ` : ''}
                </div>
            `;
            }

            detailHtml += `</div>`;

            // Tentukan tombol yang akan ditampilkan
            const buttons = {
                confirm: {
                    text: 'Close',
                    value: 'close',
                    visible: true,
                    className: 'swal2-cancel',
                    closeModal: true
                }
            };

            if (canEdit || canDelete) {
                buttons.deny = {
                    text: canEdit ? 'Edit' : '',
                    value: 'edit',
                    visible: canEdit,
                    className: 'swal2-confirm',
                    closeModal: false
                };

                buttons.cancel = {
                    text: canDelete ? 'Hapus' : '',
                    value: 'delete',
                    visible: canDelete,
                    className: 'swal2-deny',
                    closeModal: false
                };
            }

            Swal.fire({
                title: 'Detail Event',
                html: detailHtml,
                width: window.innerWidth <= 767 ? '95%' : (window.innerWidth <= 991 ? '85%' : '600px'),
                padding: window.innerWidth <= 767 ? '1rem' : '1.5rem',
                showConfirmButton: true,
                showDenyButton: canEdit,
                showCancelButton: canDelete,
                confirmButtonText: 'Close',
                denyButtonText: canEdit ? 'Edit' : '',
                cancelButtonText: canDelete ? 'Hapus' : '',
                confirmButtonColor: '#6c757d',
                denyButtonColor: '#007bff',
                cancelButtonColor: '#dc3545',
                reverseButtons: window.innerWidth <= 767,
                focusConfirm: false
            }).then((result) => {
                if (result.isDenied && canEdit) {
                    // Edit event
                    showAddEventForm(event);
                } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel && canDelete) {
                    // Delete event
                    Swal.fire({
                        title: 'Hapus Event?',
                        text: 'Apakah Anda yakin ingin menghapus event ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: window.innerWidth <= 767,
                        width: window.innerWidth <= 767 ? '90%' : '500px'
                    }).then((deleteResult) => {
                        if (deleteResult.isConfirmed) {
                            window.location.href = `<?= base_url('kalender/delete') ?>/${event.id}?view=${currentView}&date=${formatDateForUrl(calendar.view.currentStart)}`;
                        }
                    });
                }
            });
        }

        // Fungsi untuk menampilkan semua event di tanggal tertentu
        function showEventsForDate(dateStr) {
            const date = new Date(dateStr + 'T00:00:00');
            const dateFormatted = formatDate(date);
            const userLevel = <?= $level ?>;
            const currentUserId = <?= $userId ?>;

            console.log('Showing events for date:', dateStr, 'Formatted:', dateFormatted);

            // Ambil semua events dari calendar
            const allEvents = calendar.getEvents();
            console.log('All events from calendar:', allEvents);
            console.log('Total events:', allEvents.length);

            // Jika tidak ada event di calendar, fetch dari API untuk tanggal tersebut
            if (allEvents.length === 0) {
                console.log('No events in calendar, fetching from API for date:', dateStr);
                const startDate = dateStr;
                const endDate = dateStr;

                fetch(`<?= url_to('kalender.getEvents') ?>?start=${startDate}&end=${endDate}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Events fetched for clicked date:', data);
                        if (Array.isArray(data) && data.length > 0) {
                            // Render events ke calendar terlebih dahulu
                            data.forEach(eventData => {
                                calendar.addEvent(eventData);
                            });
                            // Tampilkan events
                            displayEventsForDate(dateStr, data, dateFormatted, userLevel, currentUserId);
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'Tidak Ada Event',
                                text: `Tidak ada event pada tanggal ${dateFormatted}`,
                                confirmButtonColor: '#4e73df',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching events for date:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat event untuk tanggal ini.',
                            confirmButtonColor: '#4e73df',
                            confirmButtonText: 'OK'
                        });
                    });
                return;
            }

            const eventsOnDate = allEvents.filter(event => {
                const eventStart = new Date(event.start);
                const eventEnd = new Date(event.end);
                const clickedDate = new Date(dateStr + 'T00:00:00');

                // Normalisasi tanggal ke awal hari untuk perbandingan
                const eventStartDate = new Date(eventStart.getFullYear(), eventStart.getMonth(), eventStart.getDate());
                const eventEndDate = new Date(eventEnd.getFullYear(), eventEnd.getMonth(), eventEnd.getDate());
                const clickedDateOnly = new Date(clickedDate.getFullYear(), clickedDate.getMonth(), clickedDate.getDate());

                // Cek apakah event terjadi pada tanggal yang diklik
                // Event ditampilkan jika: clickedDate berada di antara eventStart dan eventEnd (inclusive)
                const isOnDate = clickedDateOnly >= eventStartDate && clickedDateOnly <= eventEndDate;

                if (!isOnDate) return false;

                // Filter berdasarkan level user
                // Level 0: hanya tampilkan event mereka sendiri
                if (userLevel === 0) {
                    const eventUserId = event.extendedProps?.user_id;
                    return eventUserId == currentUserId;
                }

                // Level 2: tampilkan semua event dari user level 0
                // (Filter sudah dilakukan di backend, jadi di sini hanya return true)
                if (userLevel === 2) {
                    return true;
                }

                // Level lainnya: tidak tampilkan
                return false;
            });

            console.log('Events on date:', eventsOnDate);

            displayEventsForDate(dateStr, eventsOnDate, dateFormatted, userLevel, currentUserId);
        }

        // Fungsi helper untuk menampilkan events di modal
        function displayEventsForDate(dateStr, eventsOnDate, dateFormatted, userLevel, currentUserId) {
            // Pastikan eventsOnDate adalah array
            if (!Array.isArray(eventsOnDate)) {
                eventsOnDate = [];
            }

            if (eventsOnDate.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Ada Event',
                    text: `Tidak ada event pada tanggal ${dateFormatted}`,
                    confirmButtonColor: '#4e73df',
                    confirmButtonText: 'OK',
                    width: window.innerWidth <= 767 ? '90%' : '500px'
                });
                return;
            }

            // Jika hanya 1 event, tampilkan detail langsung
            if (eventsOnDate.length === 1) {
                const event = eventsOnDate[0];
                // Pastikan event adalah FullCalendar event object
                if (event.id) {
                    showEventDetail(event);
                } else {
                    // Jika bukan FullCalendar event, convert dulu
                    const fcEvent = calendar.getEventById(event.id);
                    if (fcEvent) {
                        showEventDetail(fcEvent);
                    } else {
                        showEventDetail(event);
                    }
                }
                return;
            }

            // Jika lebih dari 1 event, tampilkan list menggunakan SweetAlert2
            let listHtml = `
            <div style="text-align: left;">
                <h6 style="margin-bottom: 1rem; font-size: 1rem; font-weight: 600; color: #111827;">Event pada tanggal ${dateFormatted}:</h6>
                <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 400px; overflow-y: auto;">
        `;

            eventsOnDate.forEach((event, index) => {
                const extendedProps = event.extendedProps || {};
                const startTime = formatTime(new Date(event.start));
                const endTime = formatTime(new Date(event.end));
                const eventId = event.id || index;

                listHtml += `
                <div class="event-list-item" 
                     style="padding: 0.875rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s; background: #fff;" 
                     onclick="handleEventClick('${eventId}', ${index})"
                     onmouseover="this.style.background='#f3f4f6'; this.style.borderColor='#007bff';" 
                     onmouseout="this.style.background='#fff'; this.style.borderColor='#e5e7eb';">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <span class="color-preview" style="background: ${event.backgroundColor || event.color || '#007bff'}; width: 20px; height: 20px; border-radius: 0.25rem; flex-shrink: 0;"></span>
                        <div style="flex: 1; min-width: 0;">
                            <strong style="display: block; color: #111827; font-size: 0.875rem; margin-bottom: 0.25rem;">${event.title || 'Event Tanpa Judul'}</strong>
                            <div style="font-size: 0.75rem; color: #6b7280;">
                                ${startTime} - ${endTime}
                            </div>
                            ${extendedProps.user_name && userLevel === 2 ? `
                                <div style="font-size: 0.7rem; color: #9ca3af; margin-top: 0.25rem;">
                                    <strong>Oleh:</strong> ${extendedProps.user_name}${extendedProps.user_email ? ' (' + extendedProps.user_email + ')' : ''}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
            });

            listHtml += `</div></div>`;

            // Simpan eventsOnDate ke window untuk akses di onclick
            window.tempEventsOnDate = eventsOnDate;

            Swal.fire({
                title: `Event pada ${dateFormatted}`,
                html: listHtml,
                width: window.innerWidth <= 767 ? '95%' : (window.innerWidth <= 991 ? '85%' : '600px'),
                padding: window.innerWidth <= 767 ? '1rem' : '1.5rem',
                showConfirmButton: true,
                confirmButtonText: 'Close',
                confirmButtonColor: '#6c757d',
                didOpen: () => {
                    // Tambahkan event listener untuk click
                    document.querySelectorAll('.event-list-item').forEach((item, idx) => {
                        item.addEventListener('click', function () {
                            const event = window.tempEventsOnDate[idx];
                            if (event) {
                                Swal.close();
                                const fcEvent = calendar.getEventById(event.id);
                                if (fcEvent) {
                                    showEventDetail(fcEvent);
                                } else {
                                    showEventDetail(event);
                                }
                            }
                        });
                    });
                }
            });
        }

        // Helper function untuk handle event click
        function handleEventClick(eventId, index) {
            const event = window.tempEventsOnDate[index];
            if (event) {
                Swal.close();
                const fcEvent = calendar.getEventById(event.id);
                if (fcEvent) {
                    showEventDetail(fcEvent);
                } else {
                    showEventDetail(event);
                }
            }
        }

        // Helper functions
        function formatDateTime(date) {
            const options = {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            };
            return date.toLocaleString('id-ID', options);
        }

        function formatTime(date) {
            const options = {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            };
            return date.toLocaleString('id-ID', options);
        }

        function getColorName(colorCode) {
            const colors = {
                '#dc3545': 'Danger',
                '#28a745': 'Success',
                '#007bff': 'Primary',
                '#ffc107': 'Warning'
            };
            return colors[colorCode] || 'Primary';
        }

        // Dark mode untuk event detail
        const darkModeStyles = `
        body.dark-mode .event-detail-item p,
        [data-theme="dark"] .event-detail-item p {
            background: #111827 !important;
            color: #f9fafb;
        }
        
        body.dark-mode .event-list-item,
        [data-theme="dark"] .event-list-item {
            background: #1f2937 !important;
            border-color: #374151 !important;
        }
        
        body.dark-mode .event-list-item:hover,
        [data-theme="dark"] .event-list-item:hover {
            background: #111827 !important;
        }
        
        body.dark-mode .event-detail-item,
        [data-theme="dark"] .event-detail-item {
            border-top-color: #374151 !important;
        }
    `;

        const styleSheet = document.createElement("style");
        styleSheet.textContent = darkModeStyles;
        document.head.appendChild(styleSheet);
    });
</script>
<?= $this->endSection() ?>