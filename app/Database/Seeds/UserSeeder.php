<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $userTable = $this->db->table('user');

        $existingAdmin = $userTable->where('username', 'fuji')->get()->getRowArray();

        $adminData = [
            'username'   => 'fuji',
            'email'      => 'fuji@example.com',
            'password'   => password_hash('1234567', PASSWORD_DEFAULT),
            'role'       => 'admin',
            'picture'    => 'NiceAdmin/assets/img/profile-img.jpg',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($existingAdmin) {
            $userTable->where('id', $existingAdmin['id'])->update($adminData);
        } else {
            $userTable->insert($adminData);
        }

        $currentTotal = $userTable->countAllResults();

        for ($i = $currentTotal; $i < 10; $i++) {
            $data = [
                'username'   => $faker->unique()->userName,
                'email'      => $faker->unique()->email,
                'password'   => password_hash('1234567', PASSWORD_DEFAULT),
                'role'       => $faker->randomElement(['admin', 'guest']),
                'picture'    => 'NiceAdmin/assets/img/profile-img.jpg',
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->table('user')->insert($data);
        }
    }
}
