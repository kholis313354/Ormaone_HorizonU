<?php

namespace App\Controllers;

use App\Models\BeritaModel;
use App\Models\FakultasModel;

class PublicBlogController extends BaseController
{
    protected $beritaModel;
    protected $fakultasModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
        $this->fakultasModel = new FakultasModel();
    }

    public function detail($encryptedId)
    {
        // Load helper untuk decrypt
        helper('nav');

        // Decode ID yang dienkripsi
        $id = decrypt_berita_id($encryptedId);

        // Jika decrypt gagal, coba sebagai ID biasa (untuk backward compatibility)
        if ($id === null) {
            // Coba parse sebagai integer langsung
            $id = (int) $encryptedId;
            if ($id <= 0) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        $berita = $this->beritaModel->select('berita.*, fakultas.nama_fakultas')
            ->join('fakultas', 'fakultas.id = berita.fakultas_id')
            ->where('berita.id', $id)
            ->where('berita.status', 'published')
            ->first();

        if (!$berita) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Ambil berita terkait berdasarkan kategori yang sama
        $relatedPosts = $this->beritaModel->where('kategori', $berita['kategori'])
            ->where('id !=', $id)
            ->where('status', 'published')
            ->orderBy('tanggal', 'DESC')
            ->limit(3)
            ->findAll();

        $data = [
            'title' => $berita['nama_kegiatan'] . ' - Blog Ormaone',
            'berita' => $berita,
            'kategori' => $this->beritaModel->getDistinctCategories(),
            'related_posts' => $relatedPosts,
            'active_menu' => 'blog'
        ];

        return view('page/berita_detail', $data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');
        $kategori = $this->request->getGet('kategori');

        $builder = $this->beritaModel->builder();
        $builder->select('berita.*, fakultas.nama_fakultas');
        $builder->join('fakultas', 'fakultas.id = berita.fakultas_id');
        $builder->where('berita.status', 'published');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('berita.nama_kegiatan', $keyword)
                ->orLike('berita.deskripsi1', $keyword)
                ->orLike('berita.kategori', $keyword)
                ->groupEnd();
        }

        if (!empty($kategori)) {
            $builder->where('berita.kategori', $kategori);
        }

        $data = [
            'title' => 'Hasil Pencarian - Blog Ormaone',
            'beritas' => $builder->orderBy('berita.tanggal', 'DESC')->get()->getResultArray(),
            'kategori' => $this->beritaModel->getDistinctCategories(),
            'keyword' => $keyword,
            'selectedKategori' => $kategori,
            'active_menu' => 'blog'
        ];

        return view('page/berita', $data);
    }

    public function byCategory($category)
    {
        $beritas = $this->beritaModel->select('berita.*, fakultas.nama_fakultas')
            ->join('fakultas', 'fakultas.id = berita.fakultas_id')
            ->where('berita.kategori', urldecode($category))
            ->where('berita.status', 'published')
            ->orderBy('berita.tanggal', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Kategori: ' . urldecode($category) . ' - Blog Ormaone',
            'beritas' => $beritas,
            'kategori' => $this->beritaModel->getDistinctCategories(),
            'current_category' => urldecode($category),
            'selectedKategori' => urldecode($category),
            'active_menu' => 'blog'
        ];

        return view('page/berita', $data);
    }

    /**
     * Method untuk menangani link share yang dienkripsi
     * Decode URL yang dienkripsi, ambil data berita, dan tampilkan preview dengan redirect
     */
    public function share($encryptedUrl)
    {
        try {
            // Load helper untuk decrypt
            helper('nav');

            // Decode URL yang dienkripsi
            $decodedUrl = base64_decode(urldecode($encryptedUrl));

            // Validasi URL
            if (!filter_var($decodedUrl, FILTER_VALIDATE_URL)) {
                return redirect()->to(base_url('berita'))->with('error', 'Link tidak valid');
            }

            // Extract ID berita dari URL (bisa encrypted atau tidak)
            $urlParts = parse_url($decodedUrl);
            $pathParts = explode('/', trim($urlParts['path'], '/'));
            $beritaId = null;

            // Cari ID berita dari path (berita/detail/{encrypted_id})
            if (in_array('berita', $pathParts) && in_array('detail', $pathParts)) {
                $detailIndex = array_search('detail', $pathParts);
                if (isset($pathParts[$detailIndex + 1])) {
                    $encryptedId = $pathParts[$detailIndex + 1];
                    // Decrypt ID yang dienkripsi
                    $beritaId = decrypt_berita_id($encryptedId);
                    // Jika decrypt gagal, coba sebagai ID biasa
                    if ($beritaId === null) {
                        $beritaId = (int) $encryptedId;
                    }
                }
            }

            // Jika berhasil mendapatkan ID, ambil data berita
            $berita = null;
            if ($beritaId && $beritaId > 0) {
                $berita = $this->beritaModel->select('berita.*, fakultas.nama_fakultas')
                    ->join('fakultas', 'fakultas.id = berita.fakultas_id')
                    ->where('berita.id', $beritaId)
                    ->where('berita.status', 'published')
                    ->first();
            }

            // Jika berita tidak ditemukan atau ID tidak valid, redirect langsung
            if (!$berita) {
                // Redirect dengan meta refresh untuk memastikan Open Graph tags tetap bisa di-fetch
                return view('page/berita_share_redirect', [
                    'redirectUrl' => $decodedUrl,
                    'berita' => null
                ]);
            }

            // Tampilkan halaman share dengan Open Graph tags dan redirect
            return view('page/berita_share_redirect', [
                'redirectUrl' => $decodedUrl,
                'berita' => $berita,
                'title' => $berita['nama_kegiatan'] . ' - Blog Ormaone'
            ]);

        } catch (\Exception $e) {
            // Jika terjadi error, redirect ke halaman berita
            return redirect()->to(base_url('berita'))->with('error', 'Link tidak valid atau telah kedaluwarsa');
        }
    }
}
