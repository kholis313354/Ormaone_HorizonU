<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel;
    }

    public function index() {
         // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'User';
        $data = $this->userModel->findAll();

        return view('page/user/index', compact('title', 'data'));
    }

    public function create() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Create User';

        return view('page/user/create', compact('title'));
    }

    public function store() {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->userModel->insert($data);

        return redirect()->to(url_to('user.index'))->with('success', 'User created successfully');
    }

    public function edit($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $title = 'Edit User';
        $data = $this->userModel->find($id);

        return view('page/user/edit', compact('title', 'data'));
    }

    public function update($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $data = $this->request->getPost();
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }

        $this->userModel->update($id, $data);

        return redirect()->to(url_to('user.index'))->with('success', 'User updated successfully');
    }

    public function delete($id) {
        // Check if the user is logged in
        if (!session()->get('isLoggedIn')) return redirect()->to(url_to('login'));
        $this->userModel->delete($id);

        return redirect()->to(url_to('user.index'))->with('success', 'User deleted successfully');
    }
}
