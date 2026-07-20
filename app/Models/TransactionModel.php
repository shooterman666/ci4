<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'username',
        'total_harga',
        'alamat',
        'ongkir',
        'biaya_admin',
        'kupon_code',
        'diskon_kupon',
        'cashback',
        'status',
        'created_at',
        'updated_at',
    ];
}
