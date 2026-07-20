<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameKodeKuponToKuponCode extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('transaction', [
            'kode_kupon' => [
                'name' => 'kupon_code',
                'null' => true,
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('transaction', [
            'kupon_code' => [
                'name' => 'kode_kupon',
                'null' => true,
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);
    }
}
