<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    function __construct()
    {
        helper('form');
    }

    public function login()
    {
        if ($this->request->getPost()) {
            $rules = [
                'username' => [
                    'rules'  => 'required|min_length[6]',
                    'errors' => [
                        'required'   => 'Username harus diisi',
                        'min_length' => 'Username minimal 6 karakter',
                    ],
                ],
                'password' => [
                    'rules'  => 'required|min_length[7]|numeric',
                    'errors' => [
                        'required'   => 'Password harus diisi',
                        'min_length' => 'Password minimal 7 karakter',
                        'numeric'    => 'Password harus berupa angka',
                    ],
                ],
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $username  = $this->request->getVar('username');
            $password  = $this->request->getVar('password');
            $userModel = new UserModel();
            $dataUser  = $userModel->where('username', $username)->first();

            if (! $dataUser) {
                session()->setFlashdata('failed', 'Username tidak ditemukan');

                return redirect()->back()->withInput();
            }

            if (! password_verify($password, $dataUser['password'])) {
                session()->setFlashdata('failed', 'Username & password salah');

                return redirect()->back()->withInput();
            }

            session()->set([
                'user_id'         => $dataUser['id'],
                'username'        => $dataUser['username'],
                'role'            => $dataUser['role'],
                'email'           => $dataUser['email'],
                'picture'         => $dataUser['picture'] ?? 'NiceAdmin/assets/img/profile-img.jpg',
                'isLoggedIn'      => true,
                'time_when_login' => time(),
            ]);

            return redirect()->to(base_url('/'));
        } else {
            return view('v_login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
