<?php

namespace App\Controllers;

class Home extends BaseController
{
    private $pemilihanModel;
    private $pemilihanCalonModel;
    private $pemilihanCalonSuaraModel;

    public function __construct()
    {
        $this->pemilihanModel = new \App\Models\PemilihanModel();
        $this->pemilihanCalonModel = new \App\Models\PemilihanCalonModel();
        $this->pemilihanCalonSuaraModel = new \App\Models\PemilihanCalonSuaraModel();
    }

    public function index()
    {
        $title = 'Home';
        $organisasiModel = new \App\Models\OrganisasiModel();
        $organisasis = $organisasiModel->findAll();

        return view('page/home', compact('title', 'organisasis'));
    }

    public function voting()
    {
        $title = 'Voting';
        $calon = $this->pemilihanCalonModel->select('
                pemilihan_calons.*, 
                pemilihans.periode as pemilihan_periode, 
                organisasis.name as organisasi_name, 
                anggota_1.name as anggota_1_name,
                anggota_1.nim as anggota_1_nim, 
                anggota_2.name as anggota_2_name,
                anggota_2.nim as anggota_2_nim
            ')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id', 'left')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id', 'left')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id', 'left')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id', 'left')
            ->where('pemilihans.status', 'publish')
            ->orderBy('pemilihan_calons.number', 'ASC')
            ->findAll();

        // Sum all suara hanya dari pemilihan dengan status publish
        $totalSuaras = [];
        $pemilihanCalons = $this->pemilihanCalonModel->select(
            '
                pemilihan_calons.*,
                pemilihans.status as pemilihan_status,
                anggota_1.name as anggota_1_name,
                anggota_1.nim as anggota_1_nim,
                anggota_2.name as anggota_2_name,
                anggota_2.nim as anggota_2_nim'
        )
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
            ->where('pemilihans.status', 'publish')
            ->findAll();

        // Optimasi query: Ambil hitungan suara sekaligus menggunakan GROUP BY
        $voteCounts = $this->pemilihanCalonSuaraModel->select('pemilihan_calon_id, COUNT(*) as total')
            ->where('status', '1')
            ->groupBy('pemilihan_calon_id')
            ->findAll();

        // Buat map id -> total untuk akses cepat
        $voteMap = [];
        foreach ($voteCounts as $vc) {
            $voteMap[$vc['pemilihan_calon_id']] = $vc['total'];
        }

        foreach ($pemilihanCalons as $key => $value) {
            // Ambil total dari map, default 0 jika tidak ada
            $total = $voteMap[$value['id']] ?? 0;
            $totalSuaras[$value['anggota_1_name'] . ' & ' . $value['anggota_2_name']] = $total;
        }

        // Get all active (publish) elections
        $pemilihans = $this->pemilihanModel->where('status', 'publish')
            ->orderBy('tanggal_akhir', 'DESC')
            ->findAll();

        // If no publish, check for selesai
        $isSelesai = false;
        if (empty($pemilihans)) {
            $pemilihanSelesai = $this->pemilihanModel->where('status', 'selesai')
                ->orderBy('created_at', 'DESC')
                ->first();
            $isSelesai = $pemilihanSelesai !== null || empty($totalSuaras);
            $pemilihan = $pemilihanSelesai;
        } else {
            $pemilihan = $pemilihans[0]; // Use the latest for the main countdown
        }

        return view('page/voting', compact('title', 'calon', 'totalSuaras', 'pemilihan', 'isSelesai', 'pemilihans'));
    }

    public function votingDetail($encryptedId)
    {
        // Load helper untuk decrypt
        helper('nav');

        // Decode ID yang dienkripsi
        $id = decrypt_voting_id($encryptedId);

        // Jika decrypt gagal, coba sebagai ID biasa (untuk backward compatibility)
        if ($id === null) {
            // Coba parse sebagai integer langsung
            $id = (int) $encryptedId;
            if ($id <= 0) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kandidat tidak ditemukan atau pemilihan tidak tersedia.');
            }
        }

        $title = 'Voting Detail';
        $calon = $this->pemilihanCalonModel->select('
                pemilihan_calons.*, 
                pemilihans.periode as pemilihan_periode, 
                pemilihans.tanggal_mulai,
                pemilihans.tanggal_akhir,
                pemilihans.status as pemilihan_status,
                organisasis.name as organisasi_name,
                organisasis.kode_fakultas as organisasi_kode_fakultas,
                organisasis.id as organisasi_id,
                anggota_1.name as anggota_1_name,
                anggota_1.nim as anggota_1_nim, 
                anggota_2.name as anggota_2_name,
                anggota_2.nim as anggota_2_nim
            ')
            ->join('pemilihans', 'pemilihan_calons.pemilihan_id = pemilihans.id')
            ->join('organisasis', 'pemilihans.organisasi_id = organisasis.id')
            ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
            ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
            ->whereIn('pemilihans.status', ['publish', 'selesai']) // Izinkan status publish dan selesai
            ->where('pemilihan_calons.id', $id)
            ->first();

        // Jika calon tidak ditemukan, tampilkan error
        if (!$calon) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kandidat tidak ditemukan atau pemilihan tidak tersedia.');
        }

        // Get list suara - hanya suara yang sudah tervalidasi (status = '1')
        // Supaya konsisten dengan perhitungan di grafik statistik (totalSuaras)
        $suara = $this->pemilihanCalonSuaraModel->select('email, name, nim')
            ->where('pemilihan_calon_id', $id)
            ->where('status', '1')
            ->paginate(20, 'suara');

        $pager = $this->pemilihanCalonSuaraModel->pager;

        // Sum all suara
        $totalSuaras = [];
        // Pastikan calon ada sebelum mengakses pemilihan_id
        if ($calon && isset($calon['pemilihan_id'])) {
            $pemilihan = $this->pemilihanCalonModel->select(
                '
                    pemilihan_calons.*,
                    anggota_1.name as anggota_1_name,
                    anggota_1.nim as anggota_1_nim,
                    anggota_2.name as anggota_2_name,
                    anggota_2.nim as anggota_2_nim'
            )
                ->where('pemilihan_id', $calon['pemilihan_id'])
                ->join('anggotas as anggota_1', 'pemilihan_calons.anggota_id_1 = anggota_1.id')
                ->join('anggotas as anggota_2', 'pemilihan_calons.anggota_id_2 = anggota_2.id')
                ->findAll();

            foreach ($pemilihan as $key => $value) {
                $total = $this->pemilihanCalonSuaraModel->where('pemilihan_calon_id', $value['id'])->where('status', '1')->countAllResults();
                $totalSuaras[$value['anggota_1_name'] . ' & ' . $value['anggota_2_name']] = $total;
            }
        }

        // Get all organisasi untuk fakultasMapping
        $organisasiModel = new \App\Models\OrganisasiModel();
        $allOrganisasi = $organisasiModel->select('name, kode_fakultas')->findAll();
        $fakultasMapping = [];
        $allKodeFakultas = [];

        foreach ($allOrganisasi as $org) {
            $orgName = $org['name']; // Keep original case untuk matching
            $kodeFakultas = $org['kode_fakultas'];

            // Jika kode_fakultas NULL atau kosong, berarti organisasi UNIVERSITAS (semua NIM bisa vote)
            if (empty($kodeFakultas) || $kodeFakultas === 'NULL' || $kodeFakultas === null) {
                // Jika belum ada UNIVERSITAS, buat array semua kode fakultas
                if (!isset($fakultasMapping['UNIVERSITAS'])) {
                    // Ambil semua kode fakultas dari organisasi lain
                    $universitasKodes = [];
                    foreach ($allOrganisasi as $org2) {
                        if (!empty($org2['kode_fakultas']) && $org2['kode_fakultas'] !== 'NULL' && $org2['kode_fakultas'] !== null) {
                            $kodes = explode(',', $org2['kode_fakultas']);
                            $kodes = array_map('trim', $kodes);
                            foreach ($kodes as $kode) {
                                if (!in_array($kode, $universitasKodes)) {
                                    $universitasKodes[] = $kode;
                                }
                            }
                        }
                    }
                    $fakultasMapping['UNIVERSITAS'] = $universitasKodes;
                }
                continue;
            }

            // Parse kode_fakultas (bisa comma-separated)
            $kodes = explode(',', $kodeFakultas);
            $kodes = array_map('trim', $kodes);
            $kodes = array_filter($kodes); // Remove empty values

            // Simpan mapping dengan nama organisasi sebagai key
            $fakultasMapping[$orgName] = array_values($kodes);

            // Tambahkan ke allKodeFakultas untuk validasi umum
            foreach ($kodes as $kode) {
                if (!in_array($kode, $allKodeFakultas)) {
                    $allKodeFakultas[] = $kode;
                }
            }
        }

        // Pastikan calon organisasi memiliki informasi kode_fakultas
        $calonOrganisasiKodeFakultas = $calon['organisasi_kode_fakultas'] ?? null;

        // Normalisasi nilai null (bisa dari database sebagai NULL, 'NULL', atau null)
        if (
            $calonOrganisasiKodeFakultas === null ||
            $calonOrganisasiKodeFakultas === 'NULL' ||
            $calonOrganisasiKodeFakultas === '' ||
            trim($calonOrganisasiKodeFakultas) === '' ||
            strtoupper(trim($calonOrganisasiKodeFakultas)) === 'NULL'
        ) {
            $calonOrganisasiKodeFakultas = null; // Set to null untuk memudahkan pengecekan
        }

        // Cek apakah voting masih aktif atau sudah expired
        $tanggalMulai = $calon['tanggal_mulai'] ?? null;
        $tanggalAkhir = $calon['tanggal_akhir'] ?? null;
        $pemilihanStatus = $calon['pemilihan_status'] ?? null;
        $isVotingActive = false;
        $votingMessage = '';

        // Jika status pemilihan adalah 'selesai', langsung set tidak aktif
        if ($pemilihanStatus === 'selesai') {
            $isVotingActive = false;
            $votingMessage = 'Pemilihan sudah selesai. Terima kasih atas partisipasi Anda.';
        } elseif ($tanggalMulai && $tanggalAkhir) {
            $now = date('Y-m-d H:i:s');
            $mulai = strtotime($tanggalMulai);
            $akhir = strtotime($tanggalAkhir);
            $sekarang = strtotime($now);

            if ($sekarang >= $mulai && $sekarang <= $akhir) {
                $isVotingActive = true;
            } elseif ($sekarang < $mulai) {
                $votingMessage = 'Voting belum dimulai. Voting akan dimulai pada ' . date('d F Y H:i', $mulai);
            } else {
                $isVotingActive = false;
                $votingMessage = 'Voting sudah berakhir pada ' . date('d F Y H:i', $akhir);
            }
        } else {
            // Jika tidak ada tanggal, anggap voting aktif (backward compatibility)
            $isVotingActive = true;
        }

        return view('page/voting-detail', compact('title', 'calon', 'suara', 'pager', 'totalSuaras', 'fakultasMapping', 'allKodeFakultas', 'calonOrganisasiKodeFakultas', 'tanggalMulai', 'tanggalAkhir', 'isVotingActive', 'votingMessage'));
    }

    public function vote()
    {
        // Method ini tidak lagi digunakan karena voting sekarang menggunakan OTP
        // Redirect ke halaman voting detail
        return redirect()->back()->with('errors', 'Silakan gunakan sistem OTP untuk voting.');
    }

    public function sertifikat()
    {
        $title = 'Sertifikat';
        return view('page/sertifikat', compact('title'));
    }

    public function berita()
    {
        $beritaModel = new \App\Models\BeritaModel();
        $fakultasModel = new \App\Models\FakultasModel();

        // Ambil filter kategori dari query string
        $selectedKategori = $this->request->getGet('kategori');

        // Filter berita berdasarkan kategori
        $beritas = $beritaModel->getAllPublishedWithFakultas($selectedKategori);

        $data = [
            'title' => 'Blog Ormaone - Berita Terkini',
            'beritas' => $beritas,
            'kategori' => $beritaModel->getDistinctCategories(),
            'selectedKategori' => $selectedKategori,
            'active_menu' => 'blog'
        ];

        return view('page/berita', $data);
    }

    public function struktur()
    {
        $title = 'Struktur';

        // Get organisasi_id dari query parameter (terenkripsi)
        $encryptedOrg = $this->request->getGet('org');
        $organisasiId = null;

        if (!empty($encryptedOrg)) {
            try {
                $encrypter = \Config\Services::encrypter();

                // Coba decode sebagai hex (hasil bin2hex)
                $binary = @hex2bin($encryptedOrg);

                if ($binary !== false) {
                    $decrypted = $encrypter->decrypt($binary);
                    $organisasiId = (int) $decrypted;
                } elseif (ctype_digit($encryptedOrg)) {
                    // Fallback: dukung format lama jika masih numeric biasa
                    $organisasiId = (int) $encryptedOrg;
                }
            } catch (\Throwable $e) {
                // Jika gagal decrypt dan nilai numeric, gunakan sebagai ID biasa
                if (ctype_digit((string) $encryptedOrg)) {
                    $organisasiId = (int) $encryptedOrg;
                }
            }
        }

        $tahun = $this->request->getGet('tahun');

        $strukturTampilanModel = new \App\Models\StrukturTampilanModel();
        $organisasiModel = new \App\Models\OrganisasiModel();

        $struktur = null;
        $organisasi = null;
        $availableYears = [];

        if ($organisasiId) {
            // Get organisasi data
            $organisasi = $organisasiModel->find($organisasiId);

            if ($organisasi) {
                // Get available years untuk filter
                $availableYears = $strukturTampilanModel->getAvailableYears($organisasiId);

                // Get struktur berdasarkan organisasi_id dan tahun
                if ($tahun) {
                    $struktur = $strukturTampilanModel->getByOrganisasiAndTahun($organisasiId, $tahun);
                } else {
                    // Jika tidak ada tahun, ambil yang aktif atau terbaru
                    $struktur = $strukturTampilanModel->getActiveStruktur($organisasiId);
                }

                // Debug: Pastikan struktur ditemukan
                if (!$struktur) {
                    // Coba ambil data apapun yang ada untuk organisasi ini
                    $struktur = $strukturTampilanModel->where('organisasi_id', $organisasiId)
                        ->orderBy('tahun', 'DESC')
                        ->orderBy('created_at', 'DESC')
                        ->first();
                }
            }
        }

        // Fetch Divisions
        $divisi = [];
        $visiMisi = null;
        $proker = null;
        if ($struktur && isset($struktur['organisasi_id']) && isset($struktur['tahun'])) {
            $strukturTampilanDivisiModel = new \App\Models\StrukturTampilanDivisiModel();
            $divisi = $strukturTampilanDivisiModel->where('organisasi_id', $struktur['organisasi_id'])
                ->where('tahun', $struktur['tahun'])
                ->findAll();

            $visiMisiModel = new \App\Models\VisiMisiModel();
            $visiMisi = $visiMisiModel->where('organisasi_id', $struktur['organisasi_id'])
                ->where('tahun', $struktur['tahun'])
                ->first();

            // Fallback: Jika tidak ditemukan berdasarkan tahun, cari yang aktif
            if (!$visiMisi) {
                $visiMisi = $visiMisiModel->where('organisasi_id', $struktur['organisasi_id'])
                    ->where('is_active', 1)
                    ->first();
            }

            // Fetch Proker
            $prokerModel = new \App\Models\ProgramKerjaModel();
            $proker = $prokerModel->where('organisasi_id', $struktur['organisasi_id'])
                ->where('tahun', $struktur['tahun'])
                ->first();

            if (!$proker) {
                // If not found by specific year, fetch any active for this organization
                $proker = $prokerModel->where('organisasi_id', $struktur['organisasi_id'])
                    ->groupStart()
                    ->where('is_active', 'Coming soon')
                    ->orWhere('is_active', 'Progres')
                    ->groupEnd()
                    ->first();
            }

            // Fetch Anggaran
            $anggaranModel = new \App\Models\AnggaranModel();
            $anggaran = $anggaranModel->where('organisasi_id', $struktur['organisasi_id'])
                ->where('tahun', $struktur['tahun'])
                ->first();
        }

        return view('page/struktur', compact('title', 'struktur', 'organisasi', 'availableYears', 'organisasiId', 'tahun', 'divisi', 'visiMisi', 'proker', 'anggaran'));
    }
}
