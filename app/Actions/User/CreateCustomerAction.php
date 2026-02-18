<?php

namespace App\Actions\User;

use App\Models\UserModel;

/**
 * Handle pembuatan data customer baru.
 */
class CreateCustomerAction
{
    protected $userModel;

    public function __construct()
    {
        // Inisialisasi UserModel
        $this->userModel = new UserModel();
    }

    /**
     * Eksekusi simpan data customer.
     * Set role otomatis jadi 'customer' biar aman.
     */
    public function execute(array $data)
    {
        $data['role'] = 'customer'; 
        return $this->userModel->insert($data);
    }
}
