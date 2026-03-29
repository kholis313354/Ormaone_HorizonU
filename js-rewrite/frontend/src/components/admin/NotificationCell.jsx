import { useState, useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';

export default function NotificationCell() {
  const [notifications, setNotifications] = useState([]);
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef(null);
  
  const token = localStorage.getItem('token');
  const API = 'http://localhost:3001';

  const fetchUnread = async () => {
    try {
      const res = await axios.get(`${API}/api/admin/pesan`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      if (res.data.success || res.data.status === 'success') {
        const unreadMsgs = res.data.data.filter(m => m.status === 'unread').map(m => ({
          id: m.id,
          icon: 'bi-envelope-fill',
          iconColor: '#4e73df',
          iconBg: '#eef2ff',
          message: `Pesan baru dari ${m.name}`,
          time: new Date(m.createdAt).toLocaleDateString('id-ID'),
          link: '/admin/pesan'
        }));
        setNotifications(unreadMsgs);
      }
    } catch (err) { }
  };

  useEffect(() => {
    fetchUnread();
    const interval = setInterval(fetchUnread, 30000); // Polling setiap 30 detik
    return () => clearInterval(interval);
  }, []);

  useEffect(() => {

    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const unreadCount = notifications.length;

  return (
    <div ref={dropdownRef} style={{ position: 'relative', display: 'inline-flex' }}>
      {/* Bell button */}
      <button
        onClick={() => setIsOpen(!isOpen)}
        title="Notifikasi"
        style={{
          width: '38px', height: '38px', borderRadius: '.5rem',
          background: '#f8fafc', border: '1px solid #e2e8f0',
          display: 'flex', alignItems: 'center', justifyContent: 'center',
          color: '#64748b', cursor: 'pointer', transition: 'all .2s',
          position: 'relative', outline: 'none',
        }}
        onMouseEnter={e => { e.currentTarget.style.background = '#f1f5f9'; e.currentTarget.style.color = '#1e293b'; }}
        onMouseLeave={e => { e.currentTarget.style.background = '#f8fafc'; e.currentTarget.style.color = '#64748b'; }}
      >
        <i className="bi bi-bell-fill" style={{ fontSize: '1rem' }}></i>
        {unreadCount > 0 && (
          <span style={{
            position: 'absolute', top: '5px', right: '5px',
            width: '8px', height: '8px', background: '#f97316',
            borderRadius: '50%', border: '2px solid #fff',
          }} />
        )}
      </button>

      {/* Dropdown */}
      {isOpen && (
        <div style={{
          position: 'absolute', top: 'calc(100% + 10px)', right: 0,
          width: '320px', background: '#fff', border: '1px solid #e2e8f0',
          borderRadius: '.75rem', boxShadow: '0 10px 40px rgba(0,0,0,.14)',
          zIndex: 300, overflow: 'hidden',
        }}>
          {/* Header */}
          <div style={{
            display: 'flex', alignItems: 'center', justifyContent: 'space-between',
            padding: '.875rem 1.125rem', borderBottom: '1px solid #f1f5f9',
            background: '#fafbfc',
          }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '.5rem' }}>
              <i className="bi bi-bell-fill" style={{ color: '#A01D1D', fontSize: '.9rem' }}></i>
              <span style={{ fontWeight: 700, fontSize: '.875rem', color: '#1e293b' }}>Notifikasi</span>
            </div>
            {unreadCount > 0 && (
              <span style={{
                background: '#A01D1D', color: '#fff', fontSize: '.7rem',
                fontWeight: 700, padding: '.15rem .5rem', borderRadius: '999px',
              }}>
                {unreadCount} Baru
              </span>
            )}
          </div>

          {/* Notification list */}
          <div style={{ maxHeight: '320px', overflowY: 'auto' }}>
            {notifications.length > 0 ? notifications.map((notif, idx) => (
              <Link
                key={notif.id}
                to={notif.link}
                onClick={() => setIsOpen(false)}
                style={{
                  display: 'flex', alignItems: 'flex-start', gap: '.875rem',
                  padding: '.875rem 1.125rem', textDecoration: 'none', color: 'inherit',
                  borderBottom: idx < notifications.length - 1 ? '1px solid #f1f5f9' : 'none',
                  transition: 'background .15s',
                }}
                onMouseEnter={e => e.currentTarget.style.background = '#f8fafc'}
                onMouseLeave={e => e.currentTarget.style.background = 'transparent'}
              >
                {/* Icon */}
                <div style={{
                  width: '36px', height: '36px', borderRadius: '.5rem',
                  background: notif.iconBg, display: 'flex', alignItems: 'center',
                  justifyContent: 'center', flexShrink: 0,
                }}>
                  <i className={`bi ${notif.icon}`} style={{ color: notif.iconColor, fontSize: '1rem' }}></i>
                </div>
                {/* Text */}
                <div style={{ flex: 1, minWidth: 0 }}>
                  <p style={{ margin: 0, fontSize: '.825rem', fontWeight: 600, color: '#1e293b', lineHeight: 1.3 }}>
                    {notif.message}
                  </p>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '.3rem', marginTop: '.25rem' }}>
                    <i className="bi bi-clock" style={{ fontSize: '.65rem', color: '#94a3b8' }}></i>
                    <span style={{ fontSize: '.75rem', color: '#94a3b8' }}>{notif.time}</span>
                  </div>
                </div>
              </Link>
            )) : (
              <div style={{ padding: '2rem', textAlign: 'center', color: '#94a3b8' }}>
                <i className="bi bi-bell-slash" style={{ fontSize: '2rem', display: 'block', marginBottom: '.5rem' }}></i>
                <span style={{ fontSize: '.85rem' }}>Tidak ada notifikasi baru</span>
              </div>
            )}
          </div>

          {/* Footer */}
          <div style={{ padding: '.625rem 1.125rem', borderTop: '1px solid #f1f5f9', background: '#fafbfc', textAlign: 'center' }}>
            <Link to="#" onClick={() => setIsOpen(false)} style={{
              fontSize: '.8rem', color: '#A01D1D', fontWeight: 600, textDecoration: 'none',
              display: 'inline-flex', alignItems: 'center', gap: '.3rem',
            }}>
              Lihat Semua <i className="bi bi-arrow-right"></i>
            </Link>
          </div>
        </div>
      )}
    </div>
  );
}
