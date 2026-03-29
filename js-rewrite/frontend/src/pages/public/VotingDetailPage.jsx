import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import axios from 'axios';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Pie } from 'react-chartjs-2';
import Swal from 'sweetalert2';

ChartJS.register(ArcElement, Tooltip, Legend);

export default function VotingDetailPage() {
  const { id } = useParams();
  const [pemilihan, setPemilihan] = useState(null);
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [selectedCalonId, setSelectedCalonId] = useState(null);

  // Form State
  const [nim, setNim] = useState('');
  const [nama, setNama] = useState('');
  const [email, setEmail] = useState('');
  const [otp, setOtp] = useState('');
  const [otpSent, setOtpSent] = useState(false);
  const [isProcessing, setIsProcessing] = useState(false);

  useEffect(() => {
    fetchData();
  }, [id]);

  const fetchData = async () => {
    try {
      setLoading(true);
      const [resDetail, resStats] = await Promise.all([
        axios.get(`http://localhost:3001/api/voting/${id}`),
        axios.get(`http://localhost:3001/api/voting/${id}/stats`)
      ]);
      
      if (resDetail.data.status === 'success') setPemilihan(resDetail.data.data);
      if (resStats.data.status === 'success') setStats(resStats.data.data);
    } catch (err) {
      console.error('Error fetching voting detail:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleRequestOtp = async () => {
    if (!nim || !nama || !email) {
      alert('Mohon isi NIM, Nama, dan Email Kampus.');
      return;
    }
    
    try {
      setIsProcessing(true);
      const res = await axios.post(`http://localhost:3001/api/voting/request-otp`, { nim, email, pemilihanId: id });
      if (res.data.success) {
        setOtpSent(true);
        Swal.fire('OTP Terkirim!', 'Silakan cek email Anda (Coba gunakan 123456 untuk demo).', 'success');
      }
    } catch (err) {
      Swal.fire('Error', 'Gagal mengirim OTP.', 'error');
    } finally {
      setIsProcessing(false);
    }
  };

  const handleVote = async () => {
    if (!otp) return alert('Masukkan kode OTP.');
    
    try {
      setIsProcessing(true);
      const res = await axios.post(`http://localhost:3001/api/voting/vote`, {
        nim, nama, email, otp, pemilihanId: id, calonId: selectedCalonId
      });
      
      if (res.data.status === 'success') {
        setShowModal(false);
        Swal.fire('Berhasil!', 'Suara Anda telah tercatat.', 'success');
        fetchData(); // Refresh stats
      }
    } catch (err) {
      Swal.fire('Gagal', err.response?.data?.message || 'Terjadi kesalahan saat voting.', 'error');
    } finally {
      setIsProcessing(false);
    }
  };

  const openVoteModal = (calonId) => {
    setSelectedCalonId(calonId);
    setShowModal(true);
  };

  if (loading) return <div className="flex justify-center py-40"><div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-red-700"></div></div>;
  if (!pemilihan) return <div className="text-center py-40">Data tidak ditemukan.</div>;

  const chartData = {
    labels: stats?.candidates.map(c => `Paslon ${c.noUrut}: ${c.nama}`) || [],
    datasets: [{
      data: stats?.candidates.map(c => c._count.pemilihanCalonSuaras) || [],
      backgroundColor: ['#A01D1D', '#1e40af', '#15803d', '#b45309', '#6d28d9'],
      borderWidth: 0
    }]
  };

  return (
    <div className="bg-white min-h-screen pt-24 pb-20 font-sans">
      <style>{`
        .section-title { font-size: 2rem; font-weight: 900; color: #1f2937; margin-bottom: 2rem; text-transform: uppercase; letter-spacing: -0.05em; }
        .candidate-card { border: 2px solid #f3f4f6; border-radius: 24px; padding: 30px; transition: all 0.3s; }
        .candidate-card:hover { border-color: #A01D1D; box-shadow: 0 10px 40px rgba(160,29,29,0.05); }
        .btn-action { background: #A01D1D; color: white; padding: 12px 30px; border-radius: 50px; font-weight: 800; display: inline-block; transition: all 0.3s; border: none; cursor: pointer; }
        .btn-action:hover { background: #7d1616; transform: scale(1.05); }
        .btn-action:disabled { opacity: 0.5; transform: none; }
        .stat-box { background: #f9fafb; border-radius: 20px; padding: 40px; }
      `}</style>

      <div className="container mx-auto px-4">
        {/* Header Section */}
        <div className="text-center mb-16">
           <h1 className="text-4xl md:text-6xl font-black text-gray-900 mb-4 tracking-tighter uppercase italic">{pemilihan.judul}</h1>
           <div className="h-2 w-32 bg-red-700 mx-auto mb-6"></div>
           <p className="text-gray-500 max-w-2xl mx-auto text-lg">{pemilihan.deskripsi}</p>
        </div>

        {/* Stats & Overview */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20 items-center">
           <div className="stat-box">
              <h2 className="section-title">Hasil Sementara</h2>
              <div className="h-[350px] relative">
                 <Pie data={chartData} options={{ maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }} />
              </div>
              <div className="mt-8 text-center">
                 <span className="text-4xl font-black text-red-700">{stats?.totalSuara || 0}</span>
                 <p className="text-gray-400 font-bold uppercase tracking-widest text-sm">Total Suara Masuk</p>
              </div>
           </div>
           
           <div className="space-y-6">
              <h2 className="section-title">Informasi Pemilihan</h2>
              <div className="space-y-4">
                 {[
                   { label: 'Organisasi', value: pemilihan.organisasi?.namaOrganisasi },
                   { label: 'Status', value: pemilihan.status === 'active' ? 'SEDANG BERLANGSUNG' : 'BERAKHIR' },
                   { label: 'Ketentuan', value: 'Mahasiswa aktif Horizon University (NIM Terdaftar).' }
                 ].map((item, idx) => (
                   <div key={idx} className="flex justify-between border-b pb-4">
                      <span className="font-bold text-gray-400 uppercase text-sm tracking-widest">{item.label}</span>
                      <span className="font-black text-gray-800">{item.value}</span>
                   </div>
                 ))}
              </div>
              <div className="bg-yellow-50 border-l-4 border-yellow-400 p-6 mt-6 rounded-r-2xl">
                 <p className="text-yellow-800 text-sm leading-relaxed">
                   <strong>Penting:</strong> Pastikan Anda menggunakan email institusi <code>@krw.horizon.ac.id</code> untuk menerima kode OTP verifikasi suara.
                 </p>
              </div>
           </div>
        </div>

        {/* Candidates Section */}
        <h2 className="section-title text-center mb-12">Daftar Kandidat</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
           {pemilihan.pemilihanCalons.map((calon) => (
             <div key={calon.id} className="candidate-card bg-white flex flex-col items-center text-center">
                <div className="w-48 h-48 rounded-full bg-gray-100 mb-8 overflow-hidden border-4 border-white shadow-xl">
                   <img 
                    src={calon.gambar ? `/uploads/calon/${calon.gambar}` : '/dist/landing/assets/img/logo1.png'} 
                    alt={calon.nama} 
                    className="w-full h-full object-cover"
                    onError={(e) => e.target.src = '/dist/landing/assets/img/logo1.png'}
                   />
                </div>
                <div className="mb-2 text-red-700 font-black text-xl">NOMOR URUT {calon.noUrut}</div>
                <h3 className="text-2xl font-black text-gray-800 mb-6 uppercase tracking-tight leading-tight">{calon.nama}</h3>
                <div className="text-gray-500 mb-10 italic leading-relaxed whitespace-pre-wrap flex-1">
                   "{calon.visiMisi || 'Visi & Misi sedang dipersiapkan oleh kandidat.'}"
                </div>
                <button 
                  onClick={() => openVoteModal(calon.id)}
                  className="btn-action w-full"
                >
                   PILIH PASLON {calon.noUrut}
                </button>
             </div>
           ))}
        </div>
      </div>

      {/* Vote Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black/80 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
           <div className="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
              <div className="bg-red-700 p-8 text-white text-center">
                 <h2 className="text-2xl font-black uppercase italic tracking-tighter">Bilik Suara Digital</h2>
                 <p className="opacity-80 text-sm mt-2">Verifikasi identitas Anda untuk memberikan suara.</p>
              </div>
              <div className="p-8 space-y-5">
                 <div>
                    <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input 
                      className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-red-600 outline-none transition-all font-bold"
                      value={nama} onChange={(e) => setNama(e.target.value)}
                      disabled={otpSent}
                    />
                 </div>
                 <div>
                    <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">NIM Mahasiswa</label>
                    <input 
                      className="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-red-600 outline-none transition-all font-bold"
                      value={nim} onChange={(e) => setNim(e.target.value)}
                      disabled={otpSent}
                    />
                 </div>
                 <div>
                    <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Kampus (@krw.horizon.ac.id)</label>
                    <div className="flex gap-2">
                       <input 
                        className="flex-1 bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-red-600 outline-none transition-all font-bold"
                        value={email} onChange={(e) => setEmail(e.target.value)}
                        disabled={otpSent}
                       />
                       {!otpSent && (
                         <button 
                          onClick={handleRequestOtp} 
                          disabled={isProcessing}
                          className="bg-gray-800 text-white px-4 rounded-xl font-bold text-xs uppercase hover:bg-black transition-colors"
                         >
                           OTP
                         </button>
                       )}
                    </div>
                 </div>
                 {otpSent && (
                   <div className="animate-in slide-in-from-top-4 duration-300">
                      <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Digit OTP (6 Angka)</label>
                      <input 
                        className="w-full bg-red-50 border-2 border-red-200 rounded-xl px-4 py-3 focus:border-red-600 outline-none transition-all text-center text-2xl font-black tracking-[1em]"
                        maxLength={6}
                        value={otp} onChange={(e) => setOtp(e.target.value)}
                      />
                      <p className="text-center text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">Sent to {email}</p>
                   </div>
                 )}
                 
                 <div className="pt-4 flex gap-4">
                    <button 
                      onClick={() => setShowModal(false)}
                      className="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold hover:bg-gray-200 transition-all uppercase text-sm"
                    >
                       Batal
                    </button>
                    <button 
                      onClick={handleVote}
                      disabled={!otpSent || isProcessing}
                      className="flex-[2] btn-action"
                    >
                       {isProcessing ? 'PROSES...' : 'SUBMIT SUARA GUE!'}
                    </button>
                 </div>
              </div>
           </div>
        </div>
      )}
    </div>
  );
}
