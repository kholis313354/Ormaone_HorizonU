import { useState, useEffect } from 'react';
import axios from 'axios';
import { useOutletContext, Link } from 'react-router-dom';
import Swal from 'sweetalert2';

export default function InboxPage() {
  const [messages, setMessages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedMessage, setSelectedMessage] = useState(null);

  const ctx = useOutletContext() || {};
  const API = ctx.API || 'http://localhost:3001';
  const token = localStorage.getItem('token');

  const fetchMessages = async () => {
    try {
      setLoading(true);
      const res = await axios.get(`${API}/api/admin/pesan`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      if (res.data.success || res.data.status === 'success') {
        setMessages(res.data.data);
      }
    } catch (err) {
      console.error(err);
      Swal.fire('Error', 'Gagal memuat kotak masuk', 'error');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchMessages();
  }, [API]);

  const handleRead = async (msg) => {
    setSelectedMessage(msg);
    if (msg.status === 'unread') {
      try {
        await axios.put(`${API}/api/admin/pesan/${msg.id}/read`, {}, {
          headers: { Authorization: `Bearer ${token}` }
        });
        // Update local state directly to show it's read
        setMessages(messages.map(m => m.id === msg.id ? { ...m, status: 'read' } : m));
      } catch (err) {
        console.error(err);
      }
    }
  };

  const handleDelete = (id) => {
    Swal.fire({
      title: 'Hapus Pesan?',
      text: "Anda tidak dapat mengembalikan pesan ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          await axios.delete(`${API}/api/admin/pesan/${id}`, {
            headers: { Authorization: `Bearer ${token}` }
          });
          Swal.fire('Terhapus!', 'Pesan telah dihapus.', 'success');
          setMessages(messages.filter(m => m.id !== id));
          if (selectedMessage && selectedMessage.id === id) {
            setSelectedMessage(null);
          }
        } catch (err) {
          Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus pesan.', 'error');
        }
      }
    });
  };

  return (
    <>
      <div className="pagetitle">
        <h1>Kontak Masuk</h1>
        <nav>
          <ol className="breadcrumb">
            <li className="breadcrumb-item"><Link to="/admin/dashboard">Home</Link></li>
            <li className="breadcrumb-item active">Inbox</li>
          </ol>
        </nav>
      </div>

      <section className="section">
        <div className="row">
          <div className="col-lg-5">
            <div className="card">
              <div className="card-body">
                <h5 className="card-title">Pesan dari Pengunjung</h5>
                
                {loading ? (
                  <div className="text-center my-4">Refeshing...</div>
                ) : (
                  <div className="list-group">
                    {messages.length > 0 ? messages.map(msg => (
                      <button 
                        key={msg.id}
                        type="button" 
                        className={`list-group-item list-group-item-action ${selectedMessage?.id === msg.id ? 'active' : ''}`}
                        onClick={() => handleRead(msg)}
                      >
                        <div className="d-flex w-100 justify-content-between">
                          <h6 className="mb-1 fw-bold">
                            {msg.status === 'unread' && <span className="badge bg-danger me-2">New</span>}
                            {msg.name}
                          </h6>
                          <small>{new Date(msg.createdAt).toLocaleDateString('id-ID')}</small>
                        </div>
                        <p className="mb-1 text-truncate" style={{ fontSize: '0.875rem' }}>{msg.subject}</p>
                      </button>
                    )) : (
                      <div className="text-center py-4 text-muted border border-dashed rounded">Kotak masuk kosong.</div>
                    )}
                  </div>
                )}
                
              </div>
            </div>
          </div>

          <div className="col-lg-7">
            {selectedMessage ? (
              <div className="card">
                <div className="card-body">
                  <div className="d-flex justify-content-between align-items-center mb-3 mt-3">
                    <h5 className="card-title p-0 m-0">{selectedMessage.subject}</h5>
                    <button className="btn btn-sm btn-outline-danger" onClick={() => handleDelete(selectedMessage.id)}>
                      <i className="bi bi-trash"></i> Hapus
                    </button>
                  </div>
                  
                  <div className="p-3 bg-light rounded mb-3" style={{ borderLeft: '4px solid #A01D1D' }}>
                    <div className="d-flex align-items-center mb-2">
                      <i className="bi bi-person-circle fs-3 me-2 text-secondary"></i>
                      <div>
                        <strong>{selectedMessage.name}</strong><br/>
                        <small className="text-muted">{selectedMessage.email}</small>
                      </div>
                      <div className="ms-auto align-self-start pt-1 text-muted small">
                        {new Date(selectedMessage.createdAt).toLocaleString('id-ID')}
                      </div>
                    </div>
                    <hr />
                    <div className="pt-2">
                      {selectedMessage.message.split('\n').map((line, i) => (
                        <span key={i}>{line}<br/></span>
                      ))}
                    </div>
                  </div>
                </div>
              </div>
            ) : (
              <div className="card">
                <div className="card-body d-flex flex-column align-items-center justify-content-center" style={{ minHeight: '300px' }}>
                  <i className="bi bi-envelope-open text-muted" style={{ fontSize: '4rem', opacity: 0.5 }}></i>
                  <p className="text-muted mt-3">Pilih pesan di sebelah kiri untuk membaca isi pesannya.</p>
                </div>
              </div>
            )}
          </div>
        </div>
      </section>
    </>
  );
}
