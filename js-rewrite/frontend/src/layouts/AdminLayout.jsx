import { Outlet, Link, useNavigate, useLocation } from 'react-router-dom';
import { useState, useEffect, useRef } from 'react';
import NotificationCell from '../components/admin/NotificationCell';

const ALL_MENUS = [
  { key: 'dashboard',    icon: 'bi-speedometer2',       title: 'Dashboard',           path: '/admin/dashboard' },
  { key: 'Daftar',       icon: 'bi-building',            title: 'Daftar',              path: '#',
    submenu: [
      { title: 'Organisasi', path: '/admin/organisasi' },
      { title: 'Fakultas',   path: '/admin/fakultas' },
    ]
  },
  { key: 'E-Voting',     icon: 'bi-clipboard-check',    title: 'E-Voting',            path: '#',
    submenu: [
      { title: 'Kandidat',          path: '/admin/evoting/kandidat' },
      { title: 'E-Voting Waktu',    path: '/admin/evoting/pemilihan' },
      { title: 'Mahasiswa Voting',  path: '/admin/evoting/mahasiswa' },
      { title: 'E-voting Kandidat', path: '/admin/evoting/calon' },
      { title: 'Kepengurusan',      path: '/admin/evoting/kepengurusan' },
    ]
  },
  { key: 'Document',     icon: 'bi-archive-fill',       title: 'Document',            path: '/admin/dokumen',
    submenu: [
      { title: 'AD/ART', path: '/admin/dokumen?kategori=AD/ART' },
      { title: 'PB',     path: '/admin/dokumen?kategori=PB' },
      { title: 'CA',     path: '/admin/dokumen?kategori=CA' },
      { title: 'PRS',    path: '/admin/dokumen?kategori=PRS' },
      { title: 'POA',    path: '/admin/dokumen?kategori=POA' },
      { title: 'KPI',    path: '/admin/dokumen?kategori=KPI' },
    ]
  },
  { key: 'Kalender',     icon: 'bi-calendar-event',     title: 'Kalender',            path: '/admin/kalender' },
  { key: 'E-sertifikat', icon: 'bi-award-fill',         title: 'E-Sertifikat',        path: '/admin/sertifikat' },
  { key: 'Blogger',      icon: 'bi-journal-text',       title: 'Blogger',             path: '/admin/berita' },
  { key: 'Struktur',     icon: 'bi-diagram-3-fill',     title: 'Struktur Organisasi', path: '/admin/struktur' },
  { key: 'Gform',        icon: 'bi-file-earmark-text',  title: 'Gform',               path: '/admin/gform' },
  { key: 'Users',        icon: 'bi-people-fill',        title: 'Users',               path: '/admin/users' },
  { key: 'Keamanan',     icon: 'bi-shield-lock-fill',   title: 'Keamanan',            path: '/admin/security' },
];

const MENU_BY_LEVEL   = { 1: ['dashboard','Daftar','E-Voting','Gform'], 2: ['dashboard','Daftar','Kalender','E-sertifikat','Blogger','Document','Struktur','Gform','Keamanan','Users'], 0: ['dashboard','Document','Kalender','E-sertifikat','Blogger','Struktur','Gform'] };
const MENU_TITLE      = { 1: 'Menu SuperAdmin', 2: 'Menu Admin', 0: 'Menu Ormawa' };
const SIDEBAR_W       = 260;

export default function AdminLayout() {
  const navigate = useNavigate();
  const location = useLocation();
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [openSubmenus, setOpenSubmenus] = useState({});
  const [isDark, setIsDark] = useState(false);
  const [userDropdown, setUserDropdown] = useState(false);
  const userRef = useRef(null);

  const user = (() => { try { return JSON.parse(localStorage.getItem('user') || '{}'); } catch { return {}; } })();
  const level = user.level ?? 2;
  const allowedKeys = MENU_BY_LEVEL[level] ?? MENU_BY_LEVEL[2];
  const menuTitle = MENU_TITLE[level] ?? 'Menu Admin';
  const profilePhoto = user?.profile_photo || null;

  const isMobile = () => window.innerWidth < 992;

  useEffect(() => {
    document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
  }, [isDark]);

  useEffect(() => {
    setSidebarOpen(!isMobile());
    const handle = () => { if (isMobile()) setSidebarOpen(false); else setSidebarOpen(true); };
    window.addEventListener('resize', handle);
    return () => window.removeEventListener('resize', handle);
  }, []);

  useEffect(() => { if (isMobile()) setSidebarOpen(false); }, [location.pathname]);

  useEffect(() => {
    const h = (e) => { if (userRef.current && !userRef.current.contains(e.target)) setUserDropdown(false); };
    document.addEventListener('mousedown', h);
    return () => document.removeEventListener('mousedown', h);
  }, []);

  const handleLogout = () => { localStorage.removeItem('token'); localStorage.removeItem('user'); navigate('/login'); };
  const isActive = (path) => path !== '#' && location.pathname.startsWith(path);
  const toggleSub = (key) => setOpenSubmenus(prev => ({ ...prev, [key]: !prev[key] }));
  const filteredMenus = ALL_MENUS.filter(m => allowedKeys.includes(m.key));

  const showSubmenu = (menu) => {
    if (!menu.submenu) return false;
    if (menu.key === 'E-Voting') return level === 1;
    if (menu.key === 'Document') return true;
    return [1, 2].includes(level);
  };

  return (
    <>
      <link rel="stylesheet" href="/dist/assets/extensions/simple-datatables/style.css" />
      <link rel="stylesheet" href="/dist/assets/compiled/css/table-datatable.css" />
      <link rel="stylesheet" href="/dist/assets/compiled/css/app.css" />
      <link rel="stylesheet" href="/dist/assets/compiled/css/app-dark.css" />
      <link rel="stylesheet" href="/dist/assets/compiled/css/iconly.css" />

      <style>{`
        /* === LAYOUT === */
        #admin-root { display: flex; height: 100vh; overflow: hidden; background: #f1f5f9; }
        /* Sidebar */
        #admin-sidebar {
          width: ${SIDEBAR_W}px;
          min-width: ${SIDEBAR_W}px;
          height: 100vh;
          overflow-y: auto;
          overflow-x: hidden;
          flex-shrink: 0;
          background: #fff;
          border-right: 1px solid #e8ecf0;
          display: flex;
          flex-direction: column;
          transition: transform 0.3s ease, width 0.3s ease;
          z-index: 100;
          scrollbar-width: thin;
          scrollbar-color: #e2e8f0 transparent;
        }
        /* Main area */
        #admin-main {
          flex: 1;
          display: flex;
          flex-direction: column;
          height: 100vh;
          overflow: hidden;
        }
        /* Topbar */
        #admin-topbar {
          height: 60px;
          min-height: 60px;
          background: #fff;
          border-bottom: 1px solid #e8ecf0;
          display: flex;
          align-items: center;
          padding: 0 1.5rem;
          gap: 1rem;
          position: sticky;
          top: 0;
          z-index: 50;
          box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }
        /* Page content */
        #admin-content {
          flex: 1;
          overflow-y: auto;
          padding: 1.5rem;
          scrollbar-width: thin;
        }
        /* Footer */
        #admin-footer {
          background: #fff;
          border-top: 1px solid #e8ecf0;
          padding: 0.75rem 1.5rem;
          text-align: center;
          font-size: .8rem;
          color: #94a3b8;
          flex-shrink: 0;
        }
        /* Mobile overlay */
        #admin-overlay {
          display: none;
          position: fixed;
          inset: 0;
          background: rgba(15,23,42,.5);
          z-index: 99;
          backdrop-filter: blur(2px);
        }
        #admin-overlay.visible { display: block; }

        /* === SIDEBAR MOBILE === */
        @media (max-width: 991.98px) {
          #admin-sidebar {
            position: fixed;
            left: 0; top: 0;
            transform: translateX(-100%);
            box-shadow: 4px 0 20px rgba(0,0,0,.15);
          }
          #admin-sidebar.open { transform: translateX(0); }
          #admin-main { width: 100%; }
        }
        @media (min-width: 992px) {
          #admin-sidebar { position: relative; transform: none !important; }
        }

        /* === SIDEBAR STYLES === */
        .sb-logo-wrap { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
        .sb-logo-link { display: flex; align-items: center; gap: .75rem; text-decoration: none; }
        .sb-logo-img { width: 40px; height: 40px; object-fit: contain; }
        .sb-logo-text { font-size: 1.1rem; font-weight: 700; color: #A01D1D; letter-spacing: -.3px; }
        .sb-dark-row { display: flex; align-items: center; gap: .5rem; padding: .6rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
        .sb-dark-label { font-size: .75rem; color: #94a3b8; flex: 1; }
        .sb-section-title { font-size: .7rem; font-weight: 700; color: #94a3b8; letter-spacing: 1px; text-transform: uppercase; padding: 1rem 1.5rem .4rem; }
        .sb-org-badge { margin: .5rem 1rem; padding: .5rem .75rem; background: rgba(160,29,29,.08); border-left: 3px solid #A01D1D; border-radius: .375rem; font-size: .8rem; font-weight: 600; color: #A01D1D; display: flex; align-items: center; gap: .5rem; }

        /* Menu items */
        .sb-item { display: flex; align-items: center; gap: .75rem; padding: .6rem 1.25rem .6rem 1.5rem; margin: .125rem .75rem; border-radius: .5rem; text-decoration: none; color: #475569; font-size: .875rem; font-weight: 500; cursor: pointer; transition: all .2s; border: none; background: transparent; width: calc(100% - 1.5rem); text-align: left; }
        .sb-item i { font-size: 1rem; width: 1.25rem; flex-shrink: 0; }
        .sb-item span { flex: 1; }
        .sb-item:hover { background: #f8fafc; color: #1e293b; }
        .sb-item.active { background: #A01D1D; color: #fff; box-shadow: 0 4px 12px rgba(160,29,29,.25); }
        .sb-item.active i, .sb-item.active span { color: #fff; }
        .sb-item.parent-active { background: rgba(160,29,29,.08); color: #A01D1D; }
        .sb-item.parent-active i, .sb-item.parent-active span { color: #A01D1D; }
        .sb-chevron { font-size: .7rem; color: #cbd5e1; transition: transform .2s; }
        .sb-chevron.open { transform: rotate(180deg); }

        /* Submenu */
        .sb-submenu { padding: .25rem 0; }
        .sb-sub-item { display: flex; align-items: center; gap: .5rem; padding: .45rem 1.5rem .45rem 3.5rem; text-decoration: none; color: #64748b; font-size: .825rem; transition: all .2s; }
        .sb-sub-item::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: #cbd5e1; flex-shrink: 0; transition: background .2s; }
        .sb-sub-item:hover { color: #A01D1D; }
        .sb-sub-item:hover::before { background: #A01D1D; }
        .sb-sub-item.active { color: #A01D1D; font-weight: 600; }
        .sb-sub-item.active::before { background: #A01D1D; }

        /* Logout */
        .sb-logout { margin: auto 1rem 1rem; display: flex; align-items: center; gap: .75rem; padding: .625rem 1rem; border-radius: .5rem; border: 1px solid #fee2e2; color: #dc2626; font-size: .875rem; font-weight: 500; background: #fff5f5; cursor: pointer; transition: all .2s; text-decoration: none; }
        .sb-logout:hover { background: #fef2f2; border-color: #fca5a5; }
        .sb-logout i { font-size: 1rem; }

        /* === TOPBAR STYLES === */
        .tb-burger { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: .5rem; border: none; background: transparent; color: #64748b; cursor: pointer; transition: all .2s; flex-shrink: 0; }
        .tb-burger:hover { background: #f1f5f9; color: #1e293b; }
        .tb-burger i { font-size: 1.25rem; }
        /* Hide hamburger on desktop */
        @media (min-width: 992px) { .tb-burger { display: none !important; } }
        .tb-search { display: flex; align-items: center; gap: .5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: .5rem; padding: .375rem .75rem; flex: 1; max-width: 320px; transition: all .2s; }
        .tb-search:focus-within { border-color: #A01D1D; background: #fff; box-shadow: 0 0 0 3px rgba(160,29,29,.06); }
        .tb-search i { color: #94a3b8; font-size: .9rem; flex-shrink: 0; }
        .tb-search input { border: none; background: transparent; outline: none; font-size: .875rem; color: #1e293b; flex: 1; min-width: 0; }
        .tb-search input::placeholder { color: #94a3b8; }
        .tb-search .tb-kbd { font-size: .7rem; color: #94a3b8; background: #e2e8f0; border-radius: .25rem; padding: .1rem .35rem; white-space: nowrap; flex-shrink: 0; }
        .tb-right { display: flex; align-items: center; gap: .5rem; margin-left: auto; }
        .tb-icon-btn { width: 36px; height: 36px; border-radius: .5rem; display: flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; cursor: pointer; transition: all .2s; position: relative; text-decoration: none; }
        .tb-icon-btn:hover { background: #f1f5f9; color: #1e293b; }
        .tb-icon-btn i { font-size: 1rem; }
        .tb-notif-dot { position: absolute; top: 6px; right: 6px; width: 7px; height: 7px; background: #f97316; border-radius: 50%; border: 2px solid #fff; }
        .tb-user-btn { display: flex; align-items: center; gap: .6rem; padding: .375rem .6rem; border-radius: .5rem; border: 1px solid #e2e8f0; background: #f8fafc; cursor: pointer; transition: all .2s; color: #1e293b; }
        .tb-user-btn:hover { background: #f1f5f9; }
        .tb-avatar { width: 30px; height: 30px; border-radius: 50%; background: #ffe4e6; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
        .tb-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .tb-avatar i { font-size: 1rem; color: #A01D1D; }
        .tb-uname { font-size: .8rem; font-weight: 600; color: #1e293b; }
        .tb-chevron { font-size: .65rem; color: #94a3b8; }

        /* User dropdown */
        .tb-user-dropdown { position: absolute; top: calc(100% + 8px); right: 0; background: #fff; border: 1px solid #e2e8f0; border-radius: .75rem; box-shadow: 0 10px 30px rgba(0,0,0,.12); min-width: 200px; z-index: 200; overflow: hidden; }
        .tb-dropdown-header { padding: .875rem 1rem; background: linear-gradient(135deg, #A01D1D 0%, #c0392b 100%); }
        .tb-dropdown-header .uname { font-size: .875rem; font-weight: 700; color: #fff; }
        .tb-dropdown-header .uemail { font-size: .75rem; color: rgba(255,255,255,.7); margin-top: 2px; }
        .tb-divider { height: 1px; background: #f1f5f9; margin: .25rem 0; }
        .tb-drop-item { display: flex; align-items: center; gap: .625rem; padding: .6rem 1rem; font-size: .85rem; color: #475569; text-decoration: none; transition: background .15s; }
        .tb-drop-item:hover { background: #f8fafc; color: #1e293b; }
        .tb-drop-item i { width: 1.1rem; font-size: .9rem; color: #94a3b8; }
        .tb-drop-item.danger { color: #dc2626; }
        .tb-drop-item.danger i { color: #dc2626; }
        .tb-drop-item.danger:hover { background: #fff5f5; }
        .tb-user-wrap { position: relative; }

        /* Responsive */
        @media (max-width: 575.98px) { .tb-search { display: none; } }
        @media (max-width: 767.98px) { .tb-uname { display: none; } }

        /* === NOTIFICATION CELL CSS (matches CI4 navbar.php) === */
        .icon-btn-wrapper { position: relative; display: inline-flex; }
        .icon-btn { width: 38px; height: 38px; border-radius: .5rem; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; color: #64748b; text-decoration: none; cursor: pointer; transition: all .2s; }
        .icon-btn:hover { background: #f1f5f9; color: #1e293b; }
        .icon-btn i { font-size: 1rem; }
        .notification-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: #f97316; border: 2px solid #fff; border-radius: 50%; }
        .notification-dropdown { border-radius: .75rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,.12) !important; }
        .bg-brown { background: #fff !important; }

        /* === DARK MODE (matches CI4 app-dark.css) === */
        [data-bs-theme='dark'] #admin-root { background: #1a1f2e; }
        [data-bs-theme='dark'] #admin-sidebar { background: #222736; border-right-color: #2d3548; }
        [data-bs-theme='dark'] .sb-logo-wrap { border-bottom-color: #2d3548; }
        [data-bs-theme='dark'] .sb-dark-row { background: #1e2433; border-bottom-color: #2d3548; }
        [data-bs-theme='dark'] .sb-dark-label { color: #6b7a99; }
        [data-bs-theme='dark'] .sb-section-title { color: #6b7a99; }
        [data-bs-theme='dark'] .sb-item { color: #8892a4; }
        [data-bs-theme='dark'] .sb-item:hover { background: #2a3146; color: #c8d3e6; }
        [data-bs-theme='dark'] .sb-item.active { background: #A01D1D; color: #fff; }
        [data-bs-theme='dark'] .sb-item.parent-active { background: rgba(160,29,29,.2); color: #e88; }
        [data-bs-theme='dark'] .sb-sub-item { color: #6b7a99; }
        [data-bs-theme='dark'] .sb-sub-item:hover { color: #e88; }
        [data-bs-theme='dark'] .sb-sub-item.active { color: #e88; }
        [data-bs-theme='dark'] .sb-sub-item::before { background: #3d4a63; }
        [data-bs-theme='dark'] #admin-topbar { background: #222736; border-bottom-color: #2d3548; }
        [data-bs-theme='dark'] .tb-search { background: #1e2433; border-color: #2d3548; }
        [data-bs-theme='dark'] .tb-search input { color: #c8d3e6; }
        [data-bs-theme='dark'] .tb-search input::placeholder { color: #4a5568; }
        [data-bs-theme='dark'] .tb-search .tb-kbd { background: #2d3548; border-color: #3d4a63; color: #6b7a99; }
        [data-bs-theme='dark'] .tb-icon-btn, [data-bs-theme='dark'] .icon-btn { background: #1e2433; border-color: #2d3548; color: #8892a4; }
        [data-bs-theme='dark'] .tb-icon-btn:hover, [data-bs-theme='dark'] .icon-btn:hover { background: #2a3146; color: #c8d3e6; }
        [data-bs-theme='dark'] .tb-user-btn { background: #1e2433; border-color: #2d3548; color: #c8d3e6; }
        [data-bs-theme='dark'] .tb-uname { color: #c8d3e6; }
        [data-bs-theme='dark'] .tb-user-dropdown, [data-bs-theme='dark'] .notification-dropdown, [data-bs-theme='dark'] .bg-brown { background: #222736 !important; border-color: #2d3548 !important; }
        [data-bs-theme='dark'] .tb-drop-item { color: #8892a4; }
        [data-bs-theme='dark'] .tb-drop-item:hover { background: #2a3146; color: #c8d3e6; }
        [data-bs-theme='dark'] .tb-divider { background: #2d3548; }
        [data-bs-theme='dark'] #admin-content { background: #1a1f2e; }
        [data-bs-theme='dark'] #admin-footer { background: #222736; border-top-color: #2d3548; color: #4a5568; }
        [data-bs-theme='dark'] .card { background: #222736 !important; border-color: #2d3548 !important; color: #c8d3e6 !important; }
        [data-bs-theme='dark'] .card h6, [data-bs-theme='dark'] .card-title { color: #c8d3e6 !important; }
        [data-bs-theme='dark'] .card-widget { background: #222736 !important; border-color: #2d3548 !important; }
        [data-bs-theme='dark'] .card-widget .card-header { border-bottom-color: #2d3548; color: #c8d3e6; }
        [data-bs-theme='dark'] .dropdown-item { color: #8892a4; }
        [data-bs-theme='dark'] .dropdown-item:hover { background: #2a3146; color: #c8d3e6; }
        [data-bs-theme='dark'] .dropdown-header { color: #6b7a99; }
        [data-bs-theme='dark'] .notification-badge { border-color: #222736; }
        [data-bs-theme='dark'] .page-heading h3 { color: #c8d3e6; }
        [data-bs-theme='dark'] .page-heading p { color: #6b7a99; }
        [data-bs-theme='dark'] .text-muted { color: #6b7a99 !important; }
        [data-bs-theme='dark'] .widget-list-item { border-bottom-color: #2d3548; }
        [data-bs-theme='dark'] .badge.bg-light { background: #2a3146 !important; color: #8892a4 !important; }
        [data-bs-theme='dark'] .btn-light { background: #2a3146 !important; border-color: #3d4a63 !important; color: #8892a4 !important; }
        [data-bs-theme='dark'] .btn-light:hover { background: #323d55 !important; color: #c8d3e6 !important; }
        [data-bs-theme='dark'] .btn-sm.btn-light { background: #2a3146 !important; border-color: #3d4a63 !important; color: #8892a4 !important; }
        [data-bs-theme='dark'] .table { color: #8892a4 !important; border-color: #2d3548 !important; }
        [data-bs-theme='dark'] .table-hover > tbody > tr:hover > * { color: #c8d3e6; background-color: #2a3146; }
        [data-bs-theme='dark'] .sb-org-badge { background: rgba(160,29,29,.15); }

        /* Card-widget dark mode — more comprehensive */
        [data-bs-theme='dark'] .card-widget {
          background: #222736 !important;
          border: 1px solid #2d3548 !important;
          border-top: 2px solid #3d4a63 !important;
          box-shadow: 0 2px 8px rgba(0,0,0,.25) !important;
        }
        [data-bs-theme='dark'] .card-widget .card-header {
          border-bottom: 1px solid #2d3548 !important;
          background: transparent !important;
          color: #c8d3e6 !important;
        }
        [data-bs-theme='dark'] .card-widget .card-header h5,
        [data-bs-theme='dark'] .card-widget .card-header .card-title {
          color: #c8d3e6 !important;
        }
        [data-bs-theme='dark'] .card-widget .card-body {
          color: #8892a4 !important;
        }
        [data-bs-theme='dark'] .card-widget .card-body h6 { color: #c8d3e6 !important; }
        [data-bs-theme='dark'] .card-widget .card-body .text-dark { color: #c8d3e6 !important; }
        [data-bs-theme='dark'] .widget-icon { background: #2a3146 !important; }
        [data-bs-theme='dark'] .text-center.py-4.text-muted { color: #4a5568 !important; }
        [data-bs-theme='dark'] .card { background: #222736 !important; border-color: #2d3548 !important; }
        [data-bs-theme='dark'] .card .card-body { background: transparent !important; }
        [data-bs-theme='dark'] .card h6 { color: #c8d3e6 !important; }
        [data-bs-theme='dark'] .card .font-extrabold { color: #fff !important; }
        [data-bs-theme='dark'] .stats-icon { opacity: .9; }
        [data-bs-theme='dark'] .badge.bg-light.text-secondary { background: #2a3146 !important; color: #6b7a99 !important; border-radius: 999px; }
      `}</style>

      <div id="admin-root">
        {/* Mobile Overlay */}
        <div id="admin-overlay" className={sidebarOpen && isMobile() ? 'visible' : ''}
          onClick={() => setSidebarOpen(false)} />

        {/* ======= SIDEBAR ======= */}
        <aside id="admin-sidebar" className={sidebarOpen ? 'open' : ''}>
          {/* Logo */}
          <div className="sb-logo-wrap">
            <Link to="/" className="sb-logo-link">
              <img src="/dist/landing/assets/img/logo1.png" alt="OrmaOne" className="sb-logo-img" />
              <span className="sb-logo-text">OrmaOne</span>
            </Link>
          </div>

          {/* Dark Mode Toggle */}
          <div className="sb-dark-row">
            <i className="bi bi-sun" style={{ color: '#f59e0b', fontSize: '.85rem' }}></i>
            <div className="form-check form-switch mb-0 mx-1" style={{ lineHeight: 1 }}>
              <input className="form-check-input" type="checkbox" id="toggle-dark"
                checked={isDark} onChange={() => setIsDark(!isDark)} style={{ cursor: 'pointer', width: '2rem', height: '1rem' }} />
            </div>
            <i className="bi bi-moon-stars-fill" style={{ color: '#6366f1', fontSize: '.85rem' }}></i>
            <span className="sb-dark-label ms-1">{isDark ? 'Dark' : 'Light'}</span>
          </div>

          {/* Menu */}
          <div style={{ flex: 1, overflowY: 'auto', padding: '0.5rem 0 1rem' }}>
            <div className="sb-section-title">{menuTitle}</div>

            {user.organisasi_name && (
              <div className="sb-org-badge">
                <i className="bi bi-building"></i>
                <span>{user.organisasi_name}</span>
              </div>
            )}

            {filteredMenus.map(menu => {
              const active = isActive(menu.path);
              const hasSub = showSubmenu(menu);
              const subOpen = openSubmenus[menu.key] || active;
              const parentActive = hasSub && menu.submenu?.some(s => location.pathname === s.path.split('?')[0]);

              return (
                <div key={menu.key}>
                  {hasSub ? (
                    <button className={`sb-item${parentActive ? ' parent-active' : ''}`}
                      onClick={() => toggleSub(menu.key)}>
                      <i className={`bi ${menu.icon}`}></i>
                      <span>{menu.title}</span>
                      <i className={`bi bi-chevron-down sb-chevron${subOpen || openSubmenus[menu.key] ? ' open' : ''}`}></i>
                    </button>
                  ) : (
                    <Link to={menu.path} className={`sb-item${active ? ' active' : ''}`}>
                      <i className={`bi ${menu.icon}`}></i>
                      <span>{menu.title}</span>
                    </Link>
                  )}
                  {hasSub && (subOpen || openSubmenus[menu.key]) && (
                    <div className="sb-submenu">
                      {menu.submenu.map(sub => (
                        <Link key={sub.path} to={sub.path}
                          className={`sb-sub-item${location.pathname === sub.path.split('?')[0] ? ' active' : ''}`}>
                          {sub.title}
                        </Link>
                      ))}
                    </div>
                  )}
                </div>
              );
            })}
          </div>

          {/* Logout — styled as normal sidebar-link like CI4 */}
          <Link to="#" className="sb-item" style={{ color: '#dc2626', marginTop: 'auto', borderTop: '1px solid #f1f5f9' }}
            onClick={(e) => { e.preventDefault(); handleLogout(); }}>
            <i className="bi bi-box-arrow-right" style={{ color: '#dc2626' }}></i>
            <span>Logout</span>
          </Link>
        </aside>

        {/* ======= MAIN ======= */}
        <div id="admin-main">
          {/* TOPBAR */}
          <header id="admin-topbar">
            {/* Burger - visible on mobile, hidden on large desktop */}
            <button className="tb-burger" onClick={() => setSidebarOpen(!sidebarOpen)} aria-label="Toggle Sidebar">
              <i className={`bi ${sidebarOpen && !isMobile() ? 'bi-layout-sidebar' : 'bi-list'}`}></i>
            </button>

            {/* Search */}
            <div className="tb-search">
              <i className="bi bi-search"></i>
              <input type="text" placeholder="Cari sesuatu..." />
              <span className="tb-kbd">Ctrl K</span>
            </div>

            {/* Right actions */}
            <div className="tb-right">
              {/* Aspirasi Mahasiswa Chat */}
              <div className="tb-chat-wrap" style={{ position: 'relative' }}>
                <button className="tb-icon-btn" title="Aspirasi Mahasiswa"
                  onClick={(e) => { e.currentTarget.nextSibling.classList.toggle('show'); }}>
                  <i className="bi bi-chat-dots-fill" style={{ color: '#6366f1' }}></i>
                </button>
                <div className="dropdown-menu dropdown-menu-end shadow" style={{ minWidth: '180px', position: 'absolute', right: 0, top: 'calc(100% + 8px)', zIndex: 200 }}>
                  <Link className="dropdown-item" to="/admin/pesan">
                    <i className="bi bi-chat-dots me-2"></i>Aspirasi Mahasiswa
                  </Link>
                </div>
              </div>

              {/* Notification Bell */}
              <NotificationCell />

              {/* User dropdown */}
              <div className="tb-user-wrap" ref={userRef}>
                <button className="tb-user-btn" onClick={() => setUserDropdown(!userDropdown)}>
                  <div className="tb-avatar">
                    {profilePhoto
                      ? <img src={`http://localhost:3001/uploads/profile/${profilePhoto}`} alt="Profile" />
                      : <i className="bi bi-person-fill"></i>
                    }
                  </div>
                  <span className="tb-uname">{user.name || 'Admin'}</span>
                  <i className={`bi bi-chevron-down tb-chevron${userDropdown ? ' rotate-180' : ''}`}
                    style={{ transform: userDropdown ? 'rotate(180deg)' : '', transition: 'transform .2s' }}></i>
                </button>

                {userDropdown && (
                  <div className="tb-user-dropdown">
                    <div className="tb-dropdown-header">
                      <div className="uname">{user.name || 'Admin'}</div>
                      <div className="uemail">{user.email || ''}</div>
                    </div>
                    <div className="tb-divider"></div>
                    <Link to="/admin/profile" onClick={() => setUserDropdown(false)} className="tb-drop-item"><i className="bi bi-person"></i>Profile</Link>
                    <Link to="/admin/profile" onClick={() => setUserDropdown(false)} className="tb-drop-item"><i className="bi bi-gear"></i>Pengaturan</Link>
                    <div className="tb-divider"></div>
                    <button className="tb-drop-item danger w-100 border-0 bg-transparent text-start" onClick={handleLogout}>
                      <i className="bi bi-box-arrow-right"></i>Logout
                    </button>
                  </div>
                )}
              </div>
            </div>
          </header>

          {/* PAGE CONTENT */}
          <main id="admin-content">
            <Outlet context={{ isSidebarOpen: sidebarOpen, setIsSidebarOpen: setSidebarOpen }} />
          </main>

          {/* FOOTER matching CI4 footer.php */}
          <footer id="admin-footer">
            Copyright &copy; ormaone 2025
          </footer>
        </div>
      </div>
    </>
  );
}
