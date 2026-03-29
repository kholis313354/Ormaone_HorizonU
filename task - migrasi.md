# Migration Task Tracker: CI4 to JS Stack

Aplikasi ini sangat masif (19 fitur besar) sehingga migrasi *full-code* tidak bisa di-generate dalam 1 detik. Saya akan membuatkan fondasinya secara otomatis sekarang, lalu kita kerjakan blok per blok sesuai panduan ini.

### Phase 1: Foundation (Completed)
- [x] Create monorepo folder structure (`js-rewrite/`)
- [x] Initialize Backend (Node.js, Express, Prisma)
- [x] Initialize Frontend (React 18, Vite, Tailwind CSS)
- [x] Initialize Blockchain (Hardhat, Solidity dummy contract)
- [x] Write Full [schema.prisma](file:///c:/laragon/www/Ormaone_2026/public_html/js-rewrite/backend/prisma/schema.prisma) (based on [u234715368_ormaone26.sql](file:///c:/laragon/www/Ormaone_2026/public_html/u234715368_ormaone26.sql))
  - [x] Analyze MySQL SQL Export
  - [x] Map SQL tables to Prisma Models
  - [x] Execute Prisma Migrations
  - [x] Import all historical data from SQL

### Phase 2: Backend Core (Done)
- [x] Setup Server [index.js](file:///c:/laragon/www/Ormaone_2026/public_html/js-rewrite/backend/src/index.js), Middleware Error Handling
- [x] Setup Auth (JWT, register/login, bcrypt)
- [x] Setup API Routes for Users & Master Data (Fakultas, Organisasi)
- [x] Migrate & Seed Database (PostgreSQL)

### Phase 3: Frontend Core (Done)
- [x] Setup React Router ([src/App.jsx](file:///c:/laragon/www/Ormaone_2026/public_html/js-rewrite/frontend/src/App.jsx))
- [x] Create Main Layouts ([PublicLayout](file:///c:/laragon/www/Ormaone_2026/public_html/js-rewrite/frontend/src/layouts/PublicLayout.jsx#5-16), [AdminLayout](file:///c:/laragon/www/Ormaone_2026/public_html/js-rewrite/frontend/src/layouts/AdminLayout.jsx#4-126))
- [x] Convert CI4 Navbar & Footer to React Components
- [x] Integrate Tailwind Utility Classes

### Phase 4: Public Features (Done)
- [x] Convert Landing Page ([Home](file:///c:/laragon/www/Ormaone_2026/public_html/js-rewrite/frontend/src/pages/public/HomePage.jsx#6-380)) - Pixel Perfect
- [x] Build Berita UI & API - Pixel Perfect
- [x] Build Struktur Organisasi UI & API - Pixel Perfect CSS Grid map
- [x] E-Sertifikat Search Public UI - Pixel Perfect
- [x] E-Voting Dashboard & OTP Modals UI - Pixel Perfect Bootstrap Layout

### Phase 5: Admin Dashboard - Data Master & Settings
- [x] Implement Protected Routes (Dashboard & Role-based Access Control)
- [x] Master Data Fakultas (CRUD UI terintegrasi dengan backend)
- [x] Profil Admin & Pengaturan Akun (Ubah password, Avatar)
- [x] Pesan & Kontak Masuk (UI Inbox untuk membaca pesan dari landing page)

### Phase 6: Admin Dashboard - Fitur Inti Organisasi
- [ ] **Manajemen Arsip Dokumen (Document.php)**
  - [ ] Kategori Dokumen (AD/ART, PB, CA, PRS, POA, KPI)
  - [ ] Upload, Download, dan Hapus PDF/File dengan role access
- [ ] **Manajemen Kalender Kegiatan (Kalender.php)**
  - [ ] CRUD kalender event organisasi dengan color-coding
- [ ] **Keamanan & Log Audit (Settings.php)**
  - [ ] Security Logs UI (Aktivitas User)
  - [ ] Blocked IP Management
  - [ ] Failed Login Attempts monitoring (ContactSecurity)

### Phase 7: Advance Features - Form Builder & E-Sertifikat
- [ ] **Google Form Clone (Form.php)**
  - [ ] Form Builder JSON Logic (Drag-and-drop / Dynamic inputs)
  - [ ] Shareable Encrypted Link (Public Page)
  - [ ] Form Responses Dashboard & Export to CSV
- [ ] **E-Sertifikat Engine**
  - [ ] Batch Certificate Generator with BullMQ (Background job)
  - [ ] Generate QRCode Verifikasi
  - [ ] Pengiriman email otomatis ke penerima

### Phase 8: E-Voting System (Tradisional & Web3)
- [ ] **E-Voting Admin Management (Pemilihan.php)**
  - [ ] Setup Periode Waktu Pemilihan (Start & End Date)
  - [ ] Manajemen Kandidat & Visi Misi
  - [ ] Validasi Pemilih Mahasiswa (Import Data & OTP Status)
- [ ] **Web3 Blockchain Integration**
  - [ ] Write `Voting.sol` Smart Contract
  - [ ] Deploy contract to hardhat local / testnet
  - [ ] Ethers.js integration for User Voting Interface (Simpan hasil hashing ke block)
