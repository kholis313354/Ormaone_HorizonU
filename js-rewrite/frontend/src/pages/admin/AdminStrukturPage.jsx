import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useNavigate } from 'react-router-dom';

export default function AdminStrukturPage() {
  const navigate = useNavigate();
  const [organisasis, setOrganisasis] = useState([]);
  const [selectedOrgId, setSelectedOrgId] = useState('');
  const [strukturData, setStrukturData] = useState({ tampilan: null, divisi: [] });
  const [loading, setLoading] = useState(true);
  const [isSaving, setIsSaving] = useState(false);

  // Form Tampilan State (Pimpinan)
  const [tampilanForm, setTampilanForm] = useState({
    jabatan1: '', nama1: '', jabatan2: '', nama2: '', jabatan3: '', nama3: '', jabatan4: '', nama4: '', tahun: new Date().getFullYear().toString()
  });

  // Form Divisi State
  const [newDivisi, setNewDivisi] = useState({ namaDivisi: '', namaAnggota: '' });

  const token = localStorage.getItem('token');

  useEffect(() => {
    if (!token) return navigate('/login');
    fetchInitialData();
  }, [token, navigate]);

  useEffect(() => {
    if (selectedOrgId) fetchStruktur(selectedOrgId);
  }, [selectedOrgId]);

  const fetchInitialData = async () => {
    try {
      const res = await axios.get('http://localhost:3001/api/admin/organisasi', { headers: { Authorization: `Bearer ${token}` } });
      if (res.data.status === 'success') {
        setOrganisasis(res.data.data);
        if (res.data.data.length > 0) setSelectedOrgId(res.data.data[0].id);
      }
    } catch (err) { console.error(err); }
    finally { setLoading(false); }
  };

  const fetchStruktur = async (id) => {
    try {
      const res = await axios.get(`http://localhost:3001/api/struktur/${id}`);
      if (res.data.status === 'success') {
        const { tampilan, divisi } = res.data.data;
        setStrukturData({ tampilan, divisi });
        if (tampilan) {
          setTampilanForm({
            jabatan1: tampilan.jabatan1 || '', nama1: tampilan.nama1 || '',
            jabatan2: tampilan.jabatan2 || '', nama2: tampilan.nama2 || '',
            jabatan3: tampilan.jabatan3 || '', nama3: tampilan.nama3 || '',
            jabatan4: tampilan.jabatan4 || '', nama4: tampilan.nama4 || '',
            tahun: tampilan.tahun || new Date().getFullYear().toString()
          });
        }
      }
    } catch (err) { console.error(err); }
  };

  const handleUpdateTampilan = async (e) => {
    e.preventDefault();
    try {
      setIsSaving(true);
      await axios.put(`http://localhost:3001/api/admin/struktur/${selectedOrgId}/tampilan`, tampilanForm, {
        headers: { Authorization: `Bearer ${token}` }
      });
      Swal.fire('Berhasil', 'Struktur pimpinan diperbarui', 'success');
      fetchStruktur(selectedOrgId);
    } catch (err) { Swal.fire('Error', 'Gagal update pimpinan', 'error'); }
    finally { setIsSaving(false); }
  };

  const handleCreateDivisi = async (e) => {
    e.preventDefault();
    try {
      await axios.post(`http://localhost:3001/api/admin/struktur/divisi`, { ...newDivisi, organisasiId: selectedOrgId, tahun: tampilanForm.tahun }, {
        headers: { Authorization: `Bearer ${token}` }
      });
      Swal.fire('Berhasil', 'Divisi baru ditambahkan', 'success');
      setNewDivisi({ namaDivisi: '', namaAnggota: '' });
      fetchStruktur(selectedOrgId);
    } catch (err) { Swal.fire('Error', 'Gagal tambah divisi', 'error'); }
  };

  const handleDeleteDivisi = async (id) => {
    if (window.confirm('Hapus divisi ini?')) {
      await axios.delete(`http://localhost:3001/api/admin/struktur/divisi/${id}`, { headers: { Authorization: `Bearer ${token}` } });
      fetchStruktur(selectedOrgId);
    }
  };

  if (loading) return <div className="flex justify-center py-20"><div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-red-600"></div></div>;

  return (
    <div className="p-8 bg-[#f2f7ff] min-h-screen font-sans">
      <div className="flex justify-between items-center mb-10">
        <div>
          <h1 className="text-3xl font-black text-[#25396f] uppercase tracking-tight">Struktur Pengurus</h1>
          <p className="text-gray-400 font-medium">Atur hierarki pimpinan dan pembagian divisi ormawa.</p>
        </div>
        <div className="flex items-center gap-3 bg-white p-2 rounded-2xl border border-gray-100 shadow-sm">
           <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Pilih Ormawa:</label>
           <select 
            className="bg-gray-50 border-none rounded-xl px-4 py-2 font-bold text-[#25396f] outline-none"
            value={selectedOrgId} onChange={(e) => setSelectedOrgId(e.target.value)}
           >
              {organisasis.map(o => <option key={o.id} value={o.id}>{o.namaOrganisasi}</option>)}
           </select>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* PIMPINAN INTI FORM */}
        <div className="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
           <div className="flex items-center gap-2 mb-8">
              <div className="bg-red-50 text-red-700 w-10 h-10 rounded-xl flex items-center justify-center text-xl"><i className="bi bi-person-badge-fill"></i></div>
              <h2 className="text-xl font-black text-[#25396f] uppercase tracking-tighter">Pimpinan Inti</h2>
           </div>

           <form onSubmit={handleUpdateTampilan} className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                 {[1, 2, 3, 4].map(i => (
                   <div key={i} className="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                      <label className="block text-[10px] font-black text-red-700 uppercase tracking-widest mb-2">Jabatan {i}</label>
                      <input className="w-full bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-xs font-bold mb-2 outline-none focus:border-red-600" 
                        placeholder="Contoh: Ketua Umum" value={tampilanForm[`jabatan${i}`]} onChange={(e) => setTampilanForm({...tampilanForm, [`jabatan${i}`]: e.target.value})} />
                      <input className="w-full bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-xs font-bold outline-none focus:border-red-600" 
                        placeholder="Nama Lengkap" value={tampilanForm[`nama${i}`]} onChange={(e) => setTampilanForm({...tampilanForm, [`nama${i}`]: e.target.value})} />
                   </div>
                 ))}
              </div>
              <div className="flex justify-between items-center pt-4">
                  <div className="flex items-center gap-2">
                     <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tahun Akademik:</span>
                     <input className="w-20 bg-gray-50 border-b-2 border-red-700 text-center font-black outline-none" value={tampilanForm.tahun} onChange={(e) => setTampilanForm({...tampilanForm, tahun: e.target.value})} />
                  </div>
                  <button type="submit" disabled={isSaving} className="bg-red-700 hover:bg-red-800 text-white px-8 py-3 rounded-xl font-bold uppercase text-xs shadow-lg transition-all">
                      {isSaving ? 'Menyimpan...' : 'Update Struktur'}
                  </button>
              </div>
           </form>
        </div>

        {/* DIVISI & ANGGOTA MANAGEMENT */}
        <div className="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
           <div className="flex items-center gap-2 mb-8">
              <div className="bg-indigo-50 text-indigo-600 w-10 h-10 rounded-xl flex items-center justify-center text-xl"><i className="bi bi-people-fill"></i></div>
              <h2 className="text-xl font-black text-[#25396f] uppercase tracking-tighter">Manajemen Divisi</h2>
           </div>

           <div className="space-y-4 mb-8 max-h-[400px] overflow-y-auto pr-2">
              {strukturData.divisi.map((d) => (
                <div key={d.id} className="p-4 rounded-2xl border-2 border-indigo-50 bg-indigo-50/20 group relative">
                   <h4 className="font-black text-indigo-700 uppercase text-xs mb-1 tracking-widest">{d.namaDivisi}</h4>
                   <p className="text-xs text-gray-500 font-medium leading-relaxed">{d.namaAnggota}</p>
                   <button onClick={() => handleDeleteDivisi(d.id)} className="absolute top-2 right-2 text-red-400 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity"><i className="bi bi-x-circle-fill"></i></button>
                </div>
              ))}
              {strukturData.divisi.length === 0 && <div className="text-center py-10 text-gray-300 italic text-sm">Belum ada divisi yang ditambahkan.</div>}
           </div>

           <form onSubmit={handleCreateDivisi} className="p-6 bg-gray-50 rounded-2xl border border-gray-100 space-y-4">
              <h5 className="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">+ Tambah Divisi Baru</h5>
              <div>
                 <input className="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold outline-none focus:border-indigo-600" 
                  placeholder="Nama Divisi (Contoh: Divisi Humas)" value={newDivisi.namaDivisi} onChange={(e) => setNewDivisi({...newDivisi, namaDivisi: e.target.value})} required />
              </div>
              <div>
                 <textarea className="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-medium outline-none focus:border-indigo-600 h-20 resize-none" 
                  placeholder="Nama Anggota (Pisahkan dengan koma)" value={newDivisi.namaAnggota} onChange={(e) => setNewDivisi({...newDivisi, namaAnggota: e.target.value})} required />
              </div>
              <button type="submit" className="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl font-bold uppercase text-[10px] tracking-widest shadow-md transition-all">
                  SIMPAN DIVISI
              </button>
           </form>
        </div>
      </div>
    </div>
  );
}
