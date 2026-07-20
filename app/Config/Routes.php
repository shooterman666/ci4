<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('produk', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

$routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'TransaksiController::index');
    $routes->post('', 'TransaksiController::tambah_keranjang');
    $routes->post('edit', 'TransaksiController::edit_keranjang');
    $routes->get('delete/(:any)', 'TransaksiController::hapus_keranjang/$1');
    $routes->get('clear', 'TransaksiController::kosongkan_keranjang');
    $routes->get('checkout', 'TransaksiController::checkout');
    $routes->post('buy', 'TransaksiController::checkout_beli');
});

$routes->get('ajax/destinations', 'TransaksiController::get_destinations', ['filter' => 'auth']);
$routes->post('ajax/costs', 'TransaksiController::get_ongkir', ['filter' => 'auth']);
$routes->post('ajax/calculate', 'TransaksiController::hitung_total', ['filter' => 'auth']);

$routes->get('history', 'TransaksiController::riwayat', ['filter' => 'auth']);

$routes->resource('api/products', ['controller' => 'Api\ProdukController']);
$routes->get('api/transactions', 'Api\TransaksiController::index');

$routes->get('/profil', 'ProfileController::index', ['filter' => 'auth']);
