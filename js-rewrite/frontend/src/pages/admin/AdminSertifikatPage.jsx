import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useNavigate } from 'react-router-dom';

export default function AdminSertifikatPage() {
  const navigate = useNavigate();
  const [sertifikats, setSertifikats] = useState([]);
  const [kategoris, setKategoris] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);

  // Form State
  const [formData, setFormData] = useState({
    nim: '', nama: '', email: '', namaSertifikatId: '', nomorSertifikat: '', fileSertifikat: ''
  });

  const token = localStorage.getItem('token');

  useEffect(() => {
    if (!token) return navigate('/login');
    fetchData();
  }, [token, navigate, page]);

  const fetchData = async () => {
    try {
      setLoading(true);
      const [resSert, resKat] = await Promise.all([
        axios.get(`http://localhost:3001/api/admin/sertifikat?page=${page}&limit=10`, { headers: { Authorization: `Bearer ${token}` } }),
        axios.get('http://localhost:3001/api/master/sertifikat-kategori') // Saya asumsikan ada endpoint master ini
      ]);
      if (resSert.data.status === 'success') {
        setSertifikats(resSert.data.data);
        setTotalPages(resSert.data.totalPages);
      }
      if (resKat.data.status === 'success') setKategoris(resKat.data.data);
    } catch (err) { console.error(err); }
    finally { setLoading(false); }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await axios.post('http://localhost:3001/api/admin/sertifikat', formData, {
        headers: { Authorization: `Bearer ${token}` }
      });
      Swal.fire('Berhasil', 'Sertifikat berhasil dicatat', 'success');
      setShowModal(false);
      setFormData({ nim: '', nama: '', email: '', namaSertifikatId: '', nomorSertifikat: '', fileSertifikat: '' });
      fetchData();
    } catch (err) { Swal.fire('Error', 'Gagal menyimpan sertifikat', 'error'); }
  };

  const handleDelete = async (id) => {
    const res = await Swal.fire({ title: 'Hapus Sertifikat?', text: "Data mahasiswa tidak akan terhapus, hanya catatan sertifikat ini.", icon: 'warning', showCancelButton: true });
    if (res.isConfirmed) {
      await axios.delete(`http://localhost:3001/api/admin/sertifikat/${id}`, { headers: { Authorization: `Bearer ${token}` } });
      fetchData();
    }
  };

  if (loading) return <div className="flex justify-center py-20"><div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-orange-600"></div></div>;

  return (
    <div className="p-8 bg-[#f2f7ff] min-h-screen font-sans">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-3xl font-black text-[#25396f] uppercase tracking-tight">Manajemen Sertifikat</h1>
          <p className="text-gray-400">Kelola dan terbitkan sertifikat digital mahasiswa.</p>
        </div>
        <button 
          onClick={() => setShowModal(true)}
          className="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg flex items-center gap-2"
        >
          <i className="bi bi-file-earmark-plus text-xl"></i> TERBITKAN SERTIFIKAT
        </button>
      </div>

      <div className="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table className="w-full text-left">
          <thead>
            <tr className="bg-gray-50 border-b">
              <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">NIM / Nama Mahasiswa</th>
              <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jenis Sertifikat</th>
              <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor Sertifikat</th>
              <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            {sertifikats.map((s) => (
              <tr key={s.id} className="border-b border-gray-50 hover:bg-orange-50/30 transition-all">
                <td className="px-6 py-4">
                  <div className="font-bold text-[#25396f]">{s.mahasiswa?.nim}</div>
                  <div className="text-xs text-gray-400">{s.mahasiswa?.nama}</div>
                </td>
                <td className="px-6 py-4">
                  <span className="text-xs font-black text-orange-600 uppercase tracking-tight">{s.namaSertifikat?.namaSertifikat}</span>
                </td>
                <td className="px-6 py-4 font-mono text-xs text-gray-500">{s.nomorSertifikat}</td>
                <td className="px-6 py-4 text-right">
                  <button onClick={() => handleDelete(s.id)} className="p-2 text-red-600 bg-red-50 rounded-lg"><i className="bi bi-trash3"></i></button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Pagination */}
      <div className="mt-8 flex justify-center gap-2">
         {Array.from({ length: totalPages }, (_, i) => i + 1).map(p => (
           <button key={p} onClick={() => setPage(p)} className={`w-10 h-10 rounded-lg font-bold text-sm ${page === p ? 'bg-orange-600 text-white' : 'bg-white border text-gray-400'}`}>{p}</button>
         ))}
      </div>

      {/* Modal Terbitkan Sertifikat */}
      {showModal && (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
           <div className="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl animate-in zoom-in duration-300">
              <div className="bg-orange-600 p-6 text-white text-center">
                 <h2 className="text-xl font-black uppercase italic tracking-tighter">Terbitkan Sertifikat Baru</h2>
              </div>
              <form onSubmit={handleSubmit} className="p-8 space-y-4">
                 <div className="grid grid-cols-2 gap-4">
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">NIM Mahasiswa</label>
                       <input className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-orange-600 outline-none" 
                        value={formData.nim} onChange={(e) => setFormData({...formData, nim: e.target.value})} required />
                    </div>
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</label>
                       <input className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-orange-600 outline-none" 
                        value={formData.nama} onChange={(e) => setFormData({...formData, nama: e.target.value})} required />
                    </div>
                 </div>
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Email Mahasiswa</label>
                    <input type="email" className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-orange-600 outline-none" 
                      value={formData.email} onChange={(e) => setFormData({...formData, email: e.target.value})} required />
                 </div>
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Jenis Sertifikat</label>
                    <select className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-orange-600 outline-none"
                      value={formData.namaSertifikatId} onChange={(e) => setFormData({...formData, namaSertifikatId: e.target.value})} required>
                        <option value="">Pilih Kategori...</option>
                        {kategoris.map(k => <option key={k.id} value={k.id}>{k.namaSertifikat}</option>)}
                    </select>
                 </div>
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nomor Sertifikat (SK / No. Reg)</label>
                    <input className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-orange-600 outline-none" 
                      value={formData.nomorSertifikat} onChange={(e) => setFormData({...formData, nomorSertifikat: e.target.value})} required />
                 </div>
                 <div className="pt-4 flex gap-3">
                    <button type="button" onClick={() => setShowModal(false)} className="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold hover:bg-gray-200 uppercase text-xs">Batal</button>
                    <button type="submit" className="flex-[2] bg-orange-600 text-white py-3 rounded-xl font-bold hover:bg-orange-700 uppercase shadow-lg text-xs">Simpan Data</button>
                 </div>
              </form>
           </div>
        </div>
      )}
    </div>
  );
}
