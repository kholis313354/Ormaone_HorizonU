import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import { useNavigate } from 'react-router-dom';

export default function UserManagement() {
  const navigate = useNavigate();
  const [users, setUsers] = useState([]);
  const [fakultas, setFakultas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editingUser, setEditingUser] = useState(null);

  // Form State
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    level: 'admin',
    fakultasId: ''
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
      const [resUsers, resFakultas] = await Promise.all([
        axios.get('http://localhost:3001/api/admin/users', { headers: { Authorization: `Bearer ${token}` } }),
        axios.get('http://localhost:3001/api/master/fakultas')
      ]);
      if (resUsers.data.status === 'success') setUsers(resUsers.data.data);
      if (resFakultas.data.status === 'success') setFakultas(resFakultas.data.data);
    } catch (err) {
      console.error('Error fetching data:', err);
      if (err.response?.status === 401) {
        localStorage.removeItem('token');
        navigate('/login');
      }
    } finally {
      setLoading(false);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingUser) {
        await axios.put(`http://localhost:3001/api/admin/users/${editingUser.id}`, formData, {
          headers: { Authorization: `Bearer ${token}` }
        });
        Swal.fire('Berhasil', 'User diperbarui', 'success');
      } else {
        await axios.post('http://localhost:3001/api/admin/users', formData, {
          headers: { Authorization: `Bearer ${token}` }
        });
        Swal.fire('Berhasil', 'User baru ditambahkan', 'success');
      }
      setShowModal(false);
      resetForm();
      fetchData();
    } catch (err) {
      Swal.fire('Error', err.response?.data?.message || 'Gagal menyimpan user', 'error');
    }
  };

  const handleEdit = (user) => {
    setEditingUser(user);
    setFormData({
      name: user.name,
      email: user.email,
      password: '', // Biarkan kosong jika tidak ganti
      level: user.level,
      fakultasId: user.fakultasId || ''
    });
    setShowModal(true);
  };

  const handleDelete = async (id) => {
    const result = await Swal.fire({
      title: 'Yakin hapus user?',
      text: "Tindakan ini tidak bisa dibatalkan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!'
    });

    if (result.isConfirmed) {
      try {
        await axios.delete(`http://localhost:3001/api/admin/users/${id}`, {
          headers: { Authorization: `Bearer ${token}` }
        });
        Swal.fire('Terhapus!', 'User telah dihapus.', 'success');
        fetchData();
      } catch (err) {
        Swal.fire('Gagal', 'Gagal menghapus user', 'error');
      }
    }
  };

  const resetForm = () => {
    setEditingUser(null);
    setFormData({ name: '', email: '', password: '', level: 'admin', fakultasId: '' });
  };

  if (loading) return <div className="flex justify-center py-20"><div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600"></div></div>;

  return (
    <div className="p-8 bg-[#f2f7ff] min-h-screen font-sans">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-3xl font-black text-[#25396f] uppercase tracking-tight">Manajemen User</h1>
          <p className="text-gray-400">Total {users.length} pengguna terdaftar di sistem.</p>
        </div>
        <button 
          onClick={() => { resetForm(); setShowModal(true); }}
          className="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg flex items-center gap-2"
        >
          <i className="bi bi-person-plus text-xl"></i> TAMBAH USER
        </button>
      </div>

      <div className="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table className="w-full text-left border-collapse">
          <thead>
            <tr className="bg-gray-50 border-b border-gray-100">
              <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Nama / Email</th>
              <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Role</th>
              <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Fakultas</th>
              <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            {users.map((user) => (
              <tr key={user.id} className="border-b border-gray-50 hover:bg-indigo-50/30 transition-all">
                <td className="px-6 py-4">
                  <div className="font-bold text-[#25396f]">{user.name}</div>
                  <div className="text-xs text-gray-400">{user.email}</div>
                </td>
                <td className="px-6 py-4">
                  <span className={`px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider ${user.level === 'admin' ? 'bg-indigo-100 text-indigo-600' : 'bg-orange-100 text-orange-600'}`}>
                    {user.level}
                  </span>
                </td>
                <td className="px-6 py-4 text-sm font-bold text-gray-500">
                  {user.fakultas?.namaFakultas || '-'}
                </td>
                <td className="px-6 py-4 text-right">
                   <div className="flex justify-end gap-2">
                      <button onClick={() => handleEdit(user)} className="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100"><i className="bi bi-pencil-square"></i></button>
                      <button onClick={() => handleDelete(user.id)} className="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100"><i className="bi bi-trash3"></i></button>
                   </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Modal CRUD User */}
      {showModal && (
        <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
           <div className="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-in zoom-in duration-300">
              <div className="bg-indigo-600 p-6 text-white text-center">
                 <h2 className="text-xl font-black uppercase italic tracking-tighter">{editingUser ? 'Edit User' : 'Tambah User Baru'}</h2>
              </div>
              <form onSubmit={handleSubmit} className="p-8 space-y-4">
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</label>
                    <input className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none" 
                      value={formData.name} onChange={(e) => setFormData({...formData, name: e.target.value})} required />
                 </div>
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Email</label>
                    <input type="email" className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none" 
                      value={formData.email} onChange={(e) => setFormData({...formData, email: e.target.value})} required />
                 </div>
                 <div>
                    <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Password {editingUser && '(Kosongkan jika tidak ganti)'}</label>
                    <input type="password" className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none" 
                      value={formData.password} onChange={(e) => setFormData({...formData, password: e.target.value})} required={!editingUser} />
                 </div>
                 <div className="grid grid-cols-2 gap-4">
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Role</label>
                       <select className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none"
                        value={formData.level} onChange={(e) => setFormData({...formData, level: e.target.value})}>
                          <option value="admin">Admin</option>
                          <option value="superadmin">Superadmin</option>
                       </select>
                    </div>
                    <div>
                       <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Fakultas</label>
                       <select className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-2 font-bold focus:border-indigo-600 outline-none"
                        value={formData.fakultasId} onChange={(e) => setFormData({...formData, fakultasId: e.target.value})} required>
                          <option value="">Pilih...</option>
                          {fakultas.map(f => <option key={f.id} value={f.id}>{f.id} - {f.namaFakultas}</option>)}
                       </select>
                    </div>
                 </div>
                 <div className="pt-4 flex gap-3">
                    <button type="button" onClick={() => setShowModal(false)} className="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold hover:bg-gray-200 uppercase text-xs">Batal</button>
                    <button type="submit" className="flex-[2] bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 uppercase shadow-lg text-xs">Simpan</button>
                 </div>
              </form>
           </div>
        </div>
      )}
    </div>
  );
}
