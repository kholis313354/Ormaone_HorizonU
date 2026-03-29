import { useState } from 'react';
import axios from 'axios';
import { useNavigate, Link } from 'react-router-dom';
import FlashMessage from '../../components/common/FlashMessage';
import ErrorMessages from '../../components/common/ErrorMessages';

export default function LoginPage() {
  const [email, setEmail]       = useState('');
  const [password, setPassword] = useState('');
  const [error, setError]       = useState('');
  const [success, setSuccess]   = useState('');
  const [loading, setLoading]   = useState(false);
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccess('');
    try {
      const res = await axios.post('http://localhost:3001/api/auth/login', { email, password });
      localStorage.setItem('token', res.data.token);
      localStorage.setItem('user', JSON.stringify(res.data.user));
      navigate('/admin/dashboard');
    } catch (err) {
      setError(err.response?.data?.message || 'Login gagal. Periksa kembali akun Anda.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
          height: 100%; margin: 0; padding: 0;
          background-image: url('/dist/landing/assets/img/background-login.jpg');
          background-size: cover;
          background-position: 30% center;
          background-repeat: no-repeat;
          background-attachment: fixed;
        }

        /* ── FULL-PAGE LAYOUT ───────────────────────────────────── */
        .lp-root {
          height: 100vh;
          width: 100vw;
          position: relative;
          display: grid;
          grid-template-columns: 1fr auto;
          align-items: center;
          font-family: 'Inter', sans-serif;
          background: transparent;
        }

        /* No lp-bg needed — background is on html/body */

        /* ── HERO TEXT (left / center column) ───────────────────── */
        .lp-hero {
          position: relative;
          z-index: 5;
          padding: 2rem 2rem 2rem 7%;
          text-align: center;
        }
        .lp-hero h1 {
          font-size: 3.5rem;
          font-weight: 800;
          line-height: 1.2;
          color: #fff;
          text-shadow: 0 2px 24px rgba(0,0,0,.55);
          margin-bottom: .6rem;
        }
        .lp-hero h1 em { color: #980517; font-style: normal; font-weight: 900; text-shadow: 0 0 20px rgba(255,255,255,.4), 0 2px 8px rgba(0,0,0,.3); }
        .lp-hero p {
          font-size: 1.2rem;
          color: rgba(255,255,255,.85);
          text-shadow: 0 1px 8px rgba(0,0,0,.35);
        }

        /* ── FLOATING CARD ──────────────────────────────────────── */
        .lp-card-wrap {
          position: relative;
          z-index: 10;
          padding: 1.5rem 2.5rem 1.5rem 0;
          display: flex;
          align-items: center;
          height: 100vh;
        }
        .lp-card {
          width: 400px;
          max-height: 95vh;
          overflow-y: auto;
          background: #fff;
          border-radius: 16px;
          padding: 1.75rem 2.25rem;
          box-shadow:
            0 20px 60px rgba(0,0,0,.22),
            0 2px 8px rgba(0,0,0,.1);
          animation: cardIn .5s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes cardIn {
          from { opacity: 0; transform: translateY(20px) scale(.97); }
          to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ── LOGO ────────────────────────────────────────────────── */
        .lp-logo {
          display: flex;
          flex-direction: column;
          align-items: center;
          margin-bottom: 1rem;
        }
        .lp-logo img {
          width: 52px;
          height: 52px;
          object-fit: contain;
          margin-bottom: .45rem;
          filter: drop-shadow(0 3px 8px rgba(152,5,23,.2));
        }
        .lp-logo-name {
          font-size: 1.3rem;
          font-weight: 800;
          color: #980517;
          letter-spacing: -.3px;
        }
        .lp-logo-sub {
          font-size: .68rem;
          color: #94a3b8;
          margin-top: .15rem;
          text-align: center;
        }

        /* ── BACK LINK ────────────────────────────────────────────── */
        .lp-back {
          display: inline-flex;
          align-items: center;
          gap: .35rem;
          font-size: .78rem;
          font-weight: 600;
          color: #980517;
          text-decoration: none;
          margin-bottom: 1rem;
          transition: opacity .2s, gap .2s;
        }
        .lp-back:hover { opacity: .75; gap: .5rem; color: #980517; }

        /* ── DIVIDER ─────────────────────────────────────────────── */
        .lp-divider {
          height: 1px;
          background: #f1f5f9;
          margin-bottom: 1.25rem;
        }

        /* ── LABEL ────────────────────────────────────────────────── */
        .lp-label {
          display: block;
          font-size: .68rem;
          font-weight: 700;
          letter-spacing: .8px;
          text-transform: uppercase;
          color: #475569;
          margin-bottom: .35rem;
        }
        .lp-label i { margin-right: .3rem; color: #980517; }

        /* ── INPUT ────────────────────────────────────────────────── */
        .lp-input {
          width: 100%;
          padding: .7rem .9rem;
          background: #f8fafc;
          border: 1.5px solid #e2e8f0;
          border-radius: 9px;
          font-size: .875rem;
          color: #1e293b;
          outline: none;
          font-family: 'Inter', sans-serif;
          transition: all .2s;
        }
        .lp-input::placeholder { color: #94a3b8; }
        .lp-input:focus {
          background: #fff;
          border-color: #980517;
          box-shadow: 0 0 0 3px rgba(152,5,23,.08);
        }

        /* ── SUBMIT BTN ───────────────────────────────────────────── */
        .lp-btn {
          width: 100%;
          padding: .8rem;
          background: linear-gradient(135deg, #980517, #c0392b);
          color: #fff;
          border: none;
          border-radius: 9px;
          font-size: .95rem;
          font-weight: 700;
          cursor: pointer;
          font-family: 'Inter', sans-serif;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: .5rem;
          box-shadow: 0 4px 16px rgba(152,5,23,.4);
          transition: all .22s;
        }
        .lp-btn:hover:not(:disabled) {
          transform: translateY(-2px);
          box-shadow: 0 8px 24px rgba(152,5,23,.5);
          background: linear-gradient(135deg, #7e040f, #a93226);
        }
        .lp-btn:active:not(:disabled) { transform: translateY(0); }
        .lp-btn:disabled { opacity: .7; cursor: not-allowed; }

        /* SPINNER */
        .lp-spin {
          width: 16px; height: 16px;
          border: 2px solid rgba(255,255,255,.3);
          border-top-color: #fff;
          border-radius: 50%;
          animation: spin .7s linear infinite;
          flex-shrink: 0;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* FOOTER */
        .lp-footer {
          text-align: center;
          font-size: .68rem;
          color: #94a3b8;
          margin-top: 1.5rem;
        }

        /* ── RESPONSIVE ───────────────────────────────────────────── */
        @media (max-width: 768px) {
          .lp-root { grid-template-columns: 1fr; justify-items: center; padding: 2rem 1rem; }
          .lp-hero { display: none; }
          .lp-card-wrap { padding: 0; }
          .lp-card { width: 100%; max-width: 380px; }
        }
      `}</style>

      <div className="lp-root">


        {/* Left: hero text centered */}
        <div className="lp-hero">
          <h1>Selamat datang<br />di Orma<em>One</em></h1>
          <p>Portal Manajemen Organisasi Mahasiswa</p>
        </div>

        {/* Right: floating card */}
        <div className="lp-card-wrap">
          <div className="lp-card">
            <div className="lp-logo">
              <img src="/dist/landing/assets/img/logo111.png" alt="OrmaOne" />
              <div className="lp-logo-name">OrmaOne</div>
              <div className="lp-logo-sub">Portal Manajemen Organisasi Mahasiswa</div>
            </div>

            <Link to="/" className="lp-back">
              <i className="bi bi-arrow-left-circle-fill"></i> Kembali ke Beranda
            </Link>

            <div className="lp-divider" />

            <form onSubmit={handleLogin} autoComplete="off">
              {success && <FlashMessage success={success} />}
              {error && <ErrorMessages errors={[error]} />}

              <div style={{ marginBottom: '.9rem' }}>
                <label className="lp-label" htmlFor="login-email">
                  <i className="bi bi-envelope-fill"></i>Email
                </label>
                <input id="login-email" type="text" className="lp-input"
                  placeholder="Masukkan email Anda"
                  value={email} onChange={e => setEmail(e.target.value)}
                  autoComplete="off" required />
              </div>

              <div style={{ marginBottom: '1.25rem' }}>
                <label className="lp-label" htmlFor="login-password">
                  <i className="bi bi-lock-fill"></i>Password
                </label>
                <input id="login-password" type="password" className="lp-input"
                  placeholder="Masukkan password Anda"
                  value={password} onChange={e => setPassword(e.target.value)}
                  autoComplete="off" required />
              </div>

              <button type="submit" className="lp-btn" disabled={loading}>
                {loading
                  ? <><div className="lp-spin" />Memproses...</>
                  : <><i className="bi bi-box-arrow-in-right"></i>Masuk</>}
              </button>
            </form>

            <div className="lp-footer">Copyright &copy; ormaone 2025</div>
          </div>
        </div>
      </div>
    </>
  );
}
