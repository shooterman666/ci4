<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        return view('v_profil', [
            'user' => $userModel->find(session('user_id')),
        ]);
    }
}
