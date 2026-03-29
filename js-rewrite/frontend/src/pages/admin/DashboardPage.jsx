import { useState, useEffect } from 'react';
import { useOutletContext, Link, useNavigate } from 'react-router-dom';
import axios from 'axios';
import NotificationCell from '../../components/admin/NotificationCell';

export default function DashboardPage() {
  const { setIsSidebarOpen } = useOutletContext();
  const navigate = useNavigate();
  const [loading, setLoading] = useState(true);
  const [totalBerita, setTotalBerita] = useState(0);
  const [totalDocument, setTotalDocument] = useState(0);
  const [totalSuara, setTotalSuara] = useState(0);
  const [totalSertifikat, setTotalSertifikat] = useState(0);
  const [upcomingEvents, setUpcomingEvents] = useState([]);
  const [latestBerita, setLatestBerita] = useState([]);
  const [recentMessages, setRecentMessages] = useState([]);
  const [recentDocs, setRecentDocs] = useState([]);
  const [showStatistik, setShowStatistik] = useState(false);
  const [totalSuaras, setTotalSuaras] = useState({});

  useEffect(() => {
    const fetchDashboardData = async () => {
      try {
        setLoading(true);
        const res = await axios.get('http://localhost:3001/api/admin/dashboard/stats', {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        if (res.data?.status === 'success') {
          const d = res.data.data;
          setTotalBerita(d.totalBerita || d.berita || 0);
          setTotalDocument(d.totalDocument || 0);
          setTotalSuara(d.totalSuara || 0);
          setTotalSertifikat(d.totalSertifikat || d.sertifikat || 0);
          setUpcomingEvents(d.upcomingEvents || []);
          setLatestBerita(d.latestBerita || []);
          setRecentMessages(d.recentMessages || []);
          setRecentDocs(d.recentDocs || []);
          if (d.totalSuaras && Object.keys(d.totalSuaras).length > 0) {
            setShowStatistik(true);
            setTotalSuaras(d.totalSuaras);
          }
        }
      } catch (err) {
        console.error('Error fetching dashboard data:', err);
      } finally {
        setLoading(false);
      }
    };
    fetchDashboardData();
  }, []);


  // Initialize Chart.js exactly like CI4 if needed
  useEffect(() => {
    if (!showStatistik || Object.keys(totalSuaras).length === 0) return;

    let chartInstance = null;

    const initChart = () => {
      if (typeof window.Chart === 'undefined') {
        setTimeout(initChart, 100);
        return;
      }
      
      const canvas = document.getElementById("chart-voting");
      if (!canvas) return;
      
      const ctx = canvas.getContext("2d");
      
      const labels = [];
      const datas = [];

      Object.entries(totalSuaras).forEach(([key, val]) => {
          let cleanLabel = key.split('-')[0].trim();
          cleanLabel = cleanLabel.replace('Total suara:', '').trim();
          labels.push(cleanLabel);
          datas.push(val);
      });

      const backgroundColors = [
          '#980517', '#4A6FDC', '#2E8B57', '#FF8C00', '#9932CC', '#FF6347', '#20B2AA', '#FFD700', '#9370DB', 
          '#3CB371', '#FF4500', '#4682B4', '#32CD32', '#DA70D6', '#F08080', '#1E90FF', '#9ACD32', '#BA55D3', '#5F9EA0'
      ];

      function hashString(str) {
          let hash = 0;
          for (let i = 0; i < str.length; i++) {
              const char = str.charCodeAt(i);
              hash = ((hash << 5) - hash) + char;
              hash = hash & hash;
          }
          return Math.abs(hash);
      }

      const labelColorMap = {};
      labels.forEach(label => {
          if (!labelColorMap[label]) {
              const hash = hashString(label);
              labelColorMap[label] = backgroundColors[hash % backgroundColors.length];
          }
      });

      const pieColors = labels.map(label => labelColorMap[label] || backgroundColors[0]);

      chartInstance = new window.Chart(ctx, {
          type: 'pie',
          data: {
              labels: labels,
              datasets: [{
                  data: datas,
                  backgroundColor: pieColors,
                  borderColor: '#ffffff',
                  borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                  legend: {
                      position: 'right',
                      labels: {
                          usePointStyle: true,
                          pointStyle: 'circle',
                          font: { size: 12, weight: 'bold' }
                      }
                  },
                  tooltip: {
                      callbacks: {
                          label: function (context) {
                              let label = context.label || '';
                              if (label) label += ': ';
                              const value = context.raw;
                              const total = context.chart._metasets[context.datasetIndex].total;
                              const percentage = Math.round((value / total) * 100) + '%';
                              return label + value + ' (' + percentage + ')';
                          }
                      }
                  }
              },
              cutout: '30%'
          }
      });
    };

    if (typeof window.Chart === 'undefined') {
      const script = document.createElement('script');
      script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
      script.onload = initChart;
      document.body.appendChild(script);
    } else {
      initChart();
    }

    return () => {
      if (chartInstance) chartInstance.destroy();
    };
  }, [showStatistik, totalSuaras]);

  return (
    <>
      <style>{`
        .card-widget { border: 1px solid #e0e0e0; border-top: 2px solid #d0d0d0; box-shadow: 0 2px 8px rgba(0,0,0,.08); border-radius: 12px; background: #fff; overflow: hidden; margin-bottom: 1.5rem; }
        .card-widget .card-header { background: transparent; border-bottom: 1px solid #f0f0f0; padding: 1rem 1.5rem; font-weight: 600; color: #333; display: flex; justify-content: space-between; align-items: center; }
        .card-widget .card-body { padding: 1.5rem; }
        .widget-list-item { display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #f3f4f6; }
        .widget-list-item:last-child { border-bottom: none; padding-bottom: 0; }
        .widget-list-item:first-child { padding-top: 0; }
        .widget-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 1rem; }
        .chart-container { height: 400px; position: relative; }
        .custom-table { border: 2px solid #dee2e6 !important; }
        .custom-table th, .custom-table td { border: 1px solid #dee2e6 !important; }
        .custom-table thead th { border-bottom: 2px solid #dee2e6 !important; }
      `}</style>


      <div>
        <div className="page-heading mb-4">

            <h3>Dashboard Overview</h3>
            <p className="text-subtitle text-muted">Ringkasan aktivitas dan status terkini organisasi Anda.</p>
        </div>

        {/* SECTION 1: KEY STATS */}
        <section className="section mb-2">
            <div className="row">
                {/* Total Berita */}
                <div className="col-6 col-lg-3 col-md-6 mb-4">
                    <div className="card h-100">
                        <div className="card-body px-4 py-4-5">
                            <div className="row">
                                <div className="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div className="stats-icon purple mb-2"><i className="bi-newspaper"></i></div>
                                </div>
                                <div className="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 className="text-muted font-semibold">Total Berita</h6>
                                    <h6 className="font-extrabold mb-0">{loading ? '-' : totalBerita}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Total Document */}
                <div className="col-6 col-lg-3 col-md-6 mb-4">
                    <div className="card h-100">
                        <div className="card-body px-4 py-4-5">
                            <div className="row">
                                <div className="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div className="stats-icon blue mb-2"><i className="bi-file-earmark-text"></i></div>
                                </div>
                                <div className="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 className="text-muted font-semibold">Total Document</h6>
                                    <h6 className="font-extrabold mb-0">{loading ? '-' : totalDocument}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Total Suara */}
                <div className="col-6 col-lg-3 col-md-6 mb-4">
                    <div className="card h-100">
                        <div className="card-body px-4 py-4-5">
                            <div className="row">
                                <div className="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div className="stats-icon red mb-2"><i className="bi-bar-chart-line"></i></div>
                                </div>
                                <div className="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 className="text-muted font-semibold">Total Suara</h6>
                                    <h6 className="font-extrabold mb-0">{loading ? '-' : totalSuara}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* E-Sertifikat */}
                <div className="col-6 col-lg-3 col-md-6 mb-4">
                    <div className="card h-100">
                        <div className="card-body px-4 py-4-5">
                            <div className="row">
                                <div className="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div className="stats-icon text-white bg-warning mb-2"><i className="bi-award"></i></div>
                                </div>
                                <div className="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 className="text-muted font-semibold">E-Sertifikat</h6>
                                    <h6 className="font-extrabold mb-0">{loading ? '-' : totalSertifikat}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {/* SECTION 2: CHARTS */}
        {showStatistik && Object.keys(totalSuaras).length > 0 && (
            <section className="section mb-4">
                <div className="card-widget h-100">
                    <div className="card-header">
                        Statistik Voting Real-Time
                    </div>
                    <div className="card-body">
                        <div className="chart-container" style={{ minHeight: '400px' }}>
                            <canvas id="chart-voting"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        )}

        {/* SECTION 3: ACTIVITY WIDGETS */}
        <section className="section">
            <div className="row">
                <div className="col-12 col-lg-8">
                    {/* Agenda */}
                    <div className="card-widget mb-4">
                        <div className="card-header border-0 pb-0">
                            <h5 className="card-title">Agenda Mendatang</h5>
                            <Link to="#" className="btn btn-sm btn-light">Lihat Semua</Link>
                        </div>
                        <div className="card-body">
                            {upcomingEvents && upcomingEvents.length > 0 ? (
                                upcomingEvents.map((evt, idx) => {
                                  const dateObj = new Date(evt.start_date);
                                  const month = dateObj.toLocaleDateString('id-ID', { month: 'short' });
                                  const day = dateObj.toLocaleDateString('id-ID', { day: '2-digit' });
                                  const time = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                                  const color = evt.event_color || 'primary';
                                  
                                  return (
                                    <div className="widget-list-item" key={idx}>
                                        <div className="d-flex align-items-center">
                                            <div className="text-center me-3 px-2 py-1 rounded border">
                                                <div className="small text-uppercase fw-bold text-muted" style={{ fontSize: '0.7rem', lineHeight: 1 }}>{month}</div>
                                                <div className="fs-5 fw-bold text-dark" style={{ lineHeight: 1 }}>{day}</div>
                                            </div>
                                            <div>
                                                <h6 className="mb-0 text-dark">{evt.event_title}</h6>
                                                <div className="small text-muted">
                                                    <i className="bi bi-building me-1"></i>{evt.user_name || 'Organisasi'}
                                                </div>
                                            </div>
                                        </div>
                                        <span className="badge bg-light text-secondary rounded-pill">{time} WIB</span>
                                    </div>
                                  );
                                })
                            ) : (
                                <div className="text-center py-4 text-muted">Belum ada agenda mendatang.</div>
                            )}
                        </div>
                    </div>

                    {/* Berita */}
                    <div className="card-widget mb-4">
                        <div className="card-header border-0 pb-0">
                            <h5 className="card-title">Berita Terbaru</h5>
                        </div>
                        <div className="card-body">
                            {latestBerita && latestBerita.length > 0 ? (
                                <div className="table-responsive">
                                    <table className="table table-hover mb-0 custom-table">
                                        <thead>
                                            <tr>
                                                <th style={{ width: '50px' }} className="d-none d-md-table-cell">No</th>
                                                <th>Nama Organisasi</th>
                                                <th className="d-none d-md-table-cell">Foto</th>
                                                <th>Nama Kegiatan</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {latestBerita.map((news, idx) => (
                                                <tr key={idx}>
                                                    <td className="d-none d-md-table-cell">{idx + 1}</td>
                                                    <td><span className="fw-bold">{news.user_name || 'Admin'}</span></td>
                                                    <td className="d-none d-md-table-cell">
                                                        {news.gambar ? (
                                                            <img src={`http://localhost:3001/uploads/berita/${news.gambar}`} alt="Foto"
                                                                style={{ width: '40px', height: '40px', objectFit: 'cover', borderRadius: '4px' }} />
                                                        ) : (
                                                            <div className="bg-light d-flex align-items-center justify-content-center rounded" style={{ width: '40px', height: '40px' }}>
                                                                <i className="bi bi-image text-muted"></i>
                                                            </div>
                                                        )}
                                                    </td>
                                                    <td>{news.nama_kegiatan}</td>
                                                    <td>{new Date(news.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <div className="text-center py-4 text-muted">Belum ada berita.</div>
                            )}
                        </div>
                    </div>
                </div>

                <div className="col-12 col-lg-4">
                    {/* Aspirasi */}
                    <div className="card-widget mb-4">
                        <div className="card-header border-0 pb-0">
                            <h5 className="card-title">Aspirasi Terbaru</h5>
                            <Link to="/admin/pesan" className="btn btn-sm btn-light">Inbox</Link>
                        </div>
                        <div className="card-body">
                            {recentMessages && recentMessages.length > 0 ? (
                                recentMessages.map((msg, idx) => (
                                    <div className="widget-list-item align-items-start" key={idx}>
                                        <div className="d-flex align-items-start w-100">
                                            <div className="widget-icon bg-light-primary text-primary mt-1" style={{ minWidth: '40px' }}>
                                                <i className="bi bi-chat-quote-fill"></i>
                                            </div>
                                            <div className="flex-grow-1 ms-2" style={{ minWidth: 0 }}>
                                                <div className="d-flex justify-content-between">
                                                    <h6 className="mb-0 text-truncate" title={msg.subject}>{msg.subject}</h6>
                                                    <small className="text-muted ms-2" style={{ whiteSpace: 'nowrap' }}>
                                                      {new Date(msg.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit' })}
                                                    </small>
                                                </div>
                                                <p className="small text-muted mb-1 text-truncate">{msg.name}</p>
                                                <p className="small text-secondary mb-1 text-truncate" style={{ opacity: 0.8 }}>
                                                    {msg.message ? msg.message.replace(/(<([^>]+)>)/gi, '').substring(0, 50) + '...' : ''}
                                                </p>
                                                <Link to="#" className="text-decoration-none small fw-bold">Balas <i className="bi bi-arrow-right"></i></Link>
                                            </div>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="text-center py-4 text-muted">Belum ada aspirasi masuk.</div>
                            )}
                        </div>
                    </div>

                    {/* Dokumen Terbaru */}
                    <div className="card-widget mb-4">
                        <div className="card-header border-0 pb-0">
                            <h5 className="card-title">Dokumen Terbaru</h5>
                            <Link to="#" className="btn btn-sm btn-light">Arsip</Link>
                        </div>
                        <div className="card-body">
                            {recentDocs && recentDocs.length > 0 ? (
                                recentDocs.map((doc, idx) => {
                                    const ext = doc.file_path ? doc.file_path.split('.').pop().toLowerCase() : '';
                                    let iconClass = 'bi-file-earmark-text';
                                    let bgClass = 'bg-light-secondary';
                                    let textClass = 'text-secondary';
                                    
                                    if (['pdf'].includes(ext)) { iconClass = 'bi-file-pdf'; bgClass = 'bg-light-danger'; textClass = 'text-danger'; }
                                    else if (['doc', 'docx'].includes(ext)) { iconClass = 'bi-file-word'; bgClass = 'bg-light-primary'; textClass = 'text-primary'; }

                                    return (
                                        <div className="widget-list-item" key={idx}>
                                            <div className="d-flex align-items-center w-100">
                                                <div className={`widget-icon ${bgClass} ${textClass}`}>
                                                    <i className={`bi ${iconClass}`}></i>
                                                </div>
                                                <div className="flex-grow-1" style={{ minWidth: 0 }}>
                                                    <h6 className="mb-0 text-truncate" title={doc.judul}>{doc.judul}</h6>
                                                    <div className="small text-muted">
                                                        {doc.kategori || 'Umum'} • {new Date(doc.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })}
                                                    </div>
                                                    <div className="small text-muted">
                                                        <i className="bi bi-building me-1"></i>{doc.user_name || 'Organisasi'}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    );
                                })
                            ) : (
                                <div className="text-center py-4 text-muted">Belum ada dokumen.</div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div className="mb-5 pb-5"></div>
      </div>
    </>
  );
}
