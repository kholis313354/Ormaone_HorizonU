import { useState, useEffect } from 'react';
import axios from 'axios';
import { useSearchParams } from 'react-router-dom';

export default function SertifikatPage() {
  const [searchParams, setSearchParams] = useSearchParams();
  const searchParam = searchParams.get('search') || '';
  const typeParam = searchParams.get('nama_sertifikat') || '';
  const pageParam = parseInt(searchParams.get('page') || '1', 10);

  const [sertifikats, setSertifikats] = useState([]);
  const [loading, setLoading] = useState(true);
  
  // Pagination State
  const [currentPage, setCurrentPage] = useState(pageParam);
  const [totalPages, setTotalPages] = useState(1);

  // Search local state to allow typing without pushing to URL on every keystroke
  const [searchInput, setSearchInput] = useState(searchParam);

  useEffect(() => {
    fetchSertifikat();
    // eslint-disable-next-line
  }, [searchParam, typeParam, pageParam]);

  const fetchSertifikat = async () => {
    try {
      setLoading(true);
      // Example backend route; adjust if necessary
      const res = await axios.get(`http://localhost:3001/api/public/sertifikat?search=${searchParam}&type=${typeParam}&page=${pageParam}`);
      if (res.data.status === 'success') {
        setSertifikats(res.data.data || []);
        if (res.data.pagination) {
          setCurrentPage(res.data.pagination.currentPage || 1);
          setTotalPages(res.data.pagination.totalPages || 1);
        } else {
          // Fallback if backend doesn't support pagination yet
          setCurrentPage(1);
          setTotalPages(1);
        }
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    setSearchParams({ search: searchInput, nama_sertifikat: typeParam, page: 1 });
  };

  const handleFilterChange = (e) => {
    setSearchParams({ search: searchParam, nama_sertifikat: e.target.value, page: 1 });
  };

  const handlePageChange = (e, newPage) => {
    e.preventDefault();
    if (newPage >= 1 && newPage <= totalPages && newPage !== currentPage) {
      setSearchParams({ search: searchParam, nama_sertifikat: typeParam, page: newPage });
    }
  };

  const renderPagination = () => {
    if (totalPages <= 1) return null;

    let startPage = 1;
    let endPage = totalPages;
    const maxVisible = 5;

    if (totalPages > maxVisible) {
      if (currentPage <= 3) {
        startPage = 1;
        endPage = 5;
      } else if (currentPage >= totalPages - 2) {
        startPage = totalPages - 4;
        endPage = totalPages;
      } else {
        startPage = currentPage - 2;
        endPage = currentPage + 2;
      }
    }

    const pages = [];
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i);
    }

    return (
      <>
        <style>
          {`
             /* Pagination Custom Styles */
            .pagination-custom { flex-wrap: wrap; gap: 0.25rem; }
            .pagination-custom .page-item { margin: 0; }
            .pagination-custom .page-link { min-width: 40px; height: 40px; padding: 0.5rem 0.75rem; display: flex; align-items: center; justify-content: center; color: #000; background-color: #fff; border: 1px solid #dee2e6; border-radius: 0.25rem; text-decoration: none; transition: all 0.3s ease; font-weight: 500; }
            .pagination-custom .page-link:hover:not(.disabled):not(.active) { background-color: #f8f9fa; border-color: #980517; color: #980517; transform: translateY(-2px); box-shadow: 0 2px 4px rgba(152, 5, 23, 0.2); }
            .pagination-custom .page-item.active .page-link { background-color: #A01D1D; border-color: #A01D1D; color: #fff; font-weight: 600; box-shadow: 0 2px 6px rgba(160, 29, 29, 0.3); }
            .pagination-custom .page-item.disabled .page-link { color: #6c757d; background-color: #e9ecef; border-color: #dee2e6; cursor: not-allowed; opacity: 0.6; }
            .pagination-custom .page-item.disabled .page-link:hover { transform: none; box-shadow: none; }
            .pagination-custom .page-link .pagination-text { display: inline; }
            .pagination-custom .page-link .pagination-icon { display: none; }

            /* Tablet Styles */
            @media (max-width: 768px) {
                .pagination-custom .page-link { min-width: 36px; height: 36px; padding: 0.4rem 0.6rem; font-size: 0.9rem; }
                .pagination-custom .page-link .pagination-text { font-size: 0.85rem; }
            }

            /* Mobile Styles */
            @media (max-width: 576px) {
                .pagination-custom { gap: 0.15rem; }
                .pagination-custom .page-link { min-width: 32px; height: 32px; padding: 0.3rem 0.5rem; font-size: 0.8rem; }
                .pagination-custom .page-link .pagination-text { display: none; }
                .pagination-custom .page-link .pagination-icon { display: inline; font-size: 1.2rem; font-weight: bold; }
                .pagination-custom .page-item.disabled .page-link { padding: 0.3rem 0.4rem; }
            }

            /* Extra Small Mobile */
            @media (max-width: 375px) {
                .pagination-custom .page-link { min-width: 28px; height: 28px; padding: 0.25rem 0.4rem; font-size: 0.75rem; }
                .pagination-custom .page-link .pagination-icon { font-size: 1rem; }
            }

            .pagination-custom .page-link:focus { outline: 2px solid #980517; outline-offset: 2px; z-index: 1; }
          `}
        </style>
        <div className="container mt-4">
          <nav aria-label="Page navigation">
            <ul className="pagination pagination-custom justify-content-center">
              
              <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                <a className="page-link" href="#" tabIndex="-1" onClick={(e) => handlePageChange(e, currentPage - 1)}>
                  <span className="pagination-text">Previous</span>
                  <span className="pagination-icon">‹</span>
                </a>
              </li>

              {startPage > 1 && (
                <>
                  <li className="page-item">
                    <a className="page-link" href="#" onClick={(e) => handlePageChange(e, 1)}>1</a>
                  </li>
                  {startPage > 2 && (
                    <li className="page-item disabled"><span className="page-link">...</span></li>
                  )}
                </>
              )}

              {pages.map(i => (
                <li key={i} className={`page-item ${i === currentPage ? 'active' : ''}`}>
                  <a className="page-link" href="#" onClick={(e) => handlePageChange(e, i)}>{i}</a>
                </li>
              ))}

              {endPage < totalPages && (
                <>
                  {endPage < totalPages - 1 && (
                    <li className="page-item disabled"><span className="page-link">...</span></li>
                  )}
                  <li className="page-item">
                    <a className="page-link" href="#" onClick={(e) => handlePageChange(e, totalPages)}>{totalPages}</a>
                  </li>
                </>
              )}

              <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
                <a className="page-link" href="#" onClick={(e) => handlePageChange(e, currentPage + 1)}>
                  <span className="pagination-text">Next</span>
                  <span className="pagination-icon">›</span>
                </a>
              </li>

            </ul>
          </nav>
        </div>
      </>
    );
  };

  return (
    <>
      <section id="hero" className="hero section">
        <div className="hero-bg">
          <img src="/dist/landing/assets/img/bg_1.png" alt="" loading="eager" decoding="async" onError={(e) => e.target.style.display='none'} />
        </div>
        <div className="container text-center">
          <div className="d-flex flex-column justify-content-center align-items-center">
            <div className="row d-flex justify-content-center">
              <div className="col-lg-6 text-center">
                <img src="/dist/landing/assets/img/logo_horizon.png" width="130" height="130" className="img-fluid d-block mx-auto mb-2" alt="Sertifikat" loading="eager" decoding="async" onError={(e) => e.target.style.display='none'} />
                <h2 style={{ color: '#980517' }}>Horizon University Indonesia</h2>
                <h2 style={{ color: '#980517' }}>Verifikasi Sertifikat Digital</h2>
                <p>Sistem verifikasi sertifikat digital yang aman dan terpercaya. Cari dan verifikasi sertifikat Anda dengan mudah.</p>
                
                <div className="container mt-2">
                  <form onSubmit={handleSearchSubmit} className="d-flex justify-content-center mb-4">
                    <input type="text" name="search" className="form-control me-2 search-bar"
                      placeholder="Cari nama lengkap..."
                      value={searchInput}
                      onChange={(e) => setSearchInput(e.target.value)}
                      pattern="[A-Za-z\\s]{1,50}"
                      title="Hanya huruf dan spasi diperbolehkan (maksimal 50 karakter)"
                      maxLength="50"
                      required />
                    <button type="submit" className="btn" style={{ backgroundColor: '#980517', color: '#fff' }}>Cari</button>
                  </form>
                </div>

              </div>
            </div>
          </div>
        </div>
      </section>

      <br /><br />

      {/* Filter Dropdown */}
      <div className="container mb-4">
        <div className="row justify-content-center">
          <div className="col-md-6">
            <div className="input-group">
              <select name="nama_sertifikat" className="form-select" value={typeParam} onChange={handleFilterChange}>
                <option value="">Semua Jenis Sertifikat</option>
                {/* Fallback to basic static options without backend reference for the exact IDs, 
                    or if backend gives available cert names, they can be mapped here. 
                    Using hardcoded options like previous version, or adjust if API changes */}
                <option value="1">Sertifikat Panitia</option>
                <option value="2">Sertifikat Peserta</option>
                <option value="3">Sertifikat Penghargaan</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      {/* Gallery Section */}
      <section id="gallery" className="gallery">
        <div className="container-fluid">
          <div className="row gy-4 justify-content-center">
            {loading ? (
              <div className="col-12 text-center py-5">
                <div className="spinner-border text-danger" role="status">
                  <span className="visually-hidden">Loading...</span>
                </div>
              </div>
            ) : sertifikats && sertifikats.length > 0 ? (
              sertifikats.map((s, idx) => (
                <div className="col-xl-3 col-lg-4 col-md-6" key={s.id || idx}>
                  <div className="gallery-item h-100">
                    <div className="card">
                      <div className="card-body">
                        <h5 className="card-title">Nama : {s.nama_penerima}</h5>
                        <p className="card-text">
                          <small className="text-muted">
                            Kegiatan : {s.nama_kegiatan}<br />
                            Sertifikat : {s.nama_sertifikat}<br />
                            {s.nama_fakultas}
                          </small>
                        </p>
                      </div>
                      <img src={`http://localhost:3001/uploads/sertifikat/${s.file}`}
                        className="card-img-bottom"
                        alt="Sertifikat"
                        style={{ height: '200px', objectFit: 'contain' }}
                        loading="lazy"
                        decoding="async"
                        onError={(e) => e.target.style.display='none'} />
                      <div className="gallery-links d-flex align-items-center justify-content-center">
                        <a href={`http://localhost:3001/uploads/sertifikat/${s.file}`}
                           title={s.nama_kegiatan}
                           className="glightbox preview-link"
                           data-gallery="images-gallery"
                           data-caption={s.nama_penerima}>
                          <i className="bi bi-arrows-angle-expand"></i>
                        </a>
                      </div>
                      {/* Tombol Unduh */}
                      <div className="card-footer p-2">
                        <a href={`http://localhost:3001/uploads/sertifikat/${s.file}`} 
                           download={`${s.nama_penerima}_${s.nama_kegiatan}.${s.file.split('.').pop()}`}
                           className="btn btn-sm w-100" 
                           style={{ backgroundColor: '#A01D1D', borderColor: '#A01D1D', color: '#fff' }}
                           title="Unduh Sertifikat">
                            <i className="bi bi-download"></i> Unduh
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              ))
            ) : (
              <div className="col-12 text-center py-5">
                <div className="alert alert-warning">
                  Tidak ada sertifikat ditemukan.
                </div>
              </div>
            )}
          </div>
        </div>
      </section>

      {renderPagination()}
      
    </>
  );
}
