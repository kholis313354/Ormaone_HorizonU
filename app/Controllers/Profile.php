<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function edit()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        $userId = session()->get('id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to(url_to('dashboard'));
        }

        $title = 'Profile Photo';
        return view('page/profile/edit', compact('title', 'user'));
    }

    public function update()
    {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(url_to('login'));
        }

        $userId = session()->get('id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->setFlashdata('error', 'User tidak ditemukan.');
            return redirect()->to(url_to('dashboard'));
        }

        // Validasi file upload
        $validation = \Config\Services::validation();
        $validation->setRules([
            'profile_photo' => [
                'rules' => 'uploaded[profile_photo]|max_size[profile_photo,2048]|mime_in[profile_photo,image/jpeg,image/png,image/gif]',
                'errors' => [
                    'uploaded' => 'Pilih foto profile terlebih dahulu',
                    'max_size' => 'Ukuran file maksimal 2MB',
                    'mime_in' => 'Format file harus JPG, PNG, atau GIF',
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $file = $this->request->getFile('profile_photo');

        if ($file->isValid() && !$file->hasMoved()) {
            // Hapus foto lama jika ada
            if (!empty($user['profile_photo']) && file_exists(FCPATH . 'uploads/profile/' . $user['profile_photo'])) {
                @unlink(FCPATH . 'uploads/profile/' . $user['profile_photo']);
            }

            // Pastikan direktori uploads/profile ada
            $uploadPath = FCPATH . 'uploads/profile';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Generate nama file unik
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            // Update database
            $this->userModel->update($userId, ['profile_photo' => $newName]);

            // Update session dengan foto baru
            $sessionData = session()->get();
            $sessionData['profile_photo'] = $newName;
            session()->set($sessionData);

            session()->setFlashdata('success', 'Foto profile berhasil diupdate.');
        } else {
            session()->setFlashdata('error', 'Gagal mengupload foto profile.');
        }

        return redirect()->to(url_to('profile.edit'));
    }
}

