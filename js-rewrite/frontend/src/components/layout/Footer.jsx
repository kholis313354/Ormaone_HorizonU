import { Link } from 'react-router-dom';

export default function Footer() {
  return (
    <footer id="footer" className="footer position-relative light-background">
      <style>{`
        /* Footer CSS Variables & Overrides */
        .footer {
          --default-color: #3d4348;
          --heading-color: #3e5055;
          --accent-color: #980517;
          --default-font: "Roboto", sans-serif;
          --heading-font: "Nunito", sans-serif;
          color: var(--default-color);
          font-family: var(--default-font);
          font-size: 14px;
        }

        /* Logo sitename */
        .footer .footer-about .logo span.sitename {
          font-size: 26px;
          font-weight: 700;
          letter-spacing: 1px;
          font-family: var(--heading-font);
          color: #980517 !important;
        }

        /* Footer contact text */
        .footer .footer-contact p,
        .footer .footer-about p {
          color: var(--default-color);
          font-family: var(--default-font);
          font-size: 14px;
        }

        /* Section headings h4 */
        .footer h4 {
          font-size: 16px;
          font-weight: bold;
          color: var(--heading-color);
          font-family: var(--heading-font);
          position: relative;
          padding-bottom: 12px;
        }

        /* Footer links - override accent-color default */
        .footer .footer-links ul {
          list-style: none;
          padding: 0;
          margin: 0;
        }
        .footer .footer-links ul li {
          padding: 10px 0;
          display: flex;
          align-items: center;
        }
        .footer .footer-links ul li:first-child { padding-top: 0; }
        .footer .footer-links ul a {
          color: rgba(61, 67, 72, 0.7) !important;
          text-decoration: none;
          display: inline-block;
          line-height: 1;
          transition: color 0.3s;
        }
        .footer .footer-links ul a:hover {
          color: #980517 !important;
        }

        /* Newsletter text */
        .footer .footer-newsletter p {
          color: var(--default-color);
          font-family: var(--default-font);
          font-size: 14px;
        }

        /* Social links */
        .footer .social-links a {
          color: rgba(61, 67, 72, 0.7) !important;
          text-decoration: none;
        }
        .footer .social-links a:hover {
          color: #980517 !important;
          border-color: #980517 !important;
        }

        /* Copyright section */
        .footer .copyright {
          padding-top: 25px;
          padding-bottom: 25px;
          border-top: 1px solid rgba(61, 67, 72, 0.1);
        }
        .footer .copyright p {
          color: var(--default-color);
          font-family: var(--default-font);
          font-size: 14px;
          margin-bottom: 0;
        }
        .footer .copyright .sitename {
          color: #980517 !important;
          font-family: var(--heading-font);
        }
        .footer .credits {
          margin-top: 6px;
          font-size: 13px;
          color: var(--default-color);
          font-family: var(--default-font);
        }
        .footer .credits a {
          color: #980517 !important;
          text-decoration: none;
        }
        .footer .credits a:hover {
          text-decoration: underline;
        }
      `}</style>

      <div className="container footer-top">
        <div className="row gy-4">

          {/* Column 1: About + Contact */}
          <div className="col-lg-4 col-md-6 footer-about">
            <Link to="/" className="logo d-flex align-items-center">
              <img src="/dist/landing/assets/img/logo1.png" alt="OrmaOne Logo" />
              <span className="sitename" style={{ color: '#980517' }}>OrmaOne</span>
            </Link>
            <div className="footer-contact pt-3">
              <p>Jl. Husni Hamid No. 1, Kel. Karawang Wetan,
                Kec. Karawang Barat, Nagasari, Karawang, Jawa Barat 41312</p>
              <p className="mt-3"><strong>Phone:</strong> <span>+62-812-9755-4172</span></p>
              <p><strong>Email:</strong> <span>CSDLHorizon@gmail.com</span></p>
            </div>
            <div className="social-links d-flex mt-4">
              <a href="#"><i className="bi bi-facebook"></i></a>
              <a href="#"><i className="bi bi-instagram"></i></a>
              <a href="#"><i className="bi bi-whatsapp"></i></a>
            </div>
          </div>

          {/* Column 2: Useful Links */}
          <div className="col-lg-2 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><Link to="/">Home</Link></li>
              <li><Link to="/#about">About us</Link></li>
              <li><Link to="/berita">Berita</Link></li>
              <li><Link to="/sertifikat">E-Sertifikat</Link></li>
              <li><Link to="/voting">E-Voting</Link></li>
            </ul>
          </div>

          {/* Column 3: Our Services */}
          <div className="col-lg-2 col-md-3 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><Link to="/voting">E-Voting Web3</Link></li>
              <li><Link to="/sertifikat">Verifikasi Sertifikat</Link></li>
              <li><Link to="/struktur">Struktur Organisasi</Link></li>
              <li><Link to="/berita">Berita Ormawa</Link></li>
              <li><Link to="/#contact">Pengaduan Aspirasi</Link></li>
            </ul>
          </div>

          {/* Column 4: Newsletter */}
          <div className="col-lg-4 col-md-12 footer-newsletter">
            <h4>Our Newsletter</h4>
            <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
          </div>

        </div>
      </div>

      {/* Copyright */}
      <div className="container copyright text-center mt-4">
        <p>© <span>Copyright</span> <strong className="px-1 sitename">OrmaOne</strong><span>All Rights Reserved</span></p>
        <div className="credits">
          programmer by <a href="https://www.instagram.com/khol_lis312/" target="_blank" rel="noopener noreferrer">Khol_lis312</a>
        </div>
      </div>

    </footer>
  );
}
