<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPictureToUser extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user', [
            'picture' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'role',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('user', 'picture');
    }
}
