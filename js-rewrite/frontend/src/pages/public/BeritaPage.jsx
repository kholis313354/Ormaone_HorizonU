import { useState, useEffect } from 'react';
import axios from 'axios';
import { Link, useSearchParams } from 'react-router-dom';

export default function BeritaPage() {
  const [beritas, setBeritas] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchParams, setSearchParams] = useSearchParams();

  const currentCategory = searchParams.get('kategori') || '';
  const searchQuery = searchParams.get('q') || '';
  const currentPage = parseInt(searchParams.get('page')) || 1;
  const perPage = 4;

  useEffect(() => {
    const fetchBerita = async () => {
      try {
        const res = await axios.get('http://localhost:3001/api/public/berita');
        if (res.data.status === 'success') {
          let data = res.data.data;
          if (currentCategory) data = data.filter(b => b.category_name?.toLowerCase() === currentCategory.toLowerCase());
          if (searchQuery) data = data.filter(b => b.title.toLowerCase().includes(searchQuery.toLowerCase()));
          setBeritas(data);
        }
      } catch (err) { console.error(err); }
      finally { setLoading(false); }
    };
    fetchBerita();
  }, [currentCategory, searchQuery]);

  // Handle equal height for older posts and hover effects like CI4
  useEffect(() => {
    if (beritas.length <= 1) return;
    function setEqualCardHeights() {
      const cards = document.querySelectorAll('#older-posts-container .card');
      let maxHeight = 0;
      cards.forEach(c => c.style.height = 'auto');
      cards.forEach(c => { if (c.offsetHeight > maxHeight) maxHeight = c.offsetHeight; });
      cards.forEach(c => c.style.height = `${maxHeight}px`);
    }
    setEqualCardHeights();
    window.addEventListener('resize', setEqualCardHeights);
    
    const cards = document.querySelectorAll('.card');
    const handleEnter = function() {
      this.style.transform = 'translateY(-5px)';
      this.style.transition = 'transform 0.3s ease';
      this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
    };
    const handleLeave = function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '';
    };

    cards.forEach(card => {
      card.addEventListener('mouseenter', handleEnter);
      card.addEventListener('mouseleave', handleLeave);
    });

    return () => {
      window.removeEventListener('resize', setEqualCardHeights);
      cards.forEach(card => {
        card.removeEventListener('mouseenter', handleEnter);
        card.removeEventListener('mouseleave', handleLeave);
      });
    };
  }, [beritas, currentPage]);

  const handleSearch = (e) => {
    e.preventDefault();
    const q = e.target.q.value;
    setSearchParams(prev => { prev.set('q', q); prev.set('page', '1'); return prev; });
  };

  const handleCategoryChange = (e) => {
    setSearchParams(prev => { prev.set('kategori', e.target.value); prev.set('page', '1'); return prev; });
  };

  const handlePageChange = (page) => {
    setSearchParams(prev => { prev.set('page', page); return prev; });
    window.scrollTo(0, 0);
  };

  // Pagination logic
  const latestPost = beritas.length > 0 ? beritas[0] : null;
  const olderPosts = beritas.length > 1 ? beritas.slice(1) : [];
  
  const totalPosts = olderPosts.length;
  const totalPages = Math.ceil(totalPosts / perPage);
  const offset = (currentPage - 1) * perPage;
  const paginatedPosts = olderPosts.slice(offset, offset + perPage);

  let startPage = 1, endPage = totalPages;
  if (totalPages > 5) {
    if (currentPage <= 3) { startPage = 1; endPage = 5; }
    else if (currentPage >= totalPages - 2) { startPage = totalPages - 4; endPage = totalPages; }
    else { startPage = currentPage - 2; endPage = currentPage + 2; }
  }

  if (loading) return <div className="text-center py-5">Memuat...</div>;

  return (
    <>
      <header className="py-5 bg-light border-bottom mb-4">
        <div className="container">
          <div className="text-center my-5">
            <h1 className="fw-bolder">Welcome to Blog Ormaone</h1>
            <p className="lead mb-0">Program - program Kegiatan dari Semua Organisasi Mahasiswa di Universitas Horizon Indonesia</p>
          </div>
        </div>
      </header>

      <div className="container">
        <div className="row">
          <div className="col-lg-8" id="latest-posts-container">
            {latestPost ? (
              <div className="card mb-4">
                <img className="card-img-top" src={`http://localhost:3001/uploads/${latestPost.thumbnail}`} alt={latestPost.title} style={{ maxHeight: '300px', objectFit: 'cover' }} loading="lazy" decoding="async" onError={(e) => e.target.src = '/dist/landing/assets/img/logo1.png'} />
                <div className="card-body">
                  <div className="small text-muted">
                    {new Date(latestPost.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })} | {latestPost.category_name || latestPost.nama_fakultas || 'Umum'}
                  </div>
                  <h2 className="card-title">{latestPost.title}</h2>
                  <p className="card-text">{latestPost.content?.replace(/<[^>]*>?/gm, '').substring(0, 200)}...</p>
                  <Link className="btn" style={{ backgroundColor: '#A01D1D', borderColor: '#A01D1D', color: '#fff' }} to={`/berita/detail/${latestPost.id}`}>
                    Baca Selengkapnya &rarr;
                  </Link>
                </div>
              </div>
            ) : (
              <div className="alert alert-info">
                <h4>Tidak ada berita yang tersedia</h4>
                <p>Silakan kembali lagi nanti atau hubungi administrator untuk informasi lebih lanjut.</p>
              </div>
            )}
          </div>

          <div className="col-lg-4">
            <div className="card mb-4">
              <div className="card-header">Cari Berita</div>
              <div className="card-body">
                <form onSubmit={handleSearch} id="searchForm">
                  <div className="input-group">
                    <input className="form-control" type="text" name="q" placeholder="Masukkan kata kunci..." maxLength="100" defaultValue={searchQuery} required />
                    <button className="btn" style={{ backgroundColor: '#A01D1D', borderColor: '#A01D1D', color: '#fff' }} type="submit">Cari</button>
                  </div>
                </form>
              </div>
            </div>

            <div className="card mb-4">
              <div className="card-header">Filter Kategori</div>
              <div className="card-body">
                <form id="kategoriFilterForm">
                  <div className="mb-3">
                    <label htmlFor="kategoriFilter" className="form-label">Pilih Kategori</label>
                    <select className="form-select" id="kategoriFilter" value={currentCategory} onChange={handleCategoryChange}>
                      <option value="">Semua Kategori</option>
                      <option value="blogger">Blogger</option>
                      <option value="podcast">Podcast</option>
                    </select>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div className="row" id="older-posts-container">
          {paginatedPosts.map(post => (
            <div className="col-lg-3 mb-4" key={post.id}>
              <div className="card h-100">
                <img className="card-img-top" src={`http://localhost:3001/uploads/${post.thumbnail}`} alt={post.title} style={{ maxHeight: '200px', objectFit: 'cover' }} loading="lazy" decoding="async" onError={(e) => e.target.src = '/dist/landing/assets/img/logo1.png'} />
                <div className="card-body">
                  <div className="small text-muted">
                    {new Date(post.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })} | {post.category_name || post.nama_fakultas || 'Umum'}
                  </div>
                  <h2 className="card-title h5">{post.title}</h2>
                  <p className="card-text">{post.content?.replace(/<[^>]*>?/gm, '').substring(0, 100)}...</p>
                  <Link className="btn btn-sm" style={{ backgroundColor: '#A01D1D', borderColor: '#A01D1D', color: '#fff' }} to={`/berita/detail/${post.id}`}>
                    Baca Selengkapnya &rarr;
                  </Link>
                </div>
              </div>
            </div>
          ))}
        </div>

        {totalPages > 1 && (
          <>
            <nav aria-label="Page navigation" className="mt-4">
              <ul className="pagination pagination-custom justify-content-center">
                <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                  <button className="page-link" onClick={() => handlePageChange(Math.max(1, currentPage - 1))}>
                    <span className="pagination-text">Previous</span>
                    <span className="pagination-icon">‹</span>
                  </button>
                </li>
                {startPage > 1 && (
                  <>
                    <li className="page-item"><button className="page-link" onClick={() => handlePageChange(1)}>1</button></li>
                    {startPage > 2 && <li className="page-item disabled"><span className="page-link">...</span></li>}
                  </>
                )}
                {Array.from({ length: endPage - startPage + 1 }, (_, i) => startPage + i).map(num => (
                  <li key={num} className={`page-item ${num === currentPage ? 'active' : ''}`}>
                    <button className="page-link" onClick={() => handlePageChange(num)}>{num}</button>
                  </li>
                ))}
                {endPage < totalPages && (
                  <>
                    {endPage < totalPages - 1 && <li className="page-item disabled"><span className="page-link">...</span></li>}
                    <li className="page-item"><button className="page-link" onClick={() => handlePageChange(totalPages)}>{totalPages}</button></li>
                  </>
                )}
                <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
                  <button className="page-link" onClick={() => handlePageChange(Math.min(totalPages, currentPage + 1))}>
                    <span className="pagination-text">Next</span>
                    <span className="pagination-icon">›</span>
                  </button>
                </li>
              </ul>
            </nav>
            <style>
              {`
                .pagination-custom { flex-wrap: wrap; gap: 0.25rem; }
                .pagination-custom .page-item { margin: 0; }
                .pagination-custom .page-link { min-width: 40px; height: 40px; padding: 0.5rem 0.75rem; display: flex; align-items: center; justify-content: center; color: #000; background-color: #fff; border: 1px solid #dee2e6; border-radius: 0.25rem; text-decoration: none; transition: all 0.3s ease; font-weight: 500; }
                .pagination-custom .page-link:hover:not(.disabled):not(.active) { background-color: #f8f9fa; border-color: #980517; color: #980517; transform: translateY(-2px); box-shadow: 0 2px 4px rgba(152, 5, 23, 0.2); }
                .pagination-custom .page-item.active .page-link { background-color: #A01D1D; border-color: #A01D1D; color: #fff; font-weight: 600; box-shadow: 0 2px 6px rgba(160, 29, 29, 0.3); }
                .pagination-custom .page-item.disabled .page-link { color: #6c757d; background-color: #e9ecef; border-color: #dee2e6; cursor: not-allowed; opacity: 0.6; }
                .pagination-custom .page-item.disabled .page-link:hover { transform: none; box-shadow: none; }
                .pagination-custom .page-link .pagination-text { display: inline; }
                .pagination-custom .page-link .pagination-icon { display: none; }
                @media (max-width: 768px) { .pagination-custom .page-link { min-width: 36px; height: 36px; padding: 0.4rem 0.6rem; font-size: 0.9rem; } .pagination-custom .page-link .pagination-text { font-size: 0.85rem; } }
                @media (max-width: 576px) { .pagination-custom { gap: 0.15rem; } .pagination-custom .page-link { min-width: 32px; height: 32px; padding: 0.3rem 0.5rem; font-size: 0.8rem; } .pagination-custom .page-link .pagination-text { display: none; } .pagination-custom .page-link .pagination-icon { display: inline; font-size: 1.2rem; font-weight: bold; } }
                @media (max-width: 375px) { .pagination-custom .page-link { min-width: 28px; height: 28px; padding: 0.25rem 0.4rem; font-size: 0.75rem; } .pagination-custom .page-link .pagination-icon { font-size: 1rem; } }
                .pagination-custom .page-link:focus { outline: 2px solid #980517; outline-offset: 2px; z-index: 1; }
              `}
            </style>
          </>
        )}
      </div>
    </>
  );
}
