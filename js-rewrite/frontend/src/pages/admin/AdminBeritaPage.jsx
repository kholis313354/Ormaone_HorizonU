import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useNavigate } from 'react-router-dom';

export default function AdminBeritaPage() {
  const navigate = useNavigate();
  const [beritas, setBeritas] = useState([]);
  const [fakultas, setFakultas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editingBerita, setEditingBerita] = useState(null);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);

  // Form State
  const [formData, setFormData] = useState({
    namaKegiatan: '',
    deskripsi: '',
    tanggal: new Date().toISOString().split('T')[0],
    kategori: 'Berita',
    fakultasId: '',
    userId: 1 // Default Admin
  });

  const token = localStorage.getItem('token');

  useEffect(() => {
    if (!token) {
      navigate('/login');
      return;
    }
    fetchData();
  }, [token, navigate, page]);

  const fetchData = async () => {
    try {
      setLoading(true);
      const [resBerita, resFakultas] = await Promise.all([
        axios.get(`http://localhost:3001/api/admin/berita?page=${page}&limit=8`, { headers: { Authorization: `Bearer ${token}` } }),
        axios.get('http://localhost:3001/api/master/fakultas')
      ]);
      if (resBerita.data.status === 'success') {
        setBeritas(resBerita.data.data);
        setTotalPages(resBerita.data.totalPages);
      }
      if (resFakultas.data.status === 'success') setFakultas(resFakultas.data.data);
    } catch (err) {
      console.error('Error fetching data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingBerita) {
        await axios.put(`http://localhost:3001/api/admin/berita/${editingBerita.id}`, formData, {
          headers: { Authorization: `Bearer ${token}` }
        });
        Swal.fire('Berhasil', 'Berita diperbarui', 'success');
      } else {
        await axios.post('http://localhost:3001/api/admin/berita', formData, {
          headers: { Authorization: `Bearer ${token}` }
        });
        Swal.fire('Berhasil', 'Berita dipublikasikan', 'success');
      }
      setShowModal(false);
      resetForm();
      fetchData();
    } catch (err) {
      Swal.fire('Error', 'Gagal menyimpan berita', 'error');
    }
  };

  const handleEdit = (berita) => {
    setEditingBerita(berita);
    setFormData({
      namaKegiatan: berita.namaKegiatan,
      deskripsi: berita.deskripsi,
      tanggal: new Date(berita.tanggal).toISOString().split('T')[0],
      kategori: berita.kategori,
      fakultasId: berita.fakultasId,
      userId: berita.userId
    });
    setShowModal(true);
  };

  const handleDelete = async (id) => {
    const result = await Swal.fire({
      title: 'Hapus Berita?',
      text: "Data ini akan hilang permanen dari publik!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus!'
    });

    if (result.isConfirmed) {
      try {
        await axios.delete(`http://localhost:3001/api/admin/berita/${id}`, {
          headers: { Authorization: `Bearer ${token}` }
        });
        Swal.fire('Terhapus!', 'Berita telah dihapus.', 'success');
        fetchData();
      } catch (err) {
        Swal.fire('Gagal', 'Gagal menghapus berita', 'error');
      }
    }
  };

  const resetForm = () => {
    setEditingBerita(null);
    setFormData({ 
      namaKegiatan: '', 
      deskripsi: '', 
      tanggal: new Date().toISOString().split('T')[0], 
      kategori: 'Berita', 
      fakultasId: '', 
      userId: 1 
    });
  };

  if (loading) return <div className="flex justify-center py-20"><div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600"></div></div>;

  return (
    <div className="p-8 bg-[#f2f7ff] min-h-screen font-sans">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-3xl font-black text-[#25396f] uppercase tracking-tight">Manajemen Berita</h1>
          <p className="text-gray-400">Publikasikan kegiatan dan pengumuman ormawa.</p>
        </div>
        <button 
          onClick={() => { resetForm(); setShowModal(true); }}
          className="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg flex items-center gap-2"
        >
          <i className="bi bi-pencil-square text-xl"></i> TULIS BERITA
        </button>
      </div>

      <div className="grid grid-cols-1 gap-4">
        {beritas.map((berita) => (
          <div key={berita.id} className="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6 items-center hover:shadow-md transition-all">
             <div className="w-full md:w-32 h-20 bg-gray-100 rounded-xl flex items-center justify-center text-3xl text-gray-300">
                <i className="bi bi-image"></i>
             </div>
             <div className="flex-1">
                <div className="flex items-center gap-2 mb-1">
                   <span className="bg-indigo-50 text-indigo-600 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-widest">{berita.kategori}</span>
                   <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{new Date(berita.tanggal).toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'})}</span>
                </div>
                <h3 className="text-lg font-black text-[#25396f] uppercase tracking-tight leading-tight mb-2">{berita.namaKegiatan}</h3>
                <p className="text-xs text-gray-500 line-clamp-1 opacity-70 mb-2">{berita.deskripsi.substring(0, 150)}...</p>
                <div className="text-[10px] font-bold text-gray-400 uppercase italic">Oleh: {berita.user?.name || 'Admin'} &bull; {berita.fakultas?.namaFakultas}</div>
             </div>
             <div className="flex gap-2">
                <button onClick={() => handleEdit(berita)} className="p-3 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors"><i className="bi bi-pencil-fill"></i></button>
                <button onClick={() => handleDelete(berita.id)} className="p-3 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors"><i className="bi bi-trash3-fill"></i></button>
             </div>
          </div>
        ))}
      </div>

      {/* Pagination */}
      <div className="mt-10 flex justify-center gap-2">
         {Array.from({ length: totalPages }, (_, i) => i + 1).map(p => (
           <button 
            key={p} 
            onClick={() => setPage(p)}
            className={`w-10 h-10 rounded-lg font-bold text-sm transition-all ${page === p ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white text-gray-400 border hover:bg-gray-50'}`}
           >
             {p}
           </button>
         ))}
      </div>

      {/* Modal CRUD Berita */}
      {showModal && (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
           <div className="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl animate-in zoom-in duration-300">
              <div className="bg-indigo-600 p-6 text-white text-center">
                 <h2 className="text-xl font-black uppercase italic tracking-tighter">{editingBerita ? 'Edit Berita' : 'Tulis Berita Baru'}</h2>
              </div>
              <form onSubmit={handleSubmit} className="p-8 space-y-5">
                 <div className="grid grid-cols-2 gap-4">
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Judul / Nama Kegiatan</label>
                       <input className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none" 
                        value={formData.namaKegiatan} onChange={(e) => setFormData({...formData, namaKegiatan: e.target.value})} required />
                    </div>
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kategori</label>
                       <select className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none"
                        value={formData.kategori} onChange={(e) => setFormData({...formData, kategori: e.target.value})}>
                          <option value="Berita">Berita</option>
                          <option value="Pengumuman">Pengumuman</option>
                          <option value="Kegiatan">Kegiatan</option>
                       </select>
                    </div>
                 </div>
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Deskripsi Konten</label>
                    <textarea 
                      className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 font-medium focus:border-indigo-600 outline-none h-40 resize-none text-sm" 
                      value={formData.deskripsi} onChange={(e) => setFormData({...formData, deskripsi: e.target.value})} required 
                    />
                 </div>
                 <div className="grid grid-cols-2 gap-4">
                    <div>
                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal</label>
                        <input type="date" className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none" 
                          value={formData.tanggal} onChange={(e) => setFormData({...formData, tanggal: e.target.value})} required />
                    </div>
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Fakultas Terkait</label>
                       <select className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none"
                        value={formData.fakultasId} onChange={(e) => setFormData({...formData, fakultasId: e.target.value})} required>
                          <option value="">Pilih...</option>
                          {fakultas.map(f => <option key={f.id} value={f.id}>{f.namaFakultas}</option>)}
                       </select>
                    </div>
                 </div>
                 <div className="pt-4 flex gap-3">
                    <button type="button" onClick={() => setShowModal(false)} className="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold hover:bg-gray-200 uppercase text-xs">Batal</button>
                    <button type="submit" className="flex-[2] bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 uppercase shadow-lg text-xs">Publikasikan Berita</button>
                 </div>
              </form>
           </div>
        </div>
      )}
    </div>
  );
}
