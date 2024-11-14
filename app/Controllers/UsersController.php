<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Users;

class UsersController extends BaseController
{
    public function index()
    {
        $userModel = new Users();
        $users = $userModel->findAll();
        return view('users/index', ['users' => $users]);
    }

    public function login()
    {
        return view('users/login');
    }

    public function loginCheck()
    {
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new Users();
        $userModel = $userModel->where('email', $username)->first();
        if ($userModel && password_verify($password, $userModel['password'])) {
            $session = session();
            $session->set('user_id', $userModel['id']);
            $session->set('username', $userModel['name']);
            session()->setFlashdata('success', 'Login successful');
            return redirect()->to('/dashboard');
        }else{
            session()->setFlashdata('error', 'Invalid username or password');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        
        $validate = $this->validate([
            'name' => 'required',
            'email' => 'required|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role' => 'required',
        ]);
        if (!$validate) {
            return redirect()->to('/users/create')->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost();
        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $userModel = new Users();
        $simpan = $userModel->save($data);
        if ($simpan) {
            session()->setFlashdata('success', 'Data berhasil disimpan');
            return redirect()->to('/users');
        }else{
            session()->setFlashdata('error', 'Data gagal disimpan');
            return redirect()->back()->withInput();
        }
        
    }

    public function edit($id)
    {
        $userModel = new Users();
        $user = $userModel->find($id);
        return view('users/edit', ['user' => $user]);
    }

    public function update($id)
    {
        $validate = $this->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);
        if (!$validate) {
            return redirect()->to('/users/edit/'.$id)->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = $this->request->getPost();
        if($data['password'] != null){
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        $userModel = new Users();
        $userModel->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil diupdate');
        return redirect()->to('/users');
    }

    public function delete($id)
    {
        $userModel = new Users();
        $userModel->delete($id);
        session()->setFlashdata('success', 'Data berhasil dihapus');
        return redirect()->to('/users');
    }
}
