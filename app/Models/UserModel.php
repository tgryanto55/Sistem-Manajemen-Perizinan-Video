<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model User
 * Mengelola tabel 'users' dan otomatis hashing password.
 */
class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Set true kalau mau soft delete (data gak ilang dari DB)
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'role'];

    protected bool $allowEmptyInserts = false;

    // Konfigurasi Timestamp
    protected $useTimestamps = true; // Otomatis isi created_at dan updated_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks Model
    protected $allowCallbacks = true;
    // Callback ini jalan sebelum insert/update buat hash password
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Fungsi Callback buat hash password sebelum masuk database.
     * Biar password gak tersimpan sebagai plain text.
     */
    protected function hashPassword(array $data)
    {
        // Cek kalau ada data password yang dikirim
        if (isset($data['data']['password'])) {
            // Hash password pake algoritma default (Bcrypt)
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }
}
