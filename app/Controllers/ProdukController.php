<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;

class ProdukController extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        helper('form');
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        return view('produk/index', [
            'products' => $this->productModel->findAll(),
        ]);
    }

    public function create()
    {
        $dataphoto = $this->request->getFile('foto');
        $dataForm = [
            'harga'  => $this->request->getPost('harga'),
            'nama'   => $this->request->getPost('nama'),
            'jumlah' => $this->request->getPost('jumlah'),
        ];
        if ($dataphoto && $dataphoto->isValid()) {
            $nama_file = $dataphoto->getRandomName();
            $dataphoto->move(FCPATH . 'img', $nama_file);
            $dataForm['foto'] = $nama_file;
        }
        $this->productModel->insert($dataForm);
        return redirect()->to(base_url('produk'))->with('success', 'Data Berhasil Ditambah');
    }

    public function edit($id)
    {
        $dataProduk = $this->productModel->find($id);

        $dataForm = [
            'harga'  => $this->request->getPost('harga'),
            'nama'   => $this->request->getPost('nama'),
            'jumlah' => $this->request->getPost('jumlah'),
        ];

        if ($this->request->getPost('check') == 1) {
            if (! empty($dataProduk['foto']) && file_exists(FCPATH . 'img/' . $dataProduk['foto'])) {
                unlink(FCPATH . 'img/' . $dataProduk['foto']);
            }


            $dataphoto = $this->request->getFile('foto');

            if ($dataphoto && $dataphoto->isValid()) {
                $nama_file = $dataphoto->getRandomName();
                $dataphoto->move(FCPATH . 'img', $nama_file);

                $dataForm['foto'] = $nama_file;
            }
        }

        $this->productModel->update($id, $dataForm);

        return redirect()->to(base_url('produk'))->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id)
    {
        $dataProduk = $this->productModel->find($id);

        if (! empty($dataProduk['foto']) && file_exists(FCPATH . 'img/' . $dataProduk['foto'])) {
            unlink(FCPATH . 'img/' . $dataProduk['foto']);
        }

        $this->productModel->delete($id);


        return redirect()->to(base_url('produk'))->with('success', 'Data Berhasil Dihapus');
    }

    public function download()
    {
        $products = $this->productModel->findAll();

        $html = view('produk/download_pdf', [
            'products' => $products,
        ]);

        $filename = date('Y-m-d-H-i-s') . '-produk.pdf';


        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, [
            'Attachment' => true,
        ]);
    }
}
