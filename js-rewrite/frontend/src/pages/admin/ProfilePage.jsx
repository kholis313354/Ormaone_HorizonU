import { useState, useEffect } from 'react';
import axios from 'axios';
import { useOutletContext, useNavigate } from 'react-router-dom';
import Swal from 'sweetalert2';

export default function ProfilePage() {
  const ctx = useOutletContext() || {};
  const API = ctx.API || 'http://localhost:3001';
  const token = localStorage.getItem('token');
  const navigate = useNavigate();

  const [user, setUser] = useState(() => JSON.parse(localStorage.getItem('user') || '{}'));
  const [profileData, setProfileData] = useState({ name: user.name || '', email: user.email || '' });
  const [passwordData, setPasswordData] = useState({ currentPassword: '', newPassword: '', confirmPassword: '' });

  const handleProfileSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await axios.put(`${API}/api/admin/profile`, profileData, {
        headers: { Authorization: `Bearer ${token}` }
      });
      if (res.data.status === 'success') {
        Swal.fire('Sukses', 'Profil berhasil diperbarui', 'success');
        const updatedUser = { ...user, ...res.data.data };
        localStorage.setItem('user', JSON.stringify(updatedUser));
        setUser(updatedUser);
      }
    } catch (err) {
      console.error(err);
      Swal.fire('Gagal', err.response?.data?.message || 'Gagal memperbarui profil', 'error');
    }
  };

  const handlePasswordSubmit = async (e) => {
    e.preventDefault();
    if (passwordData.newPassword !== passwordData.confirmPassword) {
      return Swal.fire('Gagal', 'Konfirmasi password baru tidak cocok', 'error');
    }
    
    try {
      const res = await axios.put(`${API}/api/admin/profile/password`, passwordData, {
        headers: { Authorization: `Bearer ${token}` }
      });
      if (res.data.status === 'success') {
        Swal.fire('Sukses', 'Password berhasil diperbarui. Silakan login kembali.', 'success');
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        navigate('/login');
      }
    } catch (err) {
      console.error(err);
      Swal.fire('Gagal', err.response?.data?.message || 'Gagal memperbarui password', 'error');
    }
  };

  return (
    <>
      <div className="pagetitle">
        <h1>Profile</h1>
        <nav>
          <ol className="breadcrumb">
            <li className="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li className="breadcrumb-item">Users</li>
            <li className="breadcrumb-item active">Profile</li>
          </ol>
        </nav>
      </div>

      <section className="section profile">
        <div className="row">
          
          <div className="col-xl-4">
            <div className="card">
              <div className="card-body profile-card pt-4 d-flex flex-column align-items-center">
                <img src={user.profilePhoto || "/dist/assets/compiled/jpg/1.jpg"} alt="Profile" className="rounded-circle" style={{ width: '120px', height: '120px', objectFit: 'cover' }} />
                <h2 className="mt-3">{user.name}</h2>
                <h3>{user.level === 1 ? 'Admin' : (user.level === 2 ? 'Superadmin' : 'Member')}</h3>
              </div>
            </div>
          </div>

          <div className="col-xl-8">
            <div className="card">
              <div className="card-body pt-3">
                
                {/* Bordered Tabs */}
                <ul className="nav nav-tabs nav-tabs-bordered">
                  <li className="nav-item">
                    <button className="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profil</button>
                  </li>
                  <li className="nav-item">
                    <button className="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Ubah Password</button>
                  </li>
                </ul>

                <div className="tab-content pt-2">

                  <div className="tab-pane fade show active profile-edit pt-3" id="profile-edit">
                    <form onSubmit={handleProfileSubmit}>
                      <div className="row mb-3">
                        <label htmlFor="fullName" className="col-md-4 col-lg-3 col-form-label">Nama Lengkap</label>
                        <div className="col-md-8 col-lg-9">
                          <input type="text" className="form-control" id="fullName" value={profileData.name} onChange={e => setProfileData({...profileData, name: e.target.value})} required/>
                        </div>
                      </div>
                      
                      <div className="row mb-3">
                        <label htmlFor="Email" className="col-md-4 col-lg-3 col-form-label">Alamat Email</label>
                        <div className="col-md-8 col-lg-9">
                          <input type="email" className="form-control" id="Email" value={profileData.email} onChange={e => setProfileData({...profileData, email: e.target.value})} required/>
                        </div>
                      </div>

                      <div className="text-center mt-4">
                        <button type="submit" className="btn btn-primary">Simpan Perubahan Profil</button>
                      </div>
                    </form>
                  </div>

                  <div className="tab-pane fade pt-3" id="profile-change-password">
                    <form onSubmit={handlePasswordSubmit}>
                      <div className="row mb-3">
                        <label className="col-md-4 col-lg-3 col-form-label">Password Saat Ini</label>
                        <div className="col-md-8 col-lg-9">
                          <input type="password" required className="form-control" value={passwordData.currentPassword} onChange={e => setPasswordData({...passwordData, currentPassword: e.target.value})} />
                        </div>
                      </div>

                      <div className="row mb-3">
                        <label className="col-md-4 col-lg-3 col-form-label">Password Baru</label>
                        <div className="col-md-8 col-lg-9">
                          <input type="password" required className="form-control" minLength="8" value={passwordData.newPassword} onChange={e => setPasswordData({...passwordData, newPassword: e.target.value})} />
                        </div>
                      </div>

                      <div className="row mb-3">
                        <label className="col-md-4 col-lg-3 col-form-label">Konfirmasi Password</label>
                        <div className="col-md-8 col-lg-9">
                          <input type="password" required className="form-control" minLength="8" value={passwordData.confirmPassword} onChange={e => setPasswordData({...passwordData, confirmPassword: e.target.value})} />
                        </div>
                      </div>

                      <div className="text-center mt-4">
                        <button type="submit" className="btn btn-primary">Ubah Password</button>
                      </div>
                    </form>
                  </div>

                </div>
              </div>
            </div>
          </div>
          
        </div>
      </section>
    </>
  );
}
