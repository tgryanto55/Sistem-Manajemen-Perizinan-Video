<?php

namespace App\Services;

use App\Models\UserModel;

/**
 * Service Auth
 * 
 * Menangani semua urusan autentikasi (login, logout, cek user).
 * Memisahkan logic auth dari controller biar lebih rapi.
 */
class AuthService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Coba login user dengan email dan password.
     */
    public function attempt(string $email, string $password): bool
    {
        // Cari user berdasarkan email
        $user = $this->userModel->where('email', $email)->first();

        // Kalau user gak ketemu, gagal
        if (!$user) {
            return false;
        }

        // Verifikasi password (dicocokkan dengan hash di DB)
        if (password_verify($password, $user['password'])) {
            // Kalau cocok, simpan data penting ke session
            session()->set('user_id', $user['id']);
            session()->set('user_role', $user['role']);
            session()->set('user_name', $user['name']);
            return true;
        }

        // Password salah
        return false;
    }

    /**
     * Logout user dengan menghapus session.
     */
    public function logout()
    {
        session()->destroy();
    }

    /**
     * Ambil data user yang sedang login saat ini.
     * Return null kalau belum login.
     */
    public function user()
    {
        if (!session()->has('user_id')) {
            return null;
        }
        return $this->userModel->find(session()->get('user_id'));
    }

    /**
     * Cek apakah user sedang login.
     */
    public function check(): bool
    {
        return session()->has('user_id');
    }

    /**
     * Cek apakah user yang login adalah admin.
     */
    public function isAdmin(): bool
    {
        return session()->get('user_role') === 'admin';
    }

    /**
     * Cek apakah user yang login adalah customer.
     */
    public function isCustomer(): bool
    {
        return session()->get('user_role') === 'customer';
    }
}
