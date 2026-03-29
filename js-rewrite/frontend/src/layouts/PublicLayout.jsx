import { useState, useEffect } from 'react';
import { Link, Outlet, useLocation, useNavigate } from 'react-router-dom';
import axios from 'axios';

// Sort organisasi like CI4: HUSC -> FSC -> PR -> others
function getPriority(name) {
  const n = name.toUpperCase().trim();
  if (n.startsWith('HUSC')) return 1;
  if (n.startsWith('FSC')) return 2;
  if (n.startsWith('PR')) return 3;
  return 4;
}

export default function PublicLayout() {
  const [loading, setLoading] = useState(true);
  const [scrolled, setScrolled] = useState(false);
  const [mobileNavActive, setMobileNavActive] = useState(false);
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [organisasis, setOrganisasis] = useState([]);
  const location = useLocation();
  const navigate = useNavigate();

  // Fetch organisasi list for navbar dropdown
  useEffect(() => {
    axios.get('http://localhost:3001/api/public/organisasi')
      .then(res => {
        if (res.data.success && Array.isArray(res.data.data)) {
          const sorted = [...res.data.data].sort((a, b) => {
            const pa = getPriority(a.name);
            const pb = getPriority(b.name);
            if (pa !== pb) return pa - pb;
            return a.name.localeCompare(b.name);
          });
          setOrganisasis(sorted);
        }
      })
      .catch(() => {});
  }, []);

  // Handle loading screen
  useEffect(() => {
    const timer = setTimeout(() => {
      setLoading(false);
    }, 1000);
    return () => clearTimeout(timer);
  }, [location.pathname]); // Show loader briefly on route change, or only once? Only once is better, but this mimics CI4 fadeOut

  // Handle scroll for header and scroll-top button
  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 50);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  // Google Translate injection
  useEffect(() => {
    window.googleTranslateElementInit = () => {
      new window.google.translate.TranslateElement({
        pageLanguage: 'id',
        includedLanguages: 'id,en',
        layout: window.google.translate.TranslateElement.InlineLayout.SIMPLE,
        autoDisplay: false
      }, 'google_translate_element');
    };

    if (!document.getElementById('google-translate-script')) {
      const script = document.createElement('script');
      script.id = 'google-translate-script';
      script.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
      script.async = true;
      document.body.appendChild(script);
    }

    const observer = new MutationObserver(() => {
      const banner = document.querySelector('.goog-te-banner-frame');
      if (banner) {
        banner.style.display = 'none';
        banner.style.visibility = 'hidden';
      }
      document.body.style.top = '0px';
    });
    observer.observe(document.body, { childList: true, subtree: true, attributes: true });

    return () => observer.disconnect();
  }, []);

  // Vendor JS injections (dependencies for standard UI)
  useEffect(() => {
    const scripts = [
      'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
      'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js',
      'https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js',
      'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
      '/dist/landing/assets/js/main.js'
    ];
    
    scripts.forEach(src => {
      if (!document.querySelector(`script[src="${src}"]`)) {
        const script = document.createElement('script');
        script.src = src;
        script.async = true;
        document.body.appendChild(script);
      }
    });

    // AOS Init after a short delay
    setTimeout(() => {
      // @ts-ignore
      if (window.AOS) window.AOS.init({ duration: 1000, once: true });
    }, 1500);

  }, [location.pathname]);

  const changeLanguage = (lang) => {
    const cookieValue = (lang === 'id') ? '/id/id' : '/id/en';
    document.cookie = `googtrans=${cookieValue}; path=/; domain=.${document.domain}`;
    document.cookie = `googtrans=${cookieValue}; path=/`;
    const select = document.querySelector('select.goog-te-combo');
    if (select) {
      select.value = lang;
      select.dispatchEvent(new Event('change'));
    }
    window.location.reload();
  };

  const isActive = (path) => location.pathname === path ? 'active' : '';

  // Body class toggle for mobile nav
  useEffect(() => {
    if (mobileNavActive) {
      document.body.classList.add('mobile-nav-active');
    } else {
      document.body.classList.remove('mobile-nav-active');
    }
    return () => document.body.classList.remove('mobile-nav-active');
  }, [mobileNavActive]);

  return (
    <div className="index-page">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
      <link rel="stylesheet" href="/dist/landing/assets/css/main1.css" />

      {/* Translations Style Adjustments */}
      <style>
        {`
          .loading-container { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #ffffff; z-index: 999999; display: flex; justify-content: center; align-items: center; transition: opacity 0.5s ease-out, visibility 0.5s; opacity: ${loading ? 1 : 0}; visibility: ${loading ? 'visible' : 'hidden'}; }
          
          /* Navbar scroll fix: fully opaque solid white */
          header.scrolled {
            background-color: #ffffff !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12) !important;
            backdrop-filter: none !important;
          }

          /* Floating Language Switcher */
          .floating-lang-switcher { position: fixed; bottom: 90px; right: 18px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; align-items: center; }
          
          .lang-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 3px 14px rgba(0,0,0,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2.5px solid rgba(255,255,255,0.9);
            cursor: pointer;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
          }
          .lang-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
          }
          .lang-btn:hover {
            transform: scale(1.15) translateY(-2px);
            box-shadow: 0 8px 22px rgba(0,0,0,0.28);
          }
          .lang-btn[data-active="true"] {
            border-color: #A01D1D;
            box-shadow: 0 0 0 3px rgba(160,29,29,0.25);
          }
          .lang-btn::after {
            content: attr(title);
            position: absolute;
            right: 52px;
            background: rgba(30,30,30,0.85);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            padding: 3px 8px;
            border-radius: 6px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
          }
          .lang-btn:hover::after { opacity: 1; }

          .goog-te-banner-frame.skiptranslate, .goog-te-banner-frame, .skiptranslate > .goog-te-banner-frame, iframe.goog-te-banner-frame, #goog-gt-tt, .goog-te-balloon-frame, .goog-tooltip, .goog-text-highlight { display: none !important; visibility: hidden !important; height: 0 !important; width: 0 !important; opacity: 0 !important; pointer-events: none !important; }
          body { top: 0px !important; position: static !important; }
          body.translated-ltr, body.translated-rtl { margin-top: 0 !important; }
          #google_translate_element { position: absolute; top: -9999px; left: -9999px; width: 1px; height: 1px; overflow: hidden; visibility: visible; }
          font { background-color: transparent !important; box-shadow: none !important; }
          
          /* Dropdown override for react state */
          @media (max-width: 991.98px) {
            .mobile-only { display: inline-block; margin-left: 0.5rem; font-size: 0.875rem; transition: transform 0.3s ease; cursor: pointer; }
            .mobile-only.active { transform: rotate(180deg); }
            .mobile-dropdown { display: ${dropdownOpen ? 'block' : 'none'}; }
          }
          @media (min-width: 992px) {
            .mobile-only { display: none; }
            .mobile-dropdown { display: none; }
            .dropdown:hover .mobile-dropdown { display: block; }
          }
        `}
      </style>

      {loading && (
        <div className="loading-container">
          <div className="container2 flex justify-center items-center">
            <img src="/dist/landing/assets/img/loading55.gif" className="w-[500px] h-[500px] absolute mt-[7%]" alt="Loading" />
          </div>
        </div>
      )}

      {/* Header */}
      <header id="header" className={`header d-flex align-items-center fixed-top ${scrolled ? 'scrolled' : ''}`}>
        <div className="container-fluid container-xl position-relative d-flex align-items-center">

          <Link to="/" className="logo d-flex align-items-center me-auto">
            <img src="/dist/landing/assets/img/logo111.png" alt="OrmaOne Logo" width="60" height="60" />
            <h1 className="sitename" style={{ color: '#A01D1D', width: 20 }}>OrmaOne</h1>
          </Link>

          <nav id="navmenu" className="navmenu">
            <ul>
              <li><Link to="/" className={isActive('/')} onClick={() => setMobileNavActive(false)}>Home</Link></li>
              <li><Link to="/berita" className={isActive('/berita')} onClick={() => setMobileNavActive(false)}>Berita</Link></li>
              <li><Link to="/sertifikat" className={isActive('/sertifikat')} onClick={() => setMobileNavActive(false)}>E-Sertifikat</Link></li>
              <li><Link to="/voting" className={isActive('/voting')} onClick={() => setMobileNavActive(false)}>E-Voting</Link></li>
              <li className="dropdown">
                <a href="#" className={`organisasi-link ${isActive('/struktur')}`} onClick={(e) => {
                  if (window.innerWidth <= 991.98) { e.preventDefault(); setDropdownOpen(!dropdownOpen); }
                }}>
                  <span>Organisasi</span>
                  <i className={`bi bi-chevron-down toggle-dropdown mobile-only ${dropdownOpen ? 'active' : ''}`} onClick={(e) => {
                    if (window.innerWidth <= 991.98) { e.preventDefault(); e.stopPropagation(); setDropdownOpen(!dropdownOpen); }
                  }}></i>
                </a>
                <ul className={`mobile-dropdown ${dropdownOpen ? 'show' : ''}`}>
                  {organisasis.map(org => (
                    <li key={org.id}>
                      <Link to={`/struktur?org=${org.id}`} onClick={() => { setMobileNavActive(false); setDropdownOpen(false); }}>
                        {org.name}
                      </Link>
                    </li>
                  ))}
                </ul>
              </li>
            </ul>
            <i className={`mobile-nav-toggle d-xl-none bi ${mobileNavActive ? 'bi-x' : 'bi-list'}`} style={{ cursor: 'pointer', zIndex: 99999, position: 'relative', pointerEvents: 'auto' }} onClick={() => setMobileNavActive(!mobileNavActive)}></i>
          </nav>

          <Link className="btn-getstarted" to="/login">Login</Link>

        </div>
      </header>

      {/* Main Content */}
      <main className="main">
        <Outlet />
      </main>

      {/* Footer */}
      <footer id="footer" className="footer position-relative light-background">
        <div className="container footer-top">
          <div className="row gy-4">
            <div className="col-lg-4 col-md-6 footer-about">
              <Link to="/" className="logo d-flex align-items-center">
                <img src="/dist/landing/assets/img/logo111.png" style={{ width: '50px', height: '50px' }} alt="" />
                <span className="sitename" style={{ color: '#980517' }}>OrmaOne</span>
              </Link>
              <div className="footer-contact pt-3">
                <p>Jl. Husni Hamid No. 1, Kel. Karawang Wetan Kec. Karawang Barat, Nagasari, Kec. Karawang Barat, Karawang, Jawa Barat 41312</p>
                <p className="mt-3"><strong>Phone:</strong> <span>+62-812-9755-4172</span></p>
                <p><strong>Email:</strong> <span>CSDLHorizon@gmail.com</span></p>
              </div>
              <div className="social-links d-flex mt-4">
                <a href="#"><i className="bi bi-facebook"></i></a>
                <a href="#"><i className="bi bi-instagram"></i></a>
                <a href="#"><i className="bi bi-whatsapp"></i></a>
              </div>
            </div>

            <div className="col-lg-2 col-md-3 footer-links">
              <h4>Useful Links</h4>
              <ul>
                <li><Link to="/">Home</Link></li>
                <li><Link to="#">About us</Link></li>
                <li><Link to="#">Services</Link></li>
                <li><Link to="#">Terms of service</Link></li>
                <li><Link to="#">Privacy policy</Link></li>
              </ul>
            </div>

            <div className="col-lg-2 col-md-3 footer-links">
              <h4>Our Services</h4>
              <ul>
                <li><Link to="#">Web Design</Link></li>
                <li><Link to="#">Web Development</Link></li>
                <li><Link to="#">Product Management</Link></li>
                <li><Link to="#">Marketing</Link></li>
                <li><Link to="#">Graphic Design</Link></li>
              </ul>
            </div>

            <div className="col-lg-4 col-md-12 footer-newsletter">
              <h4>Our Newsletter</h4>
              <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
            </div>
          </div>
        </div>

        <div className="container copyright text-center mt-4">
          <p>© <span>Copyright</span> <strong className="px-1 sitename">Ormaone</strong><span>All Rights Reserved</span></p>
          <div className="credits">
            programmer by <a href="https://www.instagram.com/khol_lis312/" target="_blank" rel="noreferrer">Khol_lis312</a>
          </div>
        </div>
      </footer>

      {/* Floating Elements */}
      <a href="#" id="scroll-top" className={`scroll-top d-flex align-items-center justify-content-center ${scrolled ? 'active' : ''}`} onClick={(e) => { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); }}>
        <i className="bi bi-arrow-up-short text-white"></i>
      </a>

      <div className="floating-lang-switcher">
        <div className="lang-btn" onClick={() => changeLanguage('id')} title="Indonesia">
          <img src="https://flagcdn.com/w80/id.png" alt="Indonesia" />
        </div>
        <div className="lang-btn" onClick={() => changeLanguage('en')} title="English">
          <img src="https://flagcdn.com/w80/gb.png" alt="English" />
        </div>
      </div>

      <div id="google_translate_element"></div>
    </div>
  );
}
