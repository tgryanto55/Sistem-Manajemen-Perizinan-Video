<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 * 
 * Manages the 'users' table. 
 * Includes automatic password hashing via CodeIgniter 4 model callbacks.
 */
class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Set to true if you want to keep deleted users in DB
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'role'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true; // Automatically sets created_at and updated_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks
    protected $allowCallbacks = true;
    // These callbacks ensure that passwords are NEVER stored in plain text.
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Intercepts the data before it hits the database to hash the password.
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}
