<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionDetailModel;
use App\Models\TransactionModel;
use App\Services\RajaOngkirService;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        helper(['number', 'form', 'Transaksi']);
        $this->cart = service('cart');
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
    }

    public function index()
    {
        $data = [
            'items' => $this->cart->contents(),
            'total' => $this->cart->total(),
        ];

        return view('v_keranjang', $data);
    }

    public function tambah_keranjang()
    {
        $this->cart->insert([
            'id'      => $this->request->getPost('id'),
            'name'    => $this->request->getPost('nama'),
            'qty'     => 1,
            'price'   => $this->request->getPost('harga'),
            'options' => [
                'foto' => $this->request->getPost('foto'),
            ],
        ]);
        session()->setFlashdata(
            'success',
            'Produk berhasil ditambahkan ke keranjang. <a href="' . base_url('keranjang') . '">Lihat</a>'
        );
        return redirect()->to(base_url('/'));
    }

    public function edit_keranjang()
    {
        $i = 1;
        foreach ($this->cart->contents() as $item) {
            $jumlah = $this->request->getPost('qty' . $i++);

            $this->cart->update([
                'rowid' => $item['rowid'],
                'qty'   => $jumlah,
            ]);
        }


        session()->setFlashdata(
            'success',
            'Keranjang berhasil diperbarui'
        );

        return redirect()->to(base_url('keranjang'));
    }

    public function hapus_keranjang($rowid)
    {
        $this->cart->remove($rowid);


        session()->setFlashdata(
            'success',
            'Produk berhasil dihapus dari keranjang'
        );

        return redirect()->to(base_url('keranjang'));
    }

    public function kosongkan_keranjang()
    {
        $this->cart->destroy();

        session()->setFlashdata(
            'success',
            'Keranjang berhasil dikosongkan'
        );


        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        $data = [
            'total' => $this->cart->total(),
            'items' => $this->cart->contents(),
        ];

        return view('v_checkout', $data);
    }

    public function get_destinations()
    {
        $search = $this->request->getGet('q');
        $rajaOngkir = new RajaOngkirService();
        $response = $rajaOngkir->getDestination($search);
        $data = [];

        foreach (($response['data'] ?? []) as $destination) {
            $data[] = [
                'text' => $destination['label'],
                'id' => $destination['id'],
            ];
        }


        return $this->response->setJSON($data);
    }

    public function get_ongkir()
    {
        $destination = $this->request->getPost('destination');
        $rajaOngkir = new RajaOngkirService();
        $response = $rajaOngkir->getCost(64999, $destination, 1000, 'jne');
        $data = [];

        foreach (($response['data'] ?? []) as $cost) {
            $data[] = [
                'description' => $cost['description'],
                'service' => $cost['service'],
                'cost' => $cost['cost'],
                'etd' => $cost['etd'],
            ];
        }

        return $this->response->setJSON($data);
    }

    public function hitung_total()
    {
        $sub_total = (float) $this->request->getPost('subtotal');
        $kode_kupon = $this->request->getPost('kupon_code') ?? '';
        $ongkir = (float) $this->request->getPost('ongkir');

        $hasil_kupon = \hitung_diskon_kupon($sub_total, $kode_kupon);
        $biaya_admin = \hitung_biaya_admin($sub_total);
        $cashback = \hitung_cashback($sub_total);
        $grand_total = $sub_total - $hasil_kupon['diskon'] + $ongkir + $biaya_admin;

        return $this->response->setJSON([
            'diskon_kupon' => $hasil_kupon['diskon'],
            'kupon_persen' => $hasil_kupon['persen'],
            'kupon_code' => $hasil_kupon['kode'],
            'biaya_admin' => $biaya_admin,
            'cashback' => $cashback,
            'total' => $grand_total,
        ]);
    }

    public function riwayat()
    {
        $username = session()->get('username');

        $trx = $this->transactionModel->where('username', $username)->findAll();
        $trx_ids = array_column($trx, 'id');

        $produk = $this->transactionDetailModel->getProductsByTransactionIds($trx_ids);

        $data = [
            'username'      => $username,
            'products'      => $produk,
            'transactions'  => $trx
        ];

        return view('v_history', $data);
    }

    public function checkout_beli()
    {
        $model_trx = new TransactionModel();
        $model_detail_trx = new TransactionDetailModel();
        $ongkir = (int) $this->request->getPost('ongkir');

        $sub_total = $this->cart->total();

        $hasil_kupon = \hitung_diskon_kupon($sub_total, $this->request->getPost('kupon_code') ?? '');
        $diskon = $hasil_kupon['diskon'];
        $kode = $hasil_kupon['kode'];

        $biaya_admin = \hitung_biaya_admin($sub_total);
        $cashback = \hitung_cashback($sub_total);
        $grand_total = $sub_total - $diskon + $ongkir + $biaya_admin;

        $db = \Config\Database::connect();
        $db->transStart();

        $model_trx->insert([
            'username' => session()->get('username'),
            'total_harga' => $grand_total,
            'alamat' => $this->request->getPost('alamat'),
            'ongkir' => $ongkir,
            'biaya_admin' => $biaya_admin,
            'kupon_code' => $kode,
            'diskon_kupon' => $diskon,
            'cashback' => $cashback,
            'status' => 0,
        ]);

        $trx_id = $model_trx->getInsertID();


        foreach ($this->cart->contents() as $item) {
            $model_detail_trx->insert([
                'transaction_id' => $trx_id,
                'product_id' => $item['id'],
                'jumlah' => $item['qty'],
                'diskon' => 0,
                'subtotal_harga' => $item['subtotal'],
            ]);
        }

        $db->transComplete();
        $this->cart->destroy();

        session()->setFlashdata('success', 'Pesanan berhasil dibuat');

        return redirect()->to(base_url('keranjang'));
    }
}
