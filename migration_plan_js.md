# Rencana Migrasi Ormaone: CI4 ke Fullstack JavaScript (JS-Rewrite)

Dokumen ini adalah cetak biru (blueprint) komprehensif untuk memigrasi aplikasi Ormaone dari CodeIgniter 4 ke ekosistem JavaScript modern (React + Node.js + Prisma + Solidity) tanpa downtime pada aplikasi saat ini.

---

## 1. Struktur Folder Baru (`js-rewrite`)

Buat folder `js-rewrite` di dalam `public_html/`. Layout monorepo sederhana ini akan memisahkan frontend, backend, dan smart contract blockchain.

```text
public_html/
├── app/                  (CodeIgniter 4 - Existing)
├── public/               (CodeIgniter 4 - Existing)
└── js-rewrite/           (Proyek Baru - JS Stack)
    ├── frontend/         (React 18 + Vite + TailwindCSS)
    │   ├── public/       (Aset gambar statis, favicon)
    │   ├── src/
    │   │   ├── assets/   (Aset internal React)
    │   │   ├── components/(Komponen re-usable: Navbar, Footer, Card, UI)
    │   │   ├── layouts/  (Layout halaman: AdminLayout, PublicLayout)
    │   │   ├── pages/    (Halaman utama misal: Home, Berita, Dashboard)
    │   │   ├── services/ (Axios API calls & Ethers.js integration)
    │   │   ├── store/    (Zustand / Redux state)
    │   │   └── App.jsx   (Main Router)
    │   ├── package.json
    │   ├── tailwind.config.js
    │   └── vite.config.js
    ├── backend/          (Node.js + Express + Prisma)
    │   ├── prisma/
    │   │   └── schema.prisma (Skema Database PostgreSQL)
    │   ├── src/
    │   │   ├── controllers/
    │   │   ├── middlewares/ (JWT, Rate Limiter)
    │   │   ├── routes/
    │   │   ├── services/    (Logic background job: BullMQ)
    │   │   ├── utils/       (Helper: mailer, qr-generator, logger)
    │   │   └── index.js     (Entry point Express)
    │   ├── .env
    │   └── package.json
    └── blockchain/       (Solidity + Hardhat)
        ├── contracts/    (Solidity Smart Contracts)
        ├── scripts/      (Deploy scripts)
        ├── test/         (Unit test smart contract)
        └── hardhat.config.js
```

---

## 2. Urutan Migrasi Fitur (Phase-by-Phase)

Strategi migrasi disarankan menggunakan pendekatan **Bottom-Up (Dari fundamental ke kompleks)**:

*   **Fase 1: Fondasi & Database (Minggu 1-2)**
    *   *Setup* repositori, inisialisasi Prisma, merancang `schema.prisma` berkaca dari MySQL export `u234715368_ormaone26`.
    *   Migrasi data awal (Fakultas, Organisasi, User, Log Keamanan) menggunakan script migrasi DB.
    *   *Alasan:* Semua fitur bergantung pada integritas database dan autentikasi. Tanpa ini, UI tidak bisa diintegrasikan.
*   **Fase 2: Auth, Keamanan & Master Data (Minggu 3)**
    *   Implementasi JWT, Bcrypt, Middleware Rate Limiting, Log System (Blocked IP).
    *   CRUD Master Data (Fakultas, Organisasi, Kepengurusan, User Profile).
    *   *Alasan:* Menjamin keamanan lebih awal dan master data siap digunakan oleh modul transaksional.
*   **Fase 3: Refaktor UI Publik & Template (Minggu 4-5)**
    *   Konversi template HTML CI4 (`app/Views/` dan `public/`) menjadi komponen React + Tailwind.
    *   Halaman Beranda, Struktur Organisasi, Kontak/Aspirasi, dan Blog (Public & Admin).
    *   *Alasan:* Frontend publik adalah wajah aplikasi. Tampilan existing dicopas HTML-nya, diconvert ke element JSX.
*   **Fase 4: Fitur Dinamis (Form & Sertifikat) (Minggu 6-7)**
    *   Penerjemahan modul *Dynamic Forms* menggunakan struktur fitur `JSONB` milik postgreSQL.
    *   Modul E-Sertifikat dan arsip dokumen dikonversi memakai *background-processing* `BullMQ` agar file batch ratusan sertifikat tidak mengalami RTO limit API.
*   **Fase 5: Blockchain E-Voting & Integrasi Web3 (Minggu 8-9)**
    *   Penulisan Solidity *contract*, setup Hardhat, integrasi Ethers.js dengan React.
    *   Pemilu, Calon, UI Voting, dan enkripsi payload.
    *   *Alasan:* Blockchain ditambahkan karena ada kendala kapabilitas pada *shared storage*. Ini menuntaskan kerangka voting.
*   **Fase 6: Testing & Deployment (Minggu 10)**
    *   Uji coba paralel (CI4 dan JS berjalan bersama di server yang sama tapi beda `port` dan `dir`).
    *   Begitu rampung, arahkan domain (*Document Root*) ke `js-rewrite/frontend/dist` menggunakan routing NGINX/htaccess.

---

## 3. Konversi Template CI4 ke React

### A. Identifikasi Komponen
Pada CI4, tampilan biasanya menggunakan `<?= $this->include('layouts/partials/header') ?>`. Di React, ini menjadi hierarki komponen.
1.  **Layouts**: Diekstrak ke `src/layouts/PublicLayout.jsx` (berisi Navbar & Footer).
2.  **Card Berita/Organisasi**: Diekstrak ke komponen micro seperti `src/components/ui/BeritaCard.jsx`.

### B. Contoh Kode Komponen (React + Tailwind)
Tampilan UI dipertahankan dengan menerjemahkan barisan class *bootstrap* (atau custom css yang lama) setara ke Tailwind utility.
```jsx
// src/components/layout/Navbar.jsx
import { Link } from 'react-router-dom';

export default function Navbar() {
  return (
    <nav className="fixed w-full bg-white shadow-md z-50">
      <div className="max-w-7xl mx-auto px-4 flex justify-between h-16">
        <div className="flex items-center">
          <Link to="/" className="text-2xl font-bold text-blue-900 border-none">ORMAONE</Link>
        </div>
        <div className="flex items-center space-x-6">
          <Link to="/" className="text-gray-700 hover:text-blue-600 font-medium transition">Beranda</Link>
          <Link to="/berita" className="text-gray-700 hover:text-blue-600 font-medium transition">Berita</Link>
          <Link to="/login" className="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
            Masuk
          </Link>
        </div>
      </div>
    </nav>
  );
}
```

### C. Cara Menangani Routing (React Router v6)
```jsx
// src/App.jsx
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import PublicLayout from './layouts/PublicLayout';
import AdminLayout from './layouts/AdminLayout';
import HomePage from './pages/HomePage';
import DashboardPage from './pages/admin/DashboardPage';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        {/* Rute Publik memanggil template header/footer publik */}
        <Route element={<PublicLayout />}>
          <Route path="/" element={<HomePage />} />
          <Route path="/berita" element={<BeritaPage />} />
          <Route path="/form/public/:id" element={<PublicFormPage />} />
        </Route>

        {/* Rute Admin dilindungi JWT */}
        <Route path="/admin" element={<AdminLayout />}>
          <Route path="dashboard" element={<DashboardPage />} />
          <Route path="sertifikat" element={<ManageSertifikatPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}
```

---

## 4. Backend & Database (Node.js + Prisma)

### A. Migrasi MySQL CI4 ke PostgreSQL Prisma
Mengacu pada `u234715368_ormaone26.sql`, tabel `anggotas`, `berita`, `forms`, dsb diterjemahkan langsung ke blok definisi Prisma `schema.prisma`. Fitur migrasi data cukup di-*handle* via library `pg-promise` atau `mysql2` untuk ETL (Mengekstrak data `mysql` CI4 dan mem-*push*-nya via klien `prisma`).

Contoh `schema.prisma` yang setara:
```prisma
generator client {
  provider = "prisma-client-js"
}

datasource db {
  provider = "postgresql"
  url      = env("DATABASE_URL")
}

model User {
  id        Int      @id @default(autoincrement())
  name      String
  email     String   @unique
  password  String
  level     Int      @default(0) // 0: User, 1: Admin, 2: Superadmin
  status    String   @default("active")
  createdAt DateTime @default(now())
  
  // Rangkaian relasi ke tabel arsip_documents, berita, forms
  forms     Form[]
  berita    Berita[]
}
```

### B. Contoh Autentikasi JWT (Express Middleware)
```javascript
// backend/src/middlewares/authMiddleware.js
const jwt = require('jsonwebtoken');

exports.verifyToken = (req, res, next) => {
  const authHeader = req.headers.authorization;
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    return res.status(403).json({ message: "Token atau kredensial JWT tidak disertakan!" });
  }

  const token = authHeader.split(" ")[1];
  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    req.user = decoded; // Object berisikan { id, level, email }
    next();
  } catch (err) {
    return res.status(401).json({ message: "Token tidak valid atau sudah kadaluarsa (Expired)." });
  }
};
```

---

## 5. Blockchain untuk E-Voting (Solidity)

Karena storage pada server rawan gangguan kompromi nilai suara tabel di DB, E-Voting yang adil akan bermuara di ekosistem Smart Contract.

### A. Contoh Smart Contract Sederhana (E-Voting)
Struktur script di `blockchain/contracts/Voting.sol`:
```solidity
// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

contract OrmaoneVoting {
    struct Candidate {
        uint id;
        string name;
        uint voteCount;
    }

    mapping(uint => Candidate) public candidates;
    mapping(address => bool) public votersWalletCheck; // Pencegah multi-wallet casting
    mapping(string => bool) public hasVotedNimHash; // Validasi di tingkat Hash-NIM
    
    uint public candidatesCount;
    bool public votingActive = true;
    address public admin;

    constructor() { admin = msg.sender; }

    function addCandidate(string memory _name) public {
        require(msg.sender == admin, "Terlarang: Hanya Master Admin!");
        candidatesCount++;
        candidates[candidatesCount] = Candidate(candidatesCount, _name, 0);
    }

    // Strategi Enkripsi Commit-Reveal & Homomorphic: Hindari masukin identitas murni. Node.js backend akan melempar hash NIM ke user
    function vote(uint _candidateId, string memory _hashedNim) public {
        require(votingActive, "Interval voting sudah berakhir/ditutup.");
        require(!votersWalletCheck[msg.sender], "Alamat DApp wallet ini sudah memberi suara!");
        require(!hasVotedNimHash[_hashedNim], "Identifikasi Hash Partisipan telah tercatat berpartisipasi!");
        require(_candidateId > 0 && _candidateId <= candidatesCount, "Nomor urut paslon kandidat tidak disahkan.");

        votersWalletCheck[msg.sender] = true;
        hasVotedNimHash[_hashedNim] = true;
        candidates[_candidateId].voteCount++;
    }
}
```

### B. Interaksi Frontend (Ethers.js) & Strategi Enkripsi
- Frontend React memanfaatkan `ethers.js` untuk menghubungkan koneksi dompet Web3 MetaMask.
- **Strategi Suara:** Di sisi backend (NodeJS) peserta memverifikasi E-mail OTP mereka. Begitu tervalidasi, NodeJS menciptakan `keccak256(NIM + RAHASIASERVER)` sebagai Hash, dan mengembalikannya ke UI React. Hash nir-tebak ini digunakan pengguna untuk mengirimkan suara kepada transaksi metode `vote()` pada Solidity. Praktis identitas sangatlah anonim.

---

## 6. Fitur Kompleks Lainnya

### A. Form Builder (GForm Clone) - Pendekatan JSON Schema
Alih-alih membuat tabel DB terpisah untuk tipe text, tipe opsi, dsb. Pada JavaScript (khususnya PostgreSQL), format arsitektur datanya bermutasi ke relasional `JSONB`.
*   Struktur *Fields Form* akan menampung JSON persis layaknya properti array `[{type: 'checkbox', required: true, options: ["Ya", "Tidak"]}]`.
*   Rangkaian antarmuka React diselipi `dnd-kit` (Drag & Drop framework) untuk memperlancar UI pembangun formulir secara mulus.

### B. Batch Generator E-Sertifikat (BullMQ Queueing)
Masalah terbesar di CI4 adalah waktu tunggu API saat looping mem-print ribuan sertifikat (rawan `504 Gateway Timeout`). Di Node.js ini diselesaikan via **BullMQ** (membutuhkan in-memory Redis):
1.  Admin mengunggah file spreadsheet Excel berisi 1000 nama.
2.  API Node (Express) menelan rincian dan meregistrasikan 1000 penugasan (jobs) tunggal kepada layanan `Bull Queue`, langsung memberikan respons berhasil dalam 0.05ms (Tanpa menunggu antrean foto jadi).
3.  Background worker Bull memproduksi foto, menarik pustaka `qrcode` NodeJS & melukis *Canvas* / *Jimp*, satu per satu mandiri. 
4.  Konektor socket/polling dari React Frontend sekedar mencetak *loading progress bar* dari job pool ini.

### C. FullCalendar Event & Security Check
*   Pustaka React v6 (FullCalendar.io) menempel *natively* tanpa injeksi JQuery spaghetti di tampilan MVC lama.
*   **Logging, Honeypot & IP Blocker**: Diadopsi melalui middleware standar Node: `express-rate-limit` per jalur route, dan middleware pendeteksi payload siluman (*honeypot body check*) agar melabeli bot untuk dimasukkan ke tabel pemblokiran permanen (`ip_blocked` model prisma).

---

## 7. Checklist Deployment Web Server

Langkah integrasi ke struktur CPanel/VPS yang memayungi domain `huschorizonu.com` saat ini:

1.  **Dukungan Node.js Eksekutif**: Aktifkan App NodeJS (Bila menggunakan cPanel/Cyberpanel) atau sewa VPS cloud minimal memori 2GB karena menampung background worker gambar. Instalasikan dependensi PM2 secara global.
2.  **Jalankan Daemon Backend (PM2)**:
    ```bash
    cd js-rewrite/backend
    npm install
    npx prisma generate && npx prisma migrate deploy
    pm2 start src/index.js --name "ormaone-backend"
    ```
3.  **Membangun Frontend React Statis**:
    ```bash
    cd js-rewrite/frontend
    # Sesuaikan url API di env variabel saat production
    npm run build
    ```
4.  **Reverse Proxy via NGINX / .htaccess**:
    Seluruh *traffic* web root wajib masuk mengeksekusi struktur folder `/js-rewrite/frontend/dist` (merupakan index HTML murni). Sementara parameter awalan rute seperti `huschorizonu.com/api/` ditangkap *Proxy Pass* aliasnya menuju gerbang server Node di lokasi Port `http://localhost:3000`. 
5.  **Environment Variables (`.env`) Penting**:
    ```env
    DATABASE_URL="postgresql://db_user:password@localhost:5432/ormaone_js?schema=public"
    JWT_SECRET="OrmaOneSecretHashSignKunci@!26"
    RPC_URL="https://sepolia.infura.io/v3/INFURA-PROJECT-KEY"
    PRIVATE_KEY="WALLET-DEPLOYER-PRIVATE-KEY"
    REDIS_URL="redis://127.0.0.1:6379" # Untuk kebutuhan BullMQ Server
    ```
