import { useState, useEffect } from 'react';
import axios from 'axios';
import { useOutletContext } from 'react-router-dom';
import Swal from 'sweetalert2';

export default function OrganisasiManagement() {
  const [organisasis, setOrganisasis] = useState([]);
  const [fakultasList, setFakultasList] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editMode, setEditMode] = useState(false);
  const [selectedFile, setSelectedFile] = useState(null);
  
  const [formData, setFormData] = useState({
    id: null,
    name: '',
    description: '',
    type: '',
    kodeFakultas: '',
    fakultasId: '',
    image: ''
  });

  const ctx = useOutletContext() || {};
  const API = ctx.API || 'http://localhost:3001';
  const token = localStorage.getItem('token');

  const fetchData = async () => {
    try {
      setLoading(true);
      const [oRes, fRes] = await Promise.all([
        axios.get(`${API}/api/master/organisasi`),
        axios.get(`${API}/api/master/fakultas`)
      ]);
      if (oRes.data.success) setOrganisasis(oRes.data.data);
      if (fRes.data.success) setFakultasList(fRes.data.data);
    } catch (err) {
      console.error(err);
      Swal.fire('Error', 'Gagal memuat data Master', 'error');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, [API]);

  const handleOpenModal = (org = null) => {
    if (org) {
      setEditMode(true);
      setFormData({
        id: org.id,
        name: org.name || '',
        description: org.description || '',
        type: org.type || '',
        kodeFakultas: org.kodeFakultas || '',
        fakultasId: org.fakultasId || '',
        image: org.image || ''
      });
    } else {
      setEditMode(false);
      setFormData({
        id: null,
        name: '',
        description: '',
        type: '',
        kodeFakultas: '',
        fakultasId: '',
        image: ''
      });
    }
    setSelectedFile(null);
    setShowModal(true);
  };

  const handleCloseModal = () => setShowModal(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const dataToSubmit = new FormData();
      dataToSubmit.append('name', formData.name);
      dataToSubmit.append('description', formData.description);
      dataToSubmit.append('type', formData.type);
      dataToSubmit.append('kodeFakultas', formData.kodeFakultas);
      dataToSubmit.append('fakultasId', formData.fakultasId);
      if (selectedFile) {
        dataToSubmit.append('image', selectedFile);
      }

      const axiosConfig = {
        headers: { 
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'multipart/form-data'
        }
      };

      if (editMode && formData.id) {
        const res = await axios.put(`${API}/api/master/organisasi/${formData.id}`, dataToSubmit, axiosConfig);
        if (res.data.success) Swal.fire('Sukses', 'Organisasi berhasil diperbarui', 'success');
      } else {
        const res = await axios.post(`${API}/api/master/organisasi`, dataToSubmit, axiosConfig);
        if (res.data.success) Swal.fire('Sukses', 'Organisasi berhasil ditambahkan', 'success');
      }
      fetchData();
      handleCloseModal();
    } catch (err) {
      console.error(err);
      Swal.fire('Gagal', err.response?.data?.message || 'Terjadi kesalahan sistem', 'error');
    }
  };

  const handleDelete = (id) => {
    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: "Data Organisasi akan dihapus permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then(async (result) => {
      if (result.isConfirmed) {
        try {
          const res = await axios.delete(`${API}/api/master/organisasi/${id}`, {
            headers: { Authorization: `Bearer ${token}` }
          });
          if (res.data.success) {
            Swal.fire('Terhapus!', 'Data organisasi telah dihapus.', 'success');
            fetchData();
          }
        } catch (error) {
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
          <div className="card">
            <div className="card-body">
              <div className="card-header px-0 pt-0">
                <div className="d-flex justify-content-between align-items-center">
                  <h4 className="card-title m-0">Data Organisasi</h4>
                  <button className="btn btn-primary" onClick={() => handleOpenModal()}>
                    <i className="bi bi-plus-circle"></i> Tambah Organisasi
                  </button>
                </div>
              </div>
              
              {loading ? (
                <div className="text-center py-4">Memuat data...</div>
              ) : (
                <div className="table-responsive">
                  <table className="table table-striped table-bordered" id="table1" width="100%" cellSpacing="0">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Kode Fakultas</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Tanggal</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      {organisasis.length > 0 ? (
                        organisasis.map((item, index) => (
                          <tr key={item.id}>
                            <td>{index + 1}</td>
                            <td>{item.kodeFakultas || '-'}</td>
                            <td>{item.name}</td>
                            <td>{item.description}</td>
                            <td>
                              {item.image ? (
                                <img src={`${API}/uploads/${item.image}`} alt={`Logo ${item.name}`} style={{ width: '50px', height: '50px', objectFit: 'cover', borderRadius: '4px' }} />
                              ) : (
                                <span className="text-muted">-</span>
                              )}
                            </td>
                            <td>{item.createdAt ? new Date(item.createdAt).toLocaleString('id-ID', {day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit'}).replace('.', ':') : '-'}</td>
                            <td>
                              <button className="btn btn-primary btn-sm me-1" onClick={() => handleOpenModal(item)}>
                                <i className="bi bi-pencil-square"></i> Edit
                              </button>
                              <button className="btn btn-danger btn-sm" onClick={() => handleDelete(item.id)}>
                                <i className="bi bi-trash"></i> Delete
                              </button>
                            </td>
                          </tr>
                        ))
                      ) : (
                        <tr>
                          <td colSpan="7" className="text-center">Tidak ada data organisasi</td>
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
        <div className="modal-dialog modal-lg">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title">{editMode ? 'Edit Organisasi' : 'Tambah Organisasi'}</h5>
              <button type="button" className="btn-close" onClick={handleCloseModal} aria-label="Close"></button>
            </div>
            <form onSubmit={handleSubmit}>
              <div className="modal-body">
                <div className="row">
                  <div className="col-md-6 mb-3">
                    <label className="form-label">Nama Organisasi</label>
                    <input type="text" className="form-control" value={formData.name} onChange={e => setFormData({...formData, name: e.target.value})} required placeholder="Contoh: HUSC" />
                  </div>
                  <div className="col-md-6 mb-3">
                    <label className="form-label">Tipe Organisasi</label>
                    <select className="form-select" value={formData.type} onChange={e => setFormData({...formData, type: e.target.value})}>
                      <option value="">-- Pilih Tipe --</option>
                      <option value="BEM">BEM</option>
                      <option value="UKM">UKM</option>
                      <option value="HMJ">Himpunan Jurusan</option>
                    </select>
                  </div>
                  <div className="col-md-6 mb-3">
                    <label className="form-label">Fakultas Terkait (Opsional)</label>
                    <select className="form-select" value={formData.fakultasId} onChange={e => setFormData({...formData, fakultasId: e.target.value})}>
                      <option value="">-- Semua Fakultas --</option>
                      {fakultasList.map(f => <option key={f.id} value={f.id}>{f.namaFakultas}</option>)}
                    </select>
                  </div>
                  <div className="col-md-6 mb-3">
                    <label className="form-label">Kode Fakultas</label>
                    <input type="text" className="form-control" value={formData.kodeFakultas} onChange={e => setFormData({...formData, kodeFakultas: e.target.value})} placeholder="Contoh: 14201,14401" />
                  </div>
                  <div className="col-md-12 mb-3">
                    <label className="form-label">Deskripsi</label>
                    <textarea className="form-control" rows="3" value={formData.description} onChange={e => setFormData({...formData, description: e.target.value})} placeholder="Penjelasan singkat mengenai organisasi"></textarea>
                  </div>
                  <div className="col-md-12 mb-3">
                    <label className="form-label">Logo / Image File</label>
                    <input type="file" className="form-control" onChange={e => setSelectedFile(e.target.files[0])} accept="image/*" />
                    {editMode && formData.image && (
                      <small className="text-muted d-block mt-1">Logo saat ini: {formData.image}</small>
                    )}
                  </div>
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
