import { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';

export default function HomePage() {
  const [organisasis, setOrganisasis] = useState([]);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const formStartTime = useRef(Math.floor(Date.now() / 1000));
  const carouselRef = useRef(null);

  // Form State
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    message: '',
    website: '' // Honeypot
  });

  useEffect(() => {
    // Fetch organisasis to mimic CI4 carousel logic (mocking for exact structure if API not ready)
    // We assume backend has /api/public/organisasi
    const fetchOrgs = async () => {
      try {
        const res = await axios.get('http://localhost:3001/api/public/organisasi');
        if (res.data?.data) {
          setOrganisasis(res.data.data.filter(org => org.image));
        } else {
          // Fallback mock to simulate the CI4 UI if no backend items fetched
          setOrganisasis([
            { id: 1, name: 'BEM', image: 'FotbarBem.jpeg' },
            { id: 2, name: 'BEM', image: 'FotbarBem1.jpeg' }
          ]);
        }
      } catch (err) {
        setOrganisasis([
          { id: 1, name: 'BEM', image: 'FotbarBem.jpeg' }
        ]);
      }
    };
    fetchOrgs();
  }, []);

  // Clients Carousel Script Logic translated from CI4 scripts.php
  useEffect(() => {
    const carousel = carouselRef.current;
    if (!carousel || organisasis.length === 0) return;

    const items = carousel.querySelectorAll('.client-logo-item');
    if (items.length === 0) return;

    // Based on CI4 calculation: totalItems = duplicated array inside HTML
    // We already loop through organisasis 2 times in render.
    const originalItemsCount = items.length / 2;
    let currentIndex = 0;
    let itemWidth = 0;
    let intervalId = null;
    const slideInterval = 2000;

    function calculateItemWidth() {
      if (items.length > 0) itemWidth = items[0].offsetWidth;
    }

    function moveCarousel() {
      calculateItemWidth();

      // Similar to CI4 reset
      if (currentIndex >= originalItemsCount) {
        carousel.style.transition = 'none';
        currentIndex = 0;
        carousel.style.transform = `translateX(0)`;
        void carousel.offsetWidth; // Force reflow
        carousel.style.transition = 'transform 0.5s ease-in-out';
      }

      const translateX = -(currentIndex * itemWidth);
      carousel.style.transform = `translateX(${translateX}px)`;
      currentIndex++;
    }

    function startCarousel() {
      calculateItemWidth();
      if (intervalId) clearInterval(intervalId);
      intervalId = setInterval(moveCarousel, slideInterval);
    }

    const timer = setTimeout(() => startCarousel(), 1000);

    const handleMouseEnter = () => clearInterval(intervalId);
    const handleMouseLeave = () => startCarousel();

    const wrapper = carousel.closest('.clients-carousel-wrapper');
    if (wrapper) {
      wrapper.addEventListener('mouseenter', handleMouseEnter);
      wrapper.addEventListener('mouseleave', handleMouseLeave);
    }

    let resizeTimer;
    const handleResize = () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        calculateItemWidth();
        carousel.style.transition = 'none';
        currentIndex = 0;
        carousel.style.transform = 'translateX(0)';
        void carousel.offsetWidth;
        carousel.style.transition = 'transform 0.5s ease-in-out';
        startCarousel();
      }, 250);
    };
    window.addEventListener('resize', handleResize);

    return () => {
      clearTimeout(timer);
      clearInterval(intervalId);
      if (wrapper) {
        wrapper.removeEventListener('mouseenter', handleMouseEnter);
        wrapper.removeEventListener('mouseleave', handleMouseLeave);
      }
      window.removeEventListener('resize', handleResize);
    };
  }, [organisasis]);

  const handleInputChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleContactSubmit = async (e) => {
    e.preventDefault();
    if (isSubmitting) return;

    // 1. Honeypot check
    if (formData.website !== '') {
      Swal.fire({ icon: 'error', title: 'Akses Ditolak', text: 'Form tidak valid.', confirmButtonColor: '#d33' });
      return;
    }

    // 2. Time check
    const currentTime = Math.floor(Date.now() / 1000);
    if (currentTime - formStartTime.current < 5) {
      Swal.fire({ icon: 'warning', title: 'Terlalu Cepat', text: 'Mohon isi form dengan lebih teliti. Form dikirim terlalu cepat.', confirmButtonColor: '#ffc107' });
      return;
    }

    // 3. Email Check
    if (!formData.email.endsWith('@krw.horizon.ac.id')) {
      Swal.fire({ icon: 'warning', title: 'Gunakan Email Kampus', text: 'Email harus menggunakan Email Kampus', confirmButtonColor: '#ffc107' });
      return;
    }

    // 4. Content Check
    const spamPatterns = ['viagra', 'casino', 'lottery', 'winner', 'click here', 'buy now'];
    let spamCount = 0;
    const msgLower = formData.message.toLowerCase();
    const subLower = formData.subject.toLowerCase();

    spamPatterns.forEach(pattern => {
      if (msgLower.includes(pattern) || subLower.includes(pattern)) spamCount++;
    });

    if (spamCount >= 3) {
      Swal.fire({ icon: 'error', title: 'Pesan Terdeteksi sebagai Spam', text: 'Pesan Anda mengandung konten yang mencurigakan. Silakan gunakan bahasa yang lebih formal.', confirmButtonColor: '#d33' });
      return;
    }

    setIsSubmitting(true);
    try {
      // Assuming a backend route /api/public/contact
      await axios.post('http://localhost:3001/api/public/contact', formData);
      Swal.fire({
        icon: 'success',
        title: 'Terima kasih🙏',
        html: '<p style="margin-bottom: 10px;">Pesan Anda telah berhasil kami terima.</p><p style="margin: 0;">Tim kami akan segera meninjau dan membalas melalui email yang Anda berikan.</p>',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
      });
      setFormData({ name: '', email: '', subject: '', message: '', website: '' });
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Oops...', html: err.response?.data?.message || 'Gagal mengirim pesan', confirmButtonColor: '#d33', confirmButtonText: 'OK' });
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <>
      <style>
        {`
          /* Clients Carousel Styles */
          .clients-carousel-wrapper { overflow: hidden; width: 100%; position: relative; }
          .clients-carousel { display: flex; transition: transform 0.5s ease-in-out; will-change: transform; }
          .client-logo-item { flex: 0 0 auto; width: calc(100% / 6); padding: 10px; display: flex; align-items: center; justify-content: center; height: 120px; }
          .client-logo-item img { width: 100px; height: 100px; object-fit: contain; object-position: center; filter: grayscale(100%); opacity: 0.6; transition: all 0.3s ease; }
          .client-logo-item img:hover { filter: grayscale(0%); opacity: 1; }
          @media (max-width: 1200px) { .client-logo-item { width: calc(100% / 4); } }
          @media (max-width: 768px) { .client-logo-item { width: calc(100% / 3); } }
          @media (max-width: 576px) { .client-logo-item { width: calc(100% / 2); } }
          /* Security: Honeypot field styling */
          #website { position: absolute !important; left: -9999px !important; opacity: 0 !important; pointer-events: none !important; visibility: hidden !important; }
          /* Form security styling */
          .btn-submit { position: relative; min-width: 150px; }
          .spinner-border-sm { width: 1rem; height: 1rem; border-width: 0.2em; }
          #char_count { font-weight: 500; }
        `}
      </style>

      {/* Hero Section */}
      <section id="hero" className="hero section">
        <div className="hero-bg">
          <img src="/dist/landing/assets/img/bg_1.png" alt="Background" loading="eager" decoding="async" />
        </div>
        <div className="container text-center">
          <div className="d-flex flex-column justify-content-center align-items-center">
            <h1 data-aos="fade-up">Welcome to <span>ORMAONE</span></h1>
            <p data-aos="fade-up" data-aos-delay="100">
              <b>Satu Web untuk semua kebutuhan organisasi mahasiswa! Kelola acara, koordinasi anggota, pengumuman, dan administrasi dengan mudah dalam satu platform terpadu.</b><br />
            </p>
            <div className="d-flex" data-aos="fade-up" data-aos-delay="200"></div>
            <img src="/dist/landing/assets/img/clients/pak.png" className="img-fluid hero-img" alt="Hero Image" data-aos="zoom-out" data-aos-delay="300" loading="lazy" decoding="async" />
          </div>
        </div>
      </section>

      {/* About Section */}
      <section id="about" className="about section">
        <div className="container">
          <div className="row gy-4">
            <div className="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
              <p className="who-we-are">What's new on</p>
              <h3>OrmaOne</h3>
              <p className="fst-italic">
                OrmaOne (Satu aplikasi untuk semua kebutuhan Ormawa) :<br />
                diperlukan suatu sistem informasi terintegrasi yang mampu mengatasi kendala administratif dan meningkatkan transparansi dalam pengelolaan Ormawa. Salah satu solusi yang dapat diterapkan adalah dalam permasalahan tersebut penulis membuat penelitian dengan judul sistem informasi berbasis web bernama "OrmaOne", yang mencakup fitur e-voting, pendataan anggota ormawa digital, serta pembuatan e-sertifikat otomatis guna mendukung operasional Ormawa secara lebih efektif.
              </p>
              <ul>
                <li><i className="bi bi-check-circle"></i> <span>Meningkatkan efisiensi dan transparansi dalam proses pemilihan ketua dan pengurus Ormawa melalui implementasi sistem e-voting yang aman dan mudah digunakan.</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Memudahkan pengelolaan dan akses data anggota secara terpusat dan terstruktur melalui pendataan anggota digital, sehingga mempermudah komunikasi dan memantau partisipasi anggota dalam kegiatan.</span></li>
                <li><i className="bi bi-check-circle"></i> <span>Menyederhanakan dan mempercepat proses penerbitan e-sertifikat sebagai bentuk apresiasi kepada anggota yang berpartisipasi dalam kegiatan Ormawa, sehingga mengurangi beban administrasi dan meminimalkan kesalahan.</span></li>
              </ul>
            </div>
            <div className="col-lg-6 about-images" data-aos="fade-up" data-aos-delay="200">
              <div className="row gy-4">
                <div className="col-lg-6">
                  <div className="row gy-4">
                    <div className="col-lg-12">
                      <img src="/dist/landing/assets/img/fotbarBem.jpeg" className="img-fluid" alt="Foto Baris BEM" loading="lazy" decoding="async" />
                    </div>
                    <div className="col-lg-12">
                      <img src="/dist/landing/assets/img/fotbarBem1.jpeg" className="img-fluid" alt="Foto Baris BEM 1" loading="lazy" decoding="async" />
                    </div>
                  </div>
                </div>
                <div className="col-lg-6">
                  <div className="row gy-4">
                    <div className="col-lg-12">
                      <img src="/dist/landing/assets/img/fotbarBem2.jpeg" className="img-fluid" alt="Foto Baris BEM 2" loading="lazy" decoding="async" />
                    </div>
                    <div className="col-lg-12">
                      <img src="/dist/landing/assets/img/fotbarBem.jpeg" className="img-fluid" alt="Foto Baris BEM" loading="lazy" decoding="async" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Clients Section */}
      <section id="clients" className="clients section">
        <div className="container" data-aos="fade-up">
          {organisasis.length > 0 ? (
            <div className="clients-carousel-wrapper">
              <div className="clients-carousel" id="clientsCarousel" ref={carouselRef}>
                {organisasis.map((org, idx) => (
                  <div className="client-logo-item" key={`org-1-${idx}`}>
                    <img src={`http://localhost:3001/uploads/${org.image}`} className="img-fluid" alt={org.name} title={org.name} loading="lazy" decoding="async" onError={(e) => { e.currentTarget.src = '/dist/landing/assets/img/clients/client-1.png' }} />
                  </div>
                ))}
                {organisasis.map((org, idx) => (
                  <div className="client-logo-item" key={`org-2-${idx}`}>
                    <img src={`http://localhost:3001/uploads/${org.image}`} className="img-fluid" alt={org.name} title={org.name} loading="lazy" decoding="async" onError={(e) => { e.currentTarget.src = '/dist/landing/assets/img/clients/client-1.png' }} />
                  </div>
                ))}
              </div>
            </div>
          ) : (
            <div className="col-12 text-center">
              <p className="text-muted">Belum ada organisasi yang terdaftar</p>
            </div>
          )}
        </div>
      </section>

      {/* Contact Section */}
      <section id="contact" className="contact section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Contact</h2>
          <p>Hubungi kami untuk pertanyaan, Aspirasi Mahasiswa, atau dukungan. Kami siap membantu Anda. Silakan mengisi formulir kontak di bawah ini, dan kami akan segera merespons Via Email. Masukan Anda sangat penting bagi kami!</p>
        </div>
        <div className="container" data-aos="fade-up" data-aos-delay="100">
          <div className="row gy-4">
            <div className="col-lg-6">
              <div className="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                <i className="bi bi-geo-alt"></i>
                <h3>Address</h3>
                <p>Jl. Husni Hamid No. 1, Nagasari, 41312</p>
              </div>
            </div>
            <div className="col-lg-3 col-md-6">
              <div className="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                <i className="bi bi-telephone"></i>
                <h3>Call Us</h3>
                <p>+62-812-9755-4172</p>
              </div>
            </div>
            <div className="col-lg-3 col-md-6">
              <div className="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                <i className="bi bi-envelope"></i>
                <h3>Email Us</h3>
                <p>BemHorizon@gmail.com</p>
              </div>
            </div>
          </div>

          <div className="row gy-4 mt-1">
            <div className="col-lg-6" data-aos="fade-up" data-aos-delay="300">
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.8037018131095!2d107.28976947499078!3d-6.289512593699482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e697760017df9ad%3A0x74508c4a886051a4!2sHorizon%20University%20Indonesia!5e0!3m2!1sid!2sid!4v1746497191779!5m2!1sid!2sid" 
                width="100%" height="450" style={{ border: 0 }} allowFullScreen="" loading="lazy" referrerPolicy="no-referrer-when-downgrade">
              </iframe>
            </div>

            <div className="col-lg-6">
              <form onSubmit={handleContactSubmit} className="php-email-form" id="contactForm" data-aos="fade-up" data-aos-delay="400">
                <div style={{ position: 'absolute', left: '-9999px', opacity: 0, pointerEvents: 'none' }} aria-hidden="true">
                  <label htmlFor="website">Website (jangan isi jika Anda manusia)</label>
                  <input type="text" id="website" name="website" tabIndex="-1" autoComplete="off" value={formData.website} onChange={handleInputChange} />
                </div>

                <div className="row gy-4">
                  <div className="col-md-6">
                    <input type="text" name="name" id="contact_name" className="form-control" placeholder="Name" required minLength="2" maxLength="100" pattern="[A-Za-z\s]+" value={formData.name} onChange={handleInputChange} onInvalid={e => e.target.setCustomValidity('Nama hanya boleh mengandung huruf dan spasi, minimal 2 karakter')} onInput={e => e.target.setCustomValidity('')} />
                  </div>
                  <div className="col-md-6">
                    <input type="email" className="form-control" name="email" id="contact_email" placeholder="Email Kampus" required maxLength="255" value={formData.email} onChange={handleInputChange} />
                  </div>
                  <div className="col-md-12">
                    <input type="text" className="form-control" name="subject" id="contact_subject" placeholder="Subject" required minLength="3" maxLength="200" value={formData.subject} onChange={handleInputChange} />
                  </div>
                  <div className="col-md-12">
                    <textarea className="form-control" name="message" id="contact_message" rows="6" placeholder="Message" required minLength="10" maxLength="2000" value={formData.message} onChange={handleInputChange}></textarea>
                    <small className="text-muted">
                      <span id="char_count">{formData.message.length}</span> / 2000 karakter
                    </small>
                  </div>
                  <div className="col-md-12 text-center">
                    <button type="submit" id="submitBtn" className="btn-submit" disabled={isSubmitting}>
                      {!isSubmitting ? (
                        <span id="submitText">Send Message</span>
                      ) : (
                        <span id="submitLoader">
                          <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...
                        </span>
                      )}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
