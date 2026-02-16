<?php

namespace App\Actions\User;

use App\Models\UserModel;

/**
 * CreateCustomerAction
 * 
 * This action handles the logic for creating a new customer user.
 * It ensures that the role is explicitly set to 'customer' before insertion.
 * Using an action class instead of direct model calls in the controller 
 * keeps the business logic centralized and reusable.
 */
class CreateCustomerAction
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Executes the creation of a customer.
     *
     * @param array $data An array containing user details (name, email, password).
     * @return int|bool Returns the inserted User ID on success, or false on failure.
     */
    public function execute(array $data)
    {
        // We force the role to 'customer' here to prevent any accidental 
        // creation of admin accounts through this specific action.
        $data['role'] = 'customer'; 
        return $this->userModel->insert($data);
    }
}
