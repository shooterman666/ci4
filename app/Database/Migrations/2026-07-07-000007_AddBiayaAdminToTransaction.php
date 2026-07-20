<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBiayaAdminToTransaction extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transaction', [
            'biaya_admin' => [
                'null' => false,
                'type' => 'DOUBLE',
                'default' => 0,
                'after' => 'ongkir',
            ],
        ]);
    }

    public function down()
    {

        $this->forge->dropColumn('transaction', 'biaya_admin');
    }
}
