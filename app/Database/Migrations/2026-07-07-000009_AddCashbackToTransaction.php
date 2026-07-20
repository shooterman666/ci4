<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCashbackToTransaction extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transaction', [
            'cashback' => [
                'null' => false,
                'type' => 'DOUBLE',
                'default' => 0,
                'after' => 'diskon_kupon',
            ],
        ]);
    }

    public function down()
    {

        $this->forge->dropColumn('transaction', 'cashback');
    }
}
