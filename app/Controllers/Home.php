<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    public function __construct()
    {
        helper(['number', 'form']);
    }

    public function index(): string
    {
        $productModel = new ProductModel();

        return view('v_home', [
            'products' => $productModel->findAll(),
        ]);
    }
}
