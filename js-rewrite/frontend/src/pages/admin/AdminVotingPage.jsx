import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useNavigate } from 'react-router-dom';

export default function AdminVotingPage() {
  const navigate = useNavigate();
  const [pemilihans, setPemilihans] = useState([]);
  const [organisasis, setOrganisasis] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showPemilihanModal, setShowPemilihanModal] = useState(false);
  const [showCalonModal, setShowCalonModal] = useState(false);
  const [selectedPemilihan, setSelectedPemilihan] = useState(null);

  // Form States
  const [pemilihanForm, setPemilihanForm] = useState({
    judul: '', deskripsi: '', tanggalMulai: '', tanggalSelesai: '', organisasiId: ''
  });
  const [calonForm, setCalonForm] = useState({
    nama: '', noUrut: '', visiMisi: '', gambar: '', pemilihanId: ''
  });

  const token = localStorage.getItem('token');

  useEffect(() => {
    if (!token) {
      navigate('/login');
      return;
    }
    fetchData();
  }, [token, navigate]);

  const fetchData = async () => {
    try {
      setLoading(true);
      const [resVoting, resOrg] = await Promise.all([
        axios.get('http://localhost:3001/api/admin/voting', { headers: { Authorization: `Bearer ${token}` } }),
        axios.get('http://localhost:3001/api/admin/organisasi', { headers: { Authorization: `Bearer ${token}` } })
      ]);
      if (resVoting.data.status === 'success') setPemilihans(resVoting.data.data);
      if (resOrg.data.status === 'success') setOrganisasis(resOrg.data.data);
    } catch (err) {
      console.error('Error fetching voting data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handlePemilihanSubmit = async (e) => {
    e.preventDefault();
    try {
      await axios.post('http://localhost:3001/api/admin/voting', pemilihanForm, {
        headers: { Authorization: `Bearer ${token}` }
      });
      Swal.fire('Berhasil', 'Pemilihan baru dibuat', 'success');
      setShowPemilihanModal(false);
      setPemilihanForm({ judul: '', deskripsi: '', tanggalMulai: '', tanggalSelesai: '', organisasiId: '' });
      fetchData();
    } catch (err) {
      Swal.fire('Error', 'Gagal membuat pemilihan', 'error');
    }
  };

  const handleCalonSubmit = async (e) => {
    e.preventDefault();
    try {
      await axios.post('http://localhost:3001/api/admin/voting/calon', { ...calonForm, pemilihanId: selectedPemilihan.id }, {
        headers: { Authorization: `Bearer ${token}` }
      });
      Swal.fire('Berhasil', 'Kandidat ditambahkan', 'success');
      setShowCalonModal(false);
      setCalonForm({ nama: '', noUrut: '', visiMisi: '', gambar: '', pemilihanId: '' });
      fetchData();
    } catch (err) {
      Swal.fire('Error', 'Gagal menambah kandidat', 'error');
    }
  };

  const handleDeletePemilihan = async (id) => {
    const res = await Swal.fire({ title: 'Hapus Pemilihan?', text: "Semua data suara dan kandidat akan hilang!", icon: 'warning', showCancelButton: true });
    if (res.isConfirmed) {
      await axios.delete(`http://localhost:3001/api/admin/voting/${id}`, { headers: { Authorization: `Bearer ${token}` } });
      fetchData();
    }
  };

  if (loading) return <div className="flex justify-center py-20"><div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-red-700"></div></div>;

  return (
    <div className="p-8 bg-[#f2f7ff] min-h-screen font-sans">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-3xl font-black text-[#25396f] uppercase tracking-tight">Manajemen E-Voting</h1>
          <p className="text-gray-400">Kelola periode pemilihan dan daftar kandidat Paslon.</p>
        </div>
        <button 
          onClick={() => setShowPemilihanModal(true)}
          className="bg-red-700 hover:bg-red-800 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg flex items-center gap-2"
        >
          <i className="bi bi-calendar-plus text-xl"></i> BUAT PEMILIHAN
        </button>
      </div>

      <div className="space-y-6">
        {pemilihans.map((p) => (
          <div key={p.id} className="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
             <div className="flex justify-between items-start mb-6">
                <div>
                   <span className="bg-red-50 text-red-700 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest mb-2 inline-block">ID: {p.id} &bull; {p.organisasi?.namaOrganisasi}</span>
                   <h2 className="text-2xl font-black text-[#25396f] uppercase tracking-tighter italic">{p.judul}</h2>
                   <p className="text-gray-400 text-sm font-medium">{new Date(p.tanggalMulai).toLocaleDateString()} - {new Date(p.tanggalSelesai).toLocaleDateString()}</p>
                </div>
                <div className="flex gap-2">
                   <button onClick={() => { setSelectedPemilihan(p); setShowCalonModal(true); }} className="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase"><i className="bi bi-person-plus mr-2"></i> Tambah Kandidat</button>
                   <button onClick={() => handleDeletePemilihan(p.id)} className="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors"><i className="bi bi-trash3"></i></button>
                </div>
             </div>

             <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {p.pemilihanCalons?.map(calon => (
                  <div key={calon.id} className="bg-gray-50 p-4 rounded-2xl flex items-center gap-4 border border-gray-100 relative group">
                     <div className="w-12 h-12 rounded-full bg-white border flex items-center justify-center font-black text-red-700 shadow-sm">{calon.noUrut}</div>
                     <div className="flex-1">
                        <div className="text-sm font-black text-[#25396f] uppercase truncate">{calon.nama}</div>
                        <div className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kandidat Paslon</div>
                     </div>
                     <button 
                      onClick={async () => {
                        if (window.confirm('Hapus kandidat ini?')) {
                          await axios.delete(`http://localhost:3001/api/admin/voting/calon/${calon.id}`, { headers: { Authorization: `Bearer ${token}` } });
                          fetchData();
                        }
                      }}
                      className="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity p-1 text-red-600"
                     >
                       <i className="bi bi-x-circle-fill"></i>
                     </button>
                  </div>
                ))}
                {p.pemilihanCalons?.length === 0 && <div className="col-span-4 text-center py-4 text-gray-300 italic text-sm">Belum ada kandidat yang terdaftar.</div>}
             </div>
          </div>
        ))}
        {pemilihans.length === 0 && <div className="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300 text-gray-400 font-bold uppercase tracking-widest">Belum ada periode pemilihan aktif.</div>}
      </div>

      {/* Modal Buat Pemilihan */}
      {showPemilihanModal && (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
           <div className="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-in zoom-in duration-300">
              <div className="bg-red-700 p-6 text-white text-center">
                 <h2 className="text-xl font-black uppercase italic tracking-tighter">Buat Periode Pemilihan</h2>
              </div>
              <form onSubmit={handlePemilihanSubmit} className="p-8 space-y-4">
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Judul Pemilihan</label>
                    <input className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-red-600 outline-none" 
                      value={pemilihanForm.judul} onChange={(e) => setPemilihanForm({...pemilihanForm, judul: e.target.value})} required />
                 </div>
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Deskripsi</label>
                    <textarea className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-medium focus:border-red-600 outline-none h-20 resize-none text-sm" 
                      value={pemilihanForm.deskripsi} onChange={(e) => setPemilihanForm({...pemilihanForm, deskripsi: e.target.value})} required />
                 </div>
                 <div className="grid grid-cols-2 gap-4">
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal Mulai</label>
                       <input type="date" className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-red-600 outline-none uppercase" 
                        value={pemilihanForm.tanggalMulai} onChange={(e) => setPemilihanForm({...pemilihanForm, tanggalMulai: e.target.value})} required />
                    </div>
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal Selesai</label>
                       <input type="date" className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-red-600 outline-none uppercase" 
                        value={pemilihanForm.tanggalSelesai} onChange={(e) => setPemilihanForm({...pemilihanForm, tanggalSelesai: e.target.value})} required />
                    </div>
                 </div>
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Penyelenggara (Organisasi)</label>
                    <select className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-red-600 outline-none"
                      value={pemilihanForm.organisasiId} onChange={(e) => setPemilihanForm({...pemilihanForm, organisasiId: e.target.value})} required>
                        <option value="">Pilih...</option>
                        {organisasis.map(o => <option key={o.id} value={o.id}>{o.namaOrganisasi}</option>)}
                    </select>
                 </div>
                 <div className="pt-4 flex gap-3">
                    <button type="button" onClick={() => setShowPemilihanModal(false)} className="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold hover:bg-gray-200 uppercase text-xs">Batal</button>
                    <button type="submit" className="flex-[2] bg-red-700 text-white py-3 rounded-xl font-bold hover:bg-red-800 uppercase shadow-lg text-xs">Simpan Pemilihan</button>
                 </div>
              </form>
           </div>
        </div>
      )}

      {/* Modal Tambah Kandidat */}
      {showCalonModal && (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
           <div className="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-in zoom-in duration-300">
              <div className="bg-indigo-600 p-6 text-white text-center">
                 <h2 className="text-xl font-black uppercase italic tracking-tighter">Tambah Kandidat Paslon</h2>
                 <p className="text-[10px] font-bold opacity-70 uppercase tracking-widest mt-1">Pemilihan: {selectedPemilihan?.judul}</p>
              </div>
              <form onSubmit={handleCalonSubmit} className="p-8 space-y-4">
                 <div className="grid grid-cols-3 gap-4">
                    <div className="col-span-2">
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Kandidat / Paslon</label>
                       <input className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none" 
                        value={calonForm.nama} onChange={(e) => setCalonForm({...calonForm, nama: e.target.value})} required />
                    </div>
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">No. Urut</label>
                       <input type="number" className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-black focus:border-indigo-600 outline-none text-center" 
                        value={calonForm.noUrut} onChange={(e) => setCalonForm({...calonForm, noUrut: e.target.value})} required />
                    </div>
                 </div>
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Visi & Misi Ringkas</label>
                    <textarea className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-medium focus:border-indigo-600 outline-none h-24 resize-none text-sm" 
                      value={calonForm.visiMisi} onChange={(e) => setCalonForm({...calonForm, visiMisi: e.target.value})} required />
                 </div>
                 <div className="pt-4 flex gap-3">
                    <button type="button" onClick={() => setShowCalonModal(false)} className="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold hover:bg-gray-200 uppercase text-xs">Batal</button>
                    <button type="submit" className="flex-[2] bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 uppercase shadow-lg text-xs">Simpan Kandidat</button>
                 </div>
              </form>
           </div>
        </div>
      )}
    </div>
  );
}
