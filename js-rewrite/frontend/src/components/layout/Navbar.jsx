import { useState, useEffect, useRef } from 'react';
import { Link, useLocation } from 'react-router-dom';

export default function Navbar() {
  const [mobileNavActive, setMobileNavActive] = useState(false);
  const [organisasiOpen, setOrganisasiOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const location = useLocation();
  const isHome = location.pathname === '/';

  // Organisasi list (dummy - nanti dari API)
  const organisasiList = [
    { id: 1, name: 'HUSC (Horizon University Student Council)' },
    { id: 2, name: 'FSC (Faculty Student Council)' },
    { id: 3, name: 'PR (Public Relations)' },
    { id: 4, name: 'BEM STIKES' },
  ];

  useEffect(() => {
    const handleScroll = () => {
      if (window.scrollY > 10) {
        document.body.classList.add('scrolled');
        setIsScrolled(true);
      } else {
        document.body.classList.remove('scrolled');
        setIsScrolled(false);
      }
    };
    window.addEventListener('scroll', handleScroll);
    handleScroll();
    return () => {
      window.removeEventListener('scroll', handleScroll);
      document.body.classList.remove('scrolled');
    };
  }, []);

  // Close mobile nav on route change
  useEffect(() => {
    setMobileNavActive(false);
    setOrganisasiOpen(false);
  }, [location.pathname]);

  // Add index-page class to body on all public pages (CI4 always has body.index-page on landing layout)
  useEffect(() => {
    document.body.classList.add('index-page');
    return () => document.body.classList.remove('index-page');
  }, []);

  // Handle body class for mobile-nav-active (matches CI4 JS behavior)
  useEffect(() => {
    if (mobileNavActive) {
      document.body.classList.add('mobile-nav-active');
    } else {
      document.body.classList.remove('mobile-nav-active');
    }
    return () => document.body.classList.remove('mobile-nav-active');
  }, [mobileNavActive]);

  // Active link helper (matches CI4 set_active() logic)
  const isActive = (path) => location.pathname === path || (path !== '/' && location.pathname.startsWith(path));

  const toggleMobileNav = () => setMobileNavActive(!mobileNavActive);

  return (
    <header id="header" className={`header d-flex align-items-center fixed-top${isScrolled || !isHome ? ' scrolled' : ''}`}>
      <div className="container-fluid container-xl position-relative d-flex align-items-center">

        {/* Logo - matches CI4 exactly */}
        <Link to="/" className="logo d-flex align-items-center me-auto">
          <img src="/dist/landing/assets/img/logo1.png" alt="OrmaOne Logo" width="40" height="40" loading="eager" decoding="async" />
          <h1 className="sitename" style={{ color: '#A01D1D' }}>OrmaOne</h1>
        </Link>

        {/* Nav Menu - matches CI4 navmenu + navigation.php structure */}
        <nav id="navmenu" className="navmenu">
          <ul>
            <li>
              <Link to="/" className={isActive('/') && location.pathname === '/' ? 'active' : ''}>
                Beranda
              </Link>
            </li>
            <li>
              <Link to="/berita" className={isActive('/berita') ? 'active' : ''}>
                Berita
              </Link>
            </li>
            <li>
              <Link to="/sertifikat" className={isActive('/sertifikat') ? 'active' : ''}>
                E-Sertifikat
              </Link>
            </li>
            <li>
              <Link to="/voting" className={isActive('/voting') ? 'active' : ''}>
                E-Voting
              </Link>
            </li>
            {/* Dropdown Organisasi - matches CI4 .dropdown exactly */}
            <li className="dropdown">
              <a
                href="#"
                className={isActive('/struktur') ? 'active organisasi-link' : 'organisasi-link'}
                onClick={(e) => { e.preventDefault(); setOrganisasiOpen(!organisasiOpen); }}
              >
                <span>Organisasi</span>
                <i className={`bi bi-chevron-down toggle-dropdown mobile-only${organisasiOpen ? ' active' : ''}`} id="organisasi-toggle"></i>
              </a>
              <ul id="organisasi-dropdown" className={`mobile-dropdown${organisasiOpen ? ' show' : ''}`}>
                {organisasiList.map(org => (
                  <li key={org.id}>
                    <Link to={`/struktur?org=${org.id}`}>{org.name}</Link>
                  </li>
                ))}
              </ul>
            </li>
          </ul>
        </nav>

        {/* Login Button - matches CI4 .btn-getstarted exactly */}
        <Link className="btn-getstarted" to="/login">Login</Link>

        {/* Mobile Nav Toggle - matches CI4 i.mobile-nav-toggle */}
        <i
          className={`mobile-nav-toggle d-xl-none bi ${mobileNavActive ? 'bi-x' : 'bi-list'}`}
          style={{ cursor: 'pointer !important', zIndex: 99999, position: 'relative', pointerEvents: 'auto' }}
          onClick={toggleMobileNav}
        ></i>

      </div>

      {/* Inline CSS from navigation.php */}
      <style>{`
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
          .mobile-dropdown {
            display: none;
          }
          .mobile-dropdown.show {
            display: block;
          }
          .organisasi-link {
            position: relative;
          }
        }

        /* Desktop tetap hover */
        @media (min-width: 992px) {
          .mobile-dropdown { display: none; }
          .dropdown:hover .mobile-dropdown { display: block; }
        }

        /* Scrolled state shadow - body.scrolled .header (CI4 pattern) */
        body.scrolled .header {
          box-shadow: 0px 0 18px rgba(0, 0, 0, 0.1) !important;
          background-color: #ffffff !important;
        }

        /* NOT scrolled: remove ALL borders, shadows, backgrounds from header */
        body:not(.scrolled) .header {
          background-color: transparent !important;
          box-shadow: none !important;
          border: none !important;
          border-bottom: none !important;
          -webkit-box-shadow: none !important;
        }

        /* Bootstrap override: fixed-top does not have its own shadow */
        .header.fixed-top {
          border-bottom: none !important;
          border: none !important;
        }
      `}</style>
    </header>
  );
}
