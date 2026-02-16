<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        // Create Admin
        if (!$userModel->where('email', 'admin@example.com')->first()) {
            $userModel->insert([
                'name'     => 'Admin User',
                'email'    => 'admin@example.com',
                'password' => 'password',
                'role'     => 'admin',
            ]);
        }

        // Create Customer
        if (!$userModel->where('email', 'customer@example.com')->first()) {
            $userModel->insert([
                'name'     => 'Customer User',
                'email'    => 'customer@example.com',
                'password' => 'password',
                'role'     => 'customer',
            ]);
        }
    }
}
