import { useState, useEffect } from 'react';
import axios from 'axios';
import { useSearchParams } from 'react-router-dom';

const API = 'http://localhost:3001';
const DEFAULT_IMG = `${API}/dist/landing/assets/img/kholis2.jpg`;

function getImg(path, subfolder = 'struktur') {
  if (!path) return DEFAULT_IMG;
  return `${API}/uploads/${subfolder}/${path}`;
}

function OrgCard({ nama, jabatan, prodi, gambar, size = 'other' }) {
  if (!nama) return null;
  const sizeClass = size === 'president' ? 'photo-president' : size === 'vp' ? 'photo-vp' : 'photo-other';
  const fontSz = size === 'president' ? '0.9rem' : '0.85rem';
  return (
    <div>
      <div className={`photo-container ${sizeClass}`}>
        <img src={gambar || DEFAULT_IMG} alt={nama} className="photo-img" loading="lazy"
          onError={e => { e.target.src = DEFAULT_IMG; }} />
      </div>
      <div className="card-content">
        <h3 style={{ fontSize: fontSz }}>{nama}</h3>
        {prodi && <p className="prodi-text">{prodi}</p>}
        <div className="jabatan-wrapper">
          <button type="button" className="jabatan-button">{jabatan}</button>
        </div>
      </div>
    </div>
  );
}

export default function StrukturPage() {
  const [searchParams] = useSearchParams();
  const orgId = searchParams.get('org') || '';
  const tahun = searchParams.get('tahun') || '';
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchStruktur = async () => {
      try {
        setLoading(true);
        const params = new URLSearchParams();
        if (orgId) params.append('org', orgId);
        if (tahun) params.append('tahun', tahun);
        const res = await axios.get(`${API}/api/public/struktur?${params.toString()}`);
        if (res.data.status === 'success') setData(res.data);
      } catch (err) { console.error(err); }
      finally { setLoading(false); }
    };
    fetchStruktur();
  }, [orgId, tahun]);

  if (loading) return (
    <div className="text-center py-5" style={{ marginTop: '20vh' }}>
      <div className="spinner-border text-danger" role="status">
        <span className="visually-hidden">Loading...</span>
      </div>
    </div>
  );

  const st = data?.struktur || {};
  const organisasi = data?.organisasi || {};
  const anggaran = data?.anggaran || null;
  const divisi = data?.divisi || [];
  const visiMisi = data?.visiMisi || null;
  const proker = data?.proker || null;
  const availableYears = data?.availableYears || [];

  const periodeTitle = st?.periode
    ? `${st.periode}${st.tahun ? ' ' + st.tahun : ''}`
    : organisasi?.name || 'Struktur Organisasi';

  const hasStrukturData = !!(st?.nama_1 || st?.nama_2 || st?.nama_3);

  const fmtRp = (n) => Number(n || 0).toLocaleString('id-ID');

  return (
    <>
      <style>{`
        *,*:before,*:after{box-sizing:border-box;}
        #strukt-wrapper{margin:150px auto;max-width:80em;}
        #strukt-container{float:left;padding:1em;width:100%;}
        ol.organizational-chart,ol.organizational-chart ol,ol.organizational-chart li,ol.organizational-chart li>div{position:relative;}
        ol.organizational-chart,ol.organizational-chart ol{list-style:none;margin:0;padding:0;text-align:center;}
        ol.organizational-chart ol{padding-top:1em;}
        ol.organizational-chart ol:before,ol.organizational-chart ol:after,ol.organizational-chart li:before,ol.organizational-chart li:after,ol.organizational-chart>li>div:before,ol.organizational-chart>li>div:after{background-color:#000;content:'';position:absolute;}
        ol.organizational-chart ol>li{padding:1em 0 0 1em;}
        ol.organizational-chart>li ol:before{height:1em;left:50%;top:0;width:3px;}
        ol.organizational-chart>li ol:after{height:3px;left:3px;top:1em;width:50%;}
        ol.organizational-chart>li ol>li:not(:last-of-type):before{height:3px;left:0;top:2em;width:1em;}
        ol.organizational-chart>li ol>li:not(:last-of-type):after{height:100%;left:0;top:0;width:3px;}
        ol.organizational-chart>li ol>li:last-of-type:before{height:3px;left:0;top:2em;width:1em;}
        ol.organizational-chart>li ol>li:last-of-type:after{height:2em;left:0;top:0;width:3px;}
        ol.organizational-chart li>div{background-color:#A01D1D;border:4px solid #A01D1D;border-radius:18px;min-height:2em;padding:10px 10px 14px 10px;box-shadow:0 4px 10px rgba(0,0,0,.18);width:210px;max-width:210px;margin:0 auto;position:relative;overflow:visible;background:linear-gradient(to bottom,#A01D1D 0%,#A01D1D 75%,#fff 75%,#fff 100%);}
        .photo-container{margin:-10px auto 6px auto;overflow:hidden;border-radius:14px;border:3px solid #A01D1D;position:relative;z-index:5;background:#A01D1D;display:flex;align-items:flex-end;justify-content:center;}
        .photo-president{width:120px;height:120px;}
        .photo-vp{width:110px;height:110px;}
        .photo-other{width:100px;height:100px;}
        .photo-img{width:100%;height:100%;object-fit:cover;object-position:center top;display:block;border-radius:5px;}
        ol.organizational-chart li>div .card-content{padding:12px 4px 10px 4px;background:transparent;min-height:105px;display:flex;flex-direction:column;justify-content:flex-start;}
        ol.organizational-chart li>div .card-content h3{color:#fff;font-weight:600;margin-bottom:10px;margin-top:0;text-align:center;font-size:.9rem;line-height:1.2;}
        .prodi-text{color:#fff;font-size:.75rem;margin:-5px 0 8px 0;text-align:center;width:100%;line-height:1.2;font-weight:400;opacity:.9;}
        .jabatan-wrapper{background-color:#fff;border-radius:999px;padding:4px 8px;display:flex;justify-content:center;align-items:center;width:85%;max-width:180px;margin:6px auto 0 auto;box-shadow:0 4px 8px rgba(0,0,0,.18);cursor:pointer;margin-top:auto;}
        .jabatan-button{background-color:#A01D1D;color:#fff;padding:6px 22px;border-radius:999px;text-align:center;display:inline-block;font-size:.75rem;font-weight:600;border:none;cursor:pointer;transition:all .3s ease;text-transform:uppercase;letter-spacing:.5px;}
        .jabatan-button:hover{background-color:#8a1919;transform:translateY(-1px);}
        .budget-tag{background:#A01D1D;color:#fff;display:inline-block;padding:10px 25px;border-radius:50px;font-weight:600;box-shadow:0 4px 15px rgba(160,29,29,.3);margin:5px;font-size:.9rem;}
        .budget-tag.spent{background:#fff;color:#A01D1D;border:2px solid #A01D1D;}
        .status-coming{background:#e2e8f0;color:#475569;}
        .status-progres{background:#fef3c7;color:#92400e;}
        .status-finish{background:#dcfce7;color:#166534;}
        .status-badge{font-size:.7rem;text-transform:uppercase;letter-spacing:1px;font-weight:700;padding:5px 12px;border-radius:50px;margin-bottom:15px;display:inline-block;width:fit-content;}
        .proker-link{color:#A01D1D;font-size:.85rem;font-weight:600;text-decoration:none;display:flex;align-items:center;transition:opacity .2s;}
        .proker-link:hover{opacity:.8;text-decoration:underline;}
        @media only screen and (min-width:64em){
          ol.organizational-chart{margin-left:-1em;margin-right:-1em;}
          ol.organizational-chart>li>div{display:inline-block;float:none;margin:0 1em 1em 1em;vertical-align:bottom;}
          ol.organizational-chart>li>div:only-of-type{margin-bottom:0;}
          ol.organizational-chart>li>div:before,ol.organizational-chart>li>div:after{bottom:-1em!important;top:inherit!important;}
          ol.organizational-chart>li>div:before{height:1em!important;left:50%!important;width:3px!important;}
          ol.organizational-chart>li>div:only-of-type:after{display:none;}
          ol.organizational-chart>li>div:first-of-type:not(:only-of-type):after,ol.organizational-chart>li>div:last-of-type:not(:only-of-type):after{bottom:-1em;height:3px;width:calc(50% + 1em + 3px);}
          ol.organizational-chart>li>div:first-of-type:not(:only-of-type):after{left:calc(50% + 3px);}
          ol.organizational-chart>li>div:last-of-type:not(:only-of-type):after{left:calc(-1em - 3px);}
          ol.organizational-chart>li>div+div:not(:last-of-type):after{height:3px;left:-2em;width:calc(100% + 4em);}
          ol.organizational-chart>li>ol{display:flex;flex-wrap:nowrap;}
          ol.organizational-chart>li>ol:before,ol.organizational-chart>li>ol>li:before{height:1em!important;left:50%!important;top:0!important;width:3px!important;}
          ol.organizational-chart>li>ol:after{display:none;}
          ol.organizational-chart>li>ol>li{flex-grow:1;padding-left:1em;padding-right:1em;padding-top:1em;}
          ol.organizational-chart>li>ol>li:only-of-type{padding-top:0;}
          ol.organizational-chart>li>ol>li:only-of-type:before,ol.organizational-chart>li>ol>li:only-of-type:after{display:none;}
          ol.organizational-chart>li>ol>li:first-of-type:not(:only-of-type):after,ol.organizational-chart>li>ol>li:last-of-type:not(:only-of-type):after{height:3px;top:0;width:50%;}
          ol.organizational-chart>li>ol>li:first-of-type:not(:only-of-type):after{left:50%;}
          ol.organizational-chart>li>ol>li:last-of-type:not(:only-of-type):after{left:0;}
          ol.organizational-chart>li>ol>li+li:not(:last-of-type):after{height:3px;left:0;top:0;width:100%;}
        }
        @media only screen and (max-width:1024px){
          .photo-president{width:100px;height:100px;}
          .photo-vp{width:95px;height:95px;}
          .photo-other{width:85px;height:85px;}
          ol.organizational-chart,ol.organizational-chart ol{padding-left:0!important;padding-right:0!important;}
          ol.organizational-chart>li>div,ol.organizational-chart li>div{width:210px!important;margin:0 auto;}
          ol.organizational-chart ol,ol.organizational-chart li,ol.organizational-chart>li>ol,.division-chart>li>ol,.division-chart>li>ol>li>ol{display:block!important;width:100%!important;padding-top:0!important;}
          ol.organizational-chart ol{margin-top:30px!important;}
          ol.organizational-chart li{margin:0 auto 30px auto!important;position:relative;padding:0!important;}
          ol.organizational-chart ol::before,ol.organizational-chart ol::after,ol.organizational-chart li::before,ol.organizational-chart li::after,ol.organizational-chart>li>div::before,ol.organizational-chart>li>div::after{display:none!important;width:0!important;height:0!important;content:none!important;}
          ol.organizational-chart li>div::after,ol.organizational-chart>li>div::after,.division-chart>li>div::after{content:''!important;position:absolute;bottom:-30px!important;left:50%;height:31px!important;width:3px;background-color:#000;transform:translateX(-50%);display:block!important;z-index:5;}
          .division-chart>li>ol>li>ol>li>div::after{display:none!important;}
        }
      `}</style>

      <div id="strukt-wrapper">

        {/* Year Filter */}
        {orgId && availableYears.length > 0 && (
          <div className="container" style={{ marginTop: '100px', marginBottom: '20px' }}>
            <div className="row justify-content-center">
              <div className="col-md-4">
                <div className="card" style={{ borderRadius: '8px' }}>
                  <div className="card-body" style={{ padding: '0.75rem' }}>
                    <h6 className="card-title" style={{ fontSize: '0.9rem', marginBottom: '0.75rem', fontWeight: 600 }}>
                      Filter Kepengurusan
                    </h6>
                    <div className="row g-2">
                      <div className="col-8">
                        <select className="form-select form-select-sm" style={{ fontSize: '0.875rem' }}
                          value={tahun}
                          onChange={e => {
                            const url = new URL(window.location.href);
                            url.searchParams.set('tahun', e.target.value);
                            window.location.href = url.href;
                          }}>
                          <option value="">Pilih Tahun</option>
                          {availableYears.map(y => (
                            <option key={y.tahun} value={y.tahun}>{y.tahun}</option>
                          ))}
                        </select>
                      </div>
                      <div className="col-4">
                        <a href={`/struktur?org=${orgId}`} className="btn btn-secondary btn-sm w-100" style={{ fontSize: '0.875rem' }}>Reset</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}

        <div id="strukt-container">
          {/* Header */}
          <div style={{ textAlign: 'center', marginBottom: '60px', marginTop: '50px' }}>
            <h1 style={{ color: '#A01D1D', fontSize: '2.5rem', fontWeight: 600, marginBottom: '10px', lineHeight: 1.2 }}>
              {periodeTitle}
            </h1>
            {anggaran && (
              <div className="d-flex flex-wrap justify-content-center gap-2 mt-3">
                <div className="budget-tag">
                  <i className="bi bi-wallet2 me-2"></i>
                  Total Anggaran: Rp {fmtRp(anggaran.jumlah)}
                </div>
                <div className="budget-tag spent">
                  <i className="bi bi-cash-stack me-2"></i>
                  Dana Terpakai: Rp {fmtRp(anggaran.dana_berkurang)}
                </div>
              </div>
            )}
          </div>

          {/* Main Org Chart */}
          {hasStrukturData ? (
            <ol className="organizational-chart">
              <li>
                {/* Pos 1: President */}
                {st.nama_1 && (
                  <OrgCard nama={st.nama_1} jabatan={st.jabatan_1} prodi={st.prodi_1}
                    gambar={getImg(st.gambar_1)} size="president" />
                )}

                {/* Pos 2: VP */}
                {st.nama_2 && (
                  <ol>
                    <li>
                      <OrgCard nama={st.nama_2} jabatan={st.jabatan_2} prodi={st.prodi_2}
                        gambar={getImg(st.gambar_2)} size="vp" />
                    </li>
                  </ol>
                )}

                {/* Pos 3-6: Secretary, Treasurer, etc. */}
                <ol>
                  {[3, 4, 5, 6].map(n => st[`nama_${n}`] ? (
                    <li key={n}>
                      <OrgCard nama={st[`nama_${n}`]} jabatan={st[`jabatan_${n}`]} prodi={st[`prodi_${n}`]}
                        gambar={getImg(st[`gambar_${n}`])} size="other" />
                    </li>
                  ) : null)}
                </ol>
              </li>
            </ol>
          ) : (
            <div style={{ textAlign: 'center', padding: '50px' }}>
              <h2 style={{ color: '#A01D1D' }}>Struktur Organisasi</h2>
              <p style={{ color: '#666' }}>
                {orgId ? 'Data struktur belum tersedia untuk organisasi ini.' : 'Pilih organisasi dari menu Organisasi di navbar.'}
              </p>
            </div>
          )}
        </div>
      </div>

      {/* Division Section */}
      {Array.isArray(divisi) && divisi.length > 0 ? (
        divisi.map(div => (
          <div key={div.id || div.nama_divisi}>
            <div className="container section-title" data-aos="fade-up" style={{ marginTop: '50px' }}>
              <h2>Struktur Divisi - {div.nama_divisi}</h2>
            </div>
            <div className="container">
              <div className="row justify-content-center">
                <ol className="organizational-chart division-chart">
                  <li>
                    {/* Ketua Divisi */}
                    <OrgCard nama={div.nama_ketua} jabatan={div.jabatan_ketua || 'Ketua Divisi'}
                      prodi={div.prodi_ketua} gambar={getImg(div.gambar_ketua, 'struktur/divisi')} size="president" />

                    {/* Anggota 1-4, with 5-8 as children */}
                    <ol>
                      {[1, 2, 3, 4].map(i => {
                        if (!div[`nama_anggota_${i}`]) return null;
                        const childIdx = i + 4;
                        const hasChild = !!div[`nama_anggota_${childIdx}`];
                        return (
                          <li key={i}>
                            <OrgCard nama={div[`nama_anggota_${i}`]} jabatan={div[`jabatan_anggota_${i}`] || 'Anggota'}
                              prodi={div[`prodi_anggota_${i}`]} gambar={getImg(div[`gambar_anggota_${i}`], 'struktur/divisi')} size="other" />
                            {hasChild && (
                              <ol>
                                <li>
                                  <OrgCard nama={div[`nama_anggota_${childIdx}`]} jabatan={div[`jabatan_anggota_${childIdx}`] || 'Anggota'}
                                    prodi={div[`prodi_anggota_${childIdx}`]} gambar={getImg(div[`gambar_anggota_${childIdx}`], 'struktur/divisi')} size="other" />
                                </li>
                              </ol>
                            )}
                          </li>
                        );
                      })}
                    </ol>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        ))
      ) : (
        <div className="container section-title" data-aos="fade-up" style={{ marginTop: '50px' }}>
          <h2>Struktur Divisi</h2>
          <p className="text-muted mt-3">Data struktur divisi belum tersedia.</p>
        </div>
      )}

      {/* Visi Misi */}
      {visiMisi ? (
        <>
          <div className="container section-title" data-aos="fade-up" style={{ marginTop: '50px' }}>
            <h2>Visi &amp; Misi</h2>
          </div>
          <div className="container mb-5">
            <div className="row justify-content-center">
              <div className="col-md-10">
                <div className="card shadow-sm">
                  <div className="card-body p-5">
                    <div className="text-center mb-4">
                      <h3 style={{ color: '#A01D1D', fontWeight: 700 }}>VISI</h3>
                      <p className="lead" style={{ color: '#444', fontStyle: 'italic' }}>
                        &ldquo;{visiMisi.visi}&rdquo;
                      </p>
                    </div>
                    <hr className="my-4" />
                    <div className="mt-4">
                      <h3 className="text-center mb-4" style={{ color: '#A01D1D', fontWeight: 700 }}>MISI</h3>
                      <div style={{ color: '#444', lineHeight: 1.8, whiteSpace: 'pre-line' }}>
                        {visiMisi.misi}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </>
      ) : (
        <div className="container section-title" data-aos="fade-up" style={{ marginTop: '50px' }}>
          <h2>Visi &amp; Misi</h2>
          <p className="text-muted mt-3">Data Visi &amp; Misi belum tersedia.</p>
        </div>
      )}

      {/* Program Kerja */}
      {proker && proker.deskripsi ? (() => {
        let prokerList = [];
        try { prokerList = typeof proker.deskripsi === 'string' ? JSON.parse(proker.deskripsi) : proker.deskripsi; } catch(e) {}
        const statusClass = (s) => s === 'Progres' ? 'status-progres' : s === 'Finish' ? 'status-finish' : 'status-coming';
        return (
          <>
            <div className="container" style={{ marginTop: '80px', textAlign: 'center' }}>
              <h2 style={{ color: '#A01D1D', fontWeight: 600, fontSize: '2rem' }}>
                {proker.judul || 'Program Kerja'}
              </h2>
              <div style={{ width: '60px', height: '3px', background: '#A01D1D', margin: '10px auto 40px auto' }}></div>
            </div>
            <div className="container mb-5">
              <div className="row justify-content-center">
                <div className="col-md-10">
                  <div className="row">
                    {prokerList.map((item, idx) => (
                      <div key={idx} className="col-md-6 mb-4">
                        <div className="card shadow-sm h-100" style={{ borderLeft: '4px solid #A01D1D', borderRadius: '12px', transition: 'transform .3s ease' }}>
                          <div className="card-body p-4">
                            <div className="d-flex justify-content-between align-items-start mb-2">
                              <h5 className="card-title mb-0" style={{ color: '#A01D1D', fontWeight: 700, fontSize: '1.1rem', lineHeight: 1.4 }}>
                                {item.program}
                              </h5>
                              <span className={`status-badge ${statusClass(item.status)}`} style={{ fontSize: '0.65rem', padding: '4px 10px' }}>
                                {item.status || 'Coming soon'}
                              </span>
                            </div>
                            {item.keterangan && (
                              <p className="card-text text-muted small mt-2 mb-3" style={{ whiteSpace: 'pre-line' }}>{item.keterangan}</p>
                            )}
                            <div className="d-flex flex-column gap-2 mt-auto">
                              <div className="d-flex align-items-center text-dark" style={{ fontSize: '0.85rem', fontWeight: 600 }}>
                                <i className="bi bi-tag-fill me-2 text-danger"></i>
                                Rp {fmtRp(item.dana_berkurang || 0)}
                              </div>
                              {item.link_berita && (
                                <a href={item.link_berita} target="_blank" rel="noreferrer" className="proker-link" style={{ fontSize: '0.8rem' }}>
                                  <i className="bi bi-link-45deg me-1"></i> Baca Berita Selengkapnya
                                </a>
                              )}
                            </div>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </>
        );
      })() : (
        <div className="container" style={{ marginTop: '80px', textAlign: 'center' }}>
          <h2 style={{ color: '#A01D1D', fontWeight: 600, fontSize: '2rem' }}>Program Kerja</h2>
          <div style={{ width: '60px', height: '3px', background: '#A01D1D', margin: '10px auto 20px auto' }}></div>
          <p className="text-muted">Data program kerja belum tersedia.</p>
        </div>
      )}
    </>
  );
}
