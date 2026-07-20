<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKuponToTransaction extends Migration
{
    public function up()
    {
        $this->forge->addColumn('transaction', [
            'kode_kupon' => [
                'null' => true,
                'type' => 'VARCHAR',
                'constraint' => 50,
                'after' => 'biaya_admin',
            ],
            'diskon_kupon' => [
                'type' => 'DOUBLE',
                'null' => false,
                'default' => 0,
                'after' => 'kode_kupon',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', ['diskon_kupon', 'kode_kupon']);
    }
}
