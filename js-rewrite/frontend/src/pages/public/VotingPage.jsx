import { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';

export default function VotingPage() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [countdown, setCountdown] = useState({ days: '00', hours: '00', minutes: '00', seconds: '00' });
  const chartRef = useRef(null);

  useEffect(() => {
    const fetchVoting = async () => {
      try {
        const res = await axios.get('http://localhost:3001/api/public/voting');
        if (res.data.status === 'success') setData(res.data);
      } catch (err) {
        console.error(err);
      } finally {
        setLoading(false);
      }
    };
    fetchVoting();
  }, []);

  // Countdown Logic
  useEffect(() => {
    if (!data?.pemilihan?.tanggal_akhir) return;
    
    const target = new Date(data.pemilihan.tanggal_akhir || Date.now() + 30 * 24 * 60 * 60 * 1000).getTime();
    
    const timer = setInterval(() => {
      const diff = target - Date.now();
      if (diff <= 0) {
        clearInterval(timer);
        setCountdown({ days: '00', hours: '00', minutes: '00', seconds: '00' });
        return;
      }
      setCountdown({
        days: String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0'),
        hours: String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0'),
        minutes: String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0'),
        seconds: String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0')
      });
    }, 1000);
    return () => clearInterval(timer);
  }, [data]);

  // Chart.js Injection & Initialization
  useEffect(() => {
    if (!data || loading) return;
    if (data.isSelesai) return;

    let chartInstance = null;

    const initChart = () => {
      if (typeof window.Chart === 'undefined') {
        setTimeout(initChart, 100);
        return;
      }

      if (!chartRef.current) return;
      const ctxY = chartRef.current.getContext('2d');
      
      const labels = [];
      const datas = [];
      
      if (data.totalSuaras) {
        Object.entries(data.totalSuaras).forEach(([key, val]) => {
          let cleanLabel = key.split('-')[0].trim();
          cleanLabel = cleanLabel.replace('Total suara:', '').trim();
          labels.push(cleanLabel);
          datas.push(val);
        });
      }

      const backgroundColors = [
        '#980517', '#4A6FDC', '#2E8B57', '#FF8C00', '#9932CC', 
        '#FF6347', '#20B2AA', '#FFD700', '#9370DB', '#3CB371', 
        '#FF4500', '#4682B4', '#32CD32', '#DA70D6', '#F08080', 
        '#1E90FF', '#9ACD32', '#BA55D3', '#5F9EA0'
      ];

      const pieColors = labels.map((label, idx) => backgroundColors[idx % backgroundColors.length]);

      chartInstance = new window.Chart(ctxY, {
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
              position: window.innerWidth < 576 ? 'bottom' : 'right',
              labels: {
                font: {
                  size: window.innerWidth < 576 ? 10 : 12,
                  weight: 'bold'
                },
                padding: 20,
                usePointStyle: true,
                pointStyle: 'circle'
              }
            },
            tooltip: {
              enabled: true,
              callbacks: {
                label: function(context) { return 'Total suara: ' + context.raw; }
              }
            }
          },
          cutout: window.innerWidth < 576 ? '50%' : '30%'
        }
      });
      
      const handleResize = () => {
        if (!chartInstance) return;
        const isMobile = window.innerWidth < 576;
        const isTablet = window.innerWidth < 768;
        chartInstance.options.plugins.legend.position = isMobile ? 'bottom' : 'right';
        chartInstance.options.plugins.legend.labels.font.size = isMobile ? 10 : (isTablet ? 11 : 12);
        chartInstance.options.cutout = isMobile ? '50%' : '30%';
        chartInstance.update();
      };

      window.addEventListener('resize', handleResize);
    };

    // Load Chart.js if not present
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
  }, [data, loading]);

  // Owl Carousel Injection
  useEffect(() => {
    if (loading || !data?.calon?.length) return;

    const loadOwl = () => {
      if (typeof window.jQuery === 'undefined') {
        setTimeout(loadOwl, 100);
        return;
      }
      if (typeof window.jQuery.fn.owlCarousel === 'undefined') {
        const css1 = document.createElement('link');
        css1.rel = 'stylesheet';
        css1.href = '/dist/landing/assets/lib/owlcarousel/assets/owl.carousel.min.css';
        document.head.appendChild(css1);

        const css2 = document.createElement('link');
        css2.rel = 'stylesheet';
        css2.href = '/dist/landing/assets/lib/owlcarousel/assets/owl.theme.default.min.css';
        document.head.appendChild(css2);

        const script = document.createElement('script');
        script.src = '/dist/landing/assets/lib/owlcarousel/owl.carousel.min.js';
        script.onload = initOwl;
        document.body.appendChild(script);
      } else {
        initOwl();
      }
    };

    const initOwl = () => {
      const $ = window.jQuery;
      $('.categories-carousel').each(function() {
        const $carousel = $(this);
        if (!$carousel.hasClass('owl-loaded')) {
          $carousel.owlCarousel({
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            smartSpeed: 1000,
            dots: false,
            loop: false, // Set to false to prevent cloning issues in React unless specifically needed
            margin: 25,
            nav: true,
            navText: [
              '<i class="bi bi-chevron-left"></i>',
              '<i class="bi bi-chevron-right"></i>'
            ],
            responsiveClass: true,
            responsive: {
              0: { items: 1, nav: false },
              576: { items: 2, nav: false },
              768: { items: 2, nav: true },
              992: { items: 3, nav: true },
              1200: { items: 4, nav: true }
            }
          });
        }
      });
    };

    // Ensure jQuery is available, else load it
    if (typeof window.jQuery === 'undefined') {
      const jq = document.createElement('script');
      jq.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
      jq.onload = loadOwl;
      document.body.appendChild(jq);
    } else {
      loadOwl();
    }

  }, [loading, data]);

  if (loading) {
    return (
      <div className="text-center py-5" style={{ marginTop: '20vh' }}>
        <div className="spinner-border text-danger" role="status">
          <span className="visually-hidden">Loading...</span>
        </div>
      </div>
    );
  }

  // Group candidates by organisasi_name
  const groupedCandidates = {};
  if (data?.calon && Array.isArray(data.calon)) {
    data.calon.forEach(c => {
      if (!c.organisasi_name) return;
      if (!groupedCandidates[c.organisasi_name]) groupedCandidates[c.organisasi_name] = [];
      groupedCandidates[c.organisasi_name].push(c);
    });
  }

  return (
    <>
      <style>
        {`
          /* ============================
             STYLE KANDIDAT E-VOTING
             ============================ */
          .categories { background-color: #ffffff; }
          .categories-carousel { display: flex; flex-wrap: wrap; gap: 24px; justify-content: center; opacity: 0; transition: opacity 0.5s; }
          .categories-carousel.owl-loaded { display: block; opacity: 1; }
          .categories-carousel:not(.owl-loaded) { opacity: 1; }
          
          .categories-item { background: #ffffff; border-radius: 18px; box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08); min-width: 260px; max-width: 320px; flex: 0 0 auto; transition: transform 0.2s ease, box-shadow 0.2s ease; margin-bottom: 20px;}
          .categories-item:hover { transform: translateY(-6px); box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12); }
          .categories-item-inner { height: 100%; }
          .categories-img { border-top-left-radius: 18px; border-top-right-radius: 18px; overflow: hidden; background-color: #980517; }
          .categories-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
          .categories-content { background-color: #ffffff; border-bottom-left-radius: 18px; border-bottom-right-radius: 18px; }
          .categories-content .h4 { font-size: 1rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem; text-decoration: none; }
          .categories-content .h4:hover { color: #980517; text-decoration: none; }
          .card-description { font-size: 0.9rem; color: #4b5563; line-height: 1.5; }
          .categories .btn { font-weight: 600; letter-spacing: 0.02em; }
          @media (max-width: 576px) { .categories-item { min-width: 240px; max-width: 100%; } }
        `}
      </style>

      {/* Hero Section */}
      <header className="py-5 bg-light border-bottom mb-4">
        <div className="container">
          <div className="text-center my-5">
            <br /><br /><br />
            <img src="/dist/landing/assets/img/Vector.png" className="img-fluid d-block mx-auto" alt="Sertifikat" />
            <h1 className="fw-bolder" style={{ color: '#980517' }}>E-Voting</h1>
            <p className="lead mb-0">E-Voting Organisasi Mahasiswa adalah sistem pemilihan elektronik yang dirancang
              untuk mempermudah, mempercepat, dan meningkatkan transparansi dalam proses demokrasi kampus.</p>
          </div>
          
          <div className="d-flex justify-content-center">
            <div className="container">
              <div className="row justify-content-center">
                <div className="col-md-8">
                  <div className="card">
                    <div className="card-header" style={{ backgroundColor: '#980517' }}>
                      <h4 className="mb-0 text-center text-white">Batas waktu Voting</h4>
                    </div>
                    <div className="card-body">
                      <div className="row text-center">
                        <div className="col-3">
                          <h3 id="days" className="display-4" style={{ color: '#980517' }}>{countdown.days}</h3>
                          <p>Hari</p>
                        </div>
                        <div className="col-3">
                          <h3 id="hours" className="display-4" style={{ color: '#980517' }}>{countdown.hours}</h3>
                          <p>Jam</p>
                        </div>
                        <div className="col-3">
                          <h3 id="minutes" className="display-4" style={{ color: '#980517' }}>{countdown.minutes}</h3>
                          <p>Menit</p>
                        </div>
                        <div className="col-3">
                          <h3 id="seconds" className="display-4" style={{ color: '#980517' }}>{countdown.seconds}</h3>
                          <p>Detik</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>

      {/* Card Content - Chart */}
      {!data?.isSelesai && (
        <div className="container">
          <div className="card mb-4 border-0 shadow-sm" style={{ borderRadius: '12px' }}>
            <div className="card-body p-2 p-md-4">
              <div className="chart-container p-2 bg-white rounded-3" style={{ boxShadow: '0 1px 4px rgba(0,0,0,0.05)' }}>
                <h6 className="text-center mb-1 mb-md-2 fw-semibold" style={{ color: '#333', fontSize: 'clamp(0.8rem, 2.5vw, 1rem)' }}>
                  Jumlah seluruh Suara Voting
                </h6>
                <div className="chart-wrapper" style={{ height: 'clamp(180px, 40vh, 250px)', minHeight: '180px', position: 'relative' }}>
                  <canvas id="chart-voting" ref={chartRef}></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Card Kandidat */}
      <div className="container-fluid categories pb-5">
        <div className="container pb-5">
          <div className="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style={{ maxWidth: '800px' }}>
            <h1 className="display-5 text-capitalize mb-3">Kandidat <span style={{ color: '#980517' }}>E-Voting</span></h1>
          </div>
          
          {Object.keys(groupedCandidates).length === 0 ? (
            <div className="alert alert-info text-center" role="alert">
              <h5>Tidak ada kandidat yang tersedia saat ini.</h5>
              <p>Belum ada pemilihan yang aktif atau kandidat belum terdaftar.</p>
            </div>
          ) : (
            Object.entries(groupedCandidates).map(([orgName, candidates]) => (
              <div className="mb-5" key={orgName}>
                <h2 className="text-capitalize mb-4 wow fadeInUp" data-wow-delay="0.1s">Kandidat {orgName}</h2>
                <div className="categories-carousel owl-carousel wow fadeInUp" data-wow-delay="0.1s">
                  {candidates.map((c) => (
                    <div className="categories-item p-4" key={c.id}>
                      <div className="categories-item-inner d-flex flex-column h-100">
                        <div className="categories-img rounded-top" style={{ backgroundColor: '#980517', height: '250px', overflow: 'hidden' }}>
                          <img src={`http://localhost:3001/uploads/${c.gambar_1}`} className="img-fluid w-100 h-100 object-fit-cover rounded-top" alt="" loading="lazy" decoding="async" />
                        </div>
                        <div className="categories-content rounded-bottom p-4 d-flex flex-column flex-grow-1">
                          <Link to="#" className="h4 d-block mb-3 text-truncate">
                            {c.anggota_1_name || ''} - {c.anggota_2_name || ''}
                          </Link>
                          <div className="categories-review mb-4 flex-grow-1">
                            <div className="card-description" style={{
                              display: '-webkit-box',
                              WebkitLineClamp: 4,
                              WebkitBoxOrient: 'vertical',
                              overflow: 'hidden',
                              textOverflow: 'ellipsis',
                              minHeight: '96px'
                            }}>
                              {c.description ? c.description.replace(/(<([^>]+)>)/gi, '') : ''}
                            </div>
                          </div>
                          {/* We use standard numeric IDs for react router for simplicity in fetching detail, encrypting is backend's job for server sides */}
                          <Link to={`/voting/detail/${c.id}`} className="btn rounded-pill d-flex justify-content-center py-3 mt-auto"
                            style={{ backgroundColor: '#980517', color: '#fff' }}>
                            Lihat Detail
                          </Link>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            ))
          )}

        </div>
      </div>
    </>
  );
}
