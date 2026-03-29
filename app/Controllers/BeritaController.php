<?php

namespace App\Controllers;

use App\Models\BeritaModel;
use App\Models\FakultasModel;
use Config\Database;

class BeritaController extends BaseController
{
    protected $beritaModel;
    protected $fakultasModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
        $this->fakultasModel = new FakultasModel();
        helper(['form', 'url']);
    }

    // Fungsi helper untuk pengecekan login
    private function checkLogin()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'))->with('error', 'Silakan login terlebih dahulu');
        }
        return null;
    }

    public function index()
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $level = session()->get('level');

        // Level 1 (SuperAdmin) tidak memiliki akses ke Blogger
        if ($level == 1) {
            return redirect()->to(url_to('dashboard'))
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
        $userId = session()->get('id');
        $userName = session()->get('name');
        $userEmail = session()->get('email');

        // Get berita berdasarkan level user
        $beritas = $this->beritaModel->getBeritaByUser($level, $userId, $userName, $userEmail);

        $title = 'Blogger';
        $data = [
            'title' => 'Blogger',
            'beritas' => $beritas,
            'facultyStats' => $this->beritaModel->getBeritaCountByFaculty(),
            'level' => $level
        ];

        return view('page/berita/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $data = [
            'title' => 'Tambah Berita',
            'fakultas' => $this->fakultasModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('page/berita/create', $data);
    }

    public function store()
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $kategori = $this->request->getPost('kategori');

        $rules = [
            'nama_kegiatan' => 'required|max_length[255]',
            'kategori' => 'required|in_list[blogger,podcast]',
            'fakultas_id' => 'required|numeric',
            'tanggal' => 'required|valid_date',
            'deskripsi1' => 'required',
            'deskripsi2' => 'permit_empty',
            'deskripsi3' => 'permit_empty'
        ];

        // Validasi khusus berdasarkan kategori
        if ($kategori === 'podcast') {
            // Untuk podcast: link wajib, gambar tidak wajib
            $rules['link'] = 'required|valid_url|max_length[500]';
            $rules['gambar'] = [
                'if_exist',
                'uploaded[gambar]',
                'mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'max_size[gambar,2048]',
            ];
        } else {
            // Untuk blogger: gambar wajib, link tidak wajib
            $rules['gambar'] = [
                'uploaded[gambar]',
                'mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'max_size[gambar,2048]',
            ];
            $rules['link'] = 'permit_empty|valid_url|max_length[500]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('gambar');
        $namaGambar = null;

        // Upload gambar hanya jika ada file dan kategori bukan podcast (atau jika podcast juga upload gambar)
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaGambar = $file->getRandomName();
            $file->move(FCPATH . 'uploads/berita', $namaGambar);
        }

        helper('security'); // Load custom security helper

        $dataToSave = [
            'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
            'kategori' => $this->request->getPost('kategori'),
            'fakultas_id' => $this->request->getPost('fakultas_id'),
            'tanggal' => $this->request->getPost('tanggal'),
            'deskripsi1' => clean_html($this->request->getPost('deskripsi1')),
            'deskripsi2' => clean_html($this->request->getPost('deskripsi2')),
            'deskripsi3' => clean_html($this->request->getPost('deskripsi3')),
            'link' => $this->request->getPost('link') ?: null,
            'user_id' => session()->get('id'),
            'user_name' => session()->get('name'),
            'user_email' => session()->get('email'),
        ];

        // Hanya tambahkan gambar jika ada
        if ($namaGambar) {
            $dataToSave['gambar'] = $namaGambar;
        }

        $this->beritaModel->save($dataToSave);

        return redirect()->to('/page/berita')->with('success', 'Berita berhasil ditambahkan');
    }

    public function edit($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $berita = $this->beritaModel->find($id);
        if (!$berita) {
            return redirect()->to('/page/berita')->with('error', 'Berita tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Berita',
            'berita' => $berita,
            'fakultas' => $this->fakultasModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('page/berita/edit', $data);
    }

    public function update($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $berita = $this->beritaModel->find($id);
        if (!$berita) {
            return redirect()->to('/page/berita')->with('error', 'Berita tidak ditemukan');
        }

        $rules = [
            'nama_kegiatan' => 'required',
            'kategori' => 'required|in_list[blogger,podcast]',
            'fakultas_id' => 'required|numeric',
            'tanggal' => 'required|valid_date',
            'gambar' => [
                'if_exist', // Validasi hanya jika field ada
                'uploaded[gambar]',
                'mime_in[gambar,image/jpg,image/jpeg,image/png,image/webp]',
                'is_image[gambar]'
            ],
            'link' => 'permit_empty|valid_url|max_length[500]'
        ];

        // Validasi khusus: jika kategori podcast, link wajib diisi
        $kategori = $this->request->getPost('kategori');
        if ($kategori === 'podcast') {
            $rules['link'] = 'required|valid_url|max_length[500]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('gambar');
        $namaGambar = $berita['gambar'];

        // Cek apakah user mengupload file baru
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Hapus gambar lama jika ada
            if ($berita['gambar'] && file_exists(FCPATH . 'uploads/berita/' . $berita['gambar'])) {
                unlink(FCPATH . 'uploads/berita/' . $berita['gambar']);
            }

            $namaGambar = $file->getRandomName();
            $file->move(FCPATH . 'uploads/berita', $namaGambar);
        }

        helper('security');

        $this->beritaModel->save([
            'id' => $id,
            'nama_kegiatan' => $this->request->getPost('nama_kegiatan'),
            'kategori' => $this->request->getPost('kategori'),
            'fakultas_id' => $this->request->getPost('fakultas_id'),
            'tanggal' => $this->request->getPost('tanggal'),
            'deskripsi1' => clean_html($this->request->getPost('deskripsi1')),
            'deskripsi2' => clean_html($this->request->getPost('deskripsi2')),
            'deskripsi3' => clean_html($this->request->getPost('deskripsi3')),
            'gambar' => $namaGambar,
            'link' => $this->request->getPost('link') ?: null
        ]);

        return redirect()->to('/page/berita')->with('success', 'Berita berhasil diperbarui');
    }

    public function delete($id)
    {
        if ($redirect = $this->checkLogin())
            return $redirect;

        $berita = $this->beritaModel->find($id);
        if (!$berita) {
            return redirect()->to('/page/berita')->with('error', 'Berita tidak ditemukan');
        }

        // Hapus gambar terkait jika ada
        if ($berita['gambar'] && file_exists(FCPATH . 'uploads/berita/' . $berita['gambar'])) {
            unlink(FCPATH . 'uploads/berita/' . $berita['gambar']);
        }

        $this->beritaModel->delete($id);
        return redirect()->to('/page/berita')->with('success', 'Berita berhasil dihapus');
    }

    public function getAllWithFakultas()
    {
        return $this->beritaModel->select('berita.*, fakultas.nama_fakultas')
            ->join('fakultas', 'fakultas.id = berita.fakultas_id')
            ->orderBy('berita.created_at', 'DESC') // Gunakan created_at untuk konsistensi
            ->findAll();
    }
}