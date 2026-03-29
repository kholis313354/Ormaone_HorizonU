import { useState, useEffect } from 'react';
import axios from 'axios';
import { useOutletContext } from 'react-router-dom';
import Swal from 'sweetalert2';

export default function FakultasManagement() {
  const [fakultasList, setFakultasList] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editMode, setEditMode] = useState(false);
  const [formData, setFormData] = useState({ id: null, namaFakultas: '' });
  
  const ctx = useOutletContext() || {};
  const API = ctx.API || 'http://localhost:3001';
  const token = localStorage.getItem('token');

  const fetchFakultas = async () => {
    try {
      setLoading(true);
      const res = await axios.get(`${API}/api/master/fakultas`);
      if (res.data.success) {
        setFakultasList(res.data.data);
      }
    } catch (error) {
      console.error(error);
      Swal.fire('Error', 'Gagal memuat data Fakultas', 'error');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchFakultas();
  }, [API]);

  const handleOpenModal = (fakultas = null) => {
    if (fakultas) {
      setEditMode(true);
      setFormData({ id: fakultas.id, namaFakultas: fakultas.namaFakultas });
    } else {
      setEditMode(false);
      setFormData({ id: null, namaFakultas: '' });
    }
    setShowModal(true);
  };

  const handleCloseModal = () => {
    setShowModal(false);
    setFormData({ id: null, namaFakultas: '' });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editMode && formData.id) {
        // Update
        const res = await axios.put(`${API}/api/master/fakultas/${formData.id}`, { namaFakultas: formData.namaFakultas }, {
          headers: { Authorization: `Bearer ${token}` }
        });
        if (res.data.success) {
          Swal.fire('Sukses', 'Fakultas berhasil diperbarui', 'success');
          fetchFakultas();
          handleCloseModal();
        }
      } else {
        // Create
        const res = await axios.post(`${API}/api/master/fakultas`, { namaFakultas: formData.namaFakultas }, {
          headers: { Authorization: `Bearer ${token}` }
        });
        if (res.data.success) {
          Swal.fire('Sukses', 'Fakultas berhasil ditambahkan', 'success');
          fetchFakultas();
          handleCloseModal();
        }
      }
    } catch (error) {
      console.error(error);
      const msg = error.response?.data?.message || 'Gagal menyimpan fakultas';
      Swal.fire('Gagal', msg, 'error');
    }
  };

  const handleDelete = (id) => {
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: "Data Fakultas akan dihapus permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const res = await axios.delete(`${API}/api/master/fakultas/${id}`, {
            headers: { Authorization: `Bearer ${token}` }
          });
          if (res.data.success) {
            Swal.fire('Terhapus!', 'Fakultas telah dihapus.', 'success');
            fetchFakultas();
          }
        } catch (error) {
          console.error(error);
          Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data', 'error');
        }
      }
    });
  };

  return (
    <>
      <style>
        {`
          #table1 {
              border: 2px solid #dee2e6 !important;
          }
          #table1 th,
          #table1 td {
              border: 1px solid #dee2e6 !important;
          }
          #table1 thead th {
              border-bottom: 2px solid #dee2e6 !important;
          }
        `}
      </style>
      <div className="page-heading">
        <div className="page-title">
          <div className="row">
            <div className="col-12 col-md-6 order-md-1 order-last">
              <p className="text-subtitle text-muted"></p>
            </div>
          </div>
        </div>
        <section className="section">
          {/* Tabel Fakultas */}
          <div className="card">
            <div className="card-header">
              <div className="d-flex justify-content-between align-items-center">
                <h4 className="card-title">Daftar Fakultas</h4>
                <div className="d-flex align-items-center gap-2">
                  <button className="btn btn-primary" onClick={() => handleOpenModal()}>
                    <i className="bi bi-plus-circle"></i> Tambah Fakultas
                  </button>
                </div>
              </div>
            </div>
            <div className="card-body">
              {loading ? (
                <div className="text-center py-4">Memuat data...</div>
              ) : (
                <div className="table-responsive">
                  <table className="table table-striped table-bordered" id="table1" width="100%" cellSpacing="0">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama Fakultas</th>
                        <th>Tanggal Dibuat</th>
                        <th>Tanggal Diupdate</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      {fakultasList.length > 0 ? (
                        fakultasList.map((fak, index) => (
                          <tr key={fak.id}>
                            <td>{index + 1}</td>
                            <td>{fak.namaFakultas}</td>
                            <td>{fak.createdAt ? new Date(fak.createdAt).toLocaleString('id-ID', {day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit'}).replace('.', ':') : '-'}</td>
                            <td>{fak.updatedAt ? new Date(fak.updatedAt).toLocaleString('id-ID', {day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit'}).replace('.', ':') : '-'}</td>
                            <td>
                              <button className="btn btn-sm btn-warning me-1" onClick={() => handleOpenModal(fak)}>
                                <i className="bi bi-pencil-square"></i> Edit
                              </button>
                              <button className="btn btn-sm btn-danger" onClick={() => handleDelete(fak.id)}>
                                <i className="bi bi-trash"></i> Hapus
                              </button>
                            </td>
                          </tr>
                        ))
                      ) : (
                        <tr>
                          <td colSpan="5" className="text-center">Tidak ada data fakultas</td>
                        </tr>
                      )}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          </div>
        </section>
      </div>

      {/* Modal Add/Edit */}
      <div className={`modal fade ${showModal ? 'show d-block' : ''}`} tabIndex="-1" style={{ backgroundColor: showModal ? 'rgba(0,0,0,0.5)' : 'transparent' }}>
        <div className="modal-dialog">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title">{editMode ? 'Edit Fakultas' : 'Tambah Fakultas'}</h5>
              <button type="button" className="btn-close" onClick={handleCloseModal} aria-label="Close"></button>
            </div>
            <form onSubmit={handleSubmit}>
              <div className="modal-body">
                <div className="mb-3">
                  <label htmlFor="namaFakultas" className="form-label">Nama Fakultas</label>
                  <input 
                    type="text" 
                    className="form-control" 
                    id="namaFakultas" 
                    value={formData.namaFakultas} 
                    onChange={(e) => setFormData({...formData, namaFakultas: e.target.value})}
                    required 
                    placeholder="Contoh: FICT, FMB, FHS..."
                  />
                </div>
              </div>
              <div className="modal-footer">
                <button type="button" className="btn btn-secondary" onClick={handleCloseModal}>Batal</button>
                <button type="submit" className="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </>
  );
}
