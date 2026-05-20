<?php

namespace App\Controllers;

use App\Controllers\BaseController;
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
            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');



            $dataUser = [
                'username' => 'fuji', 
                'password' => 'e206a54e97690cce50cc872dd70ee896', 
                'role' => 'admin',
                'email' => 'htop@github.com',
                'picture' => 'https://upload.wikimedia.org/wikipedia/commons/a/a1/Bundesarchiv_Bild_146-2006-0122%2C_Hans-Joachim_Marseille.jpg'
            ]; 

            if ($username == $dataUser['username']) {
                if (md5($password) == $dataUser['password']) {
                    session()->set([
                        'username' => $dataUser['username'],
                        'role' => $dataUser['role'],
                        'email' => $dataUser['email'],
                        'picture' => $dataUser['picture'],
                        'isLoggedIn' => TRUE,
                        'time_when_login' => time()

                    ]);


                    return redirect()->to(base_url('/'));
                } else {
                    
                    session()->setFlashdata('failed', 'Username & Password Salah');
                    return redirect()->back();
                }
            } else {
                session()->setFlashdata('failed', 'Username Tidak Ditemukan');
                return redirect()->back();
            }
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

