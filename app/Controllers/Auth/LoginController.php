<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\AuthService;

/**
 * Controller untuk menangani proses login user.
 */
class LoginController extends BaseController
{
    protected $authService;

    public function __construct()
    {
        // Inisialisasi AuthService untuk handle logika autentikasi
        $this->authService = new AuthService();
    }

    /**
     * Tampilkan halaman login.
     */
    public function index()
    {
        return view('auth/login');
    }

    /**
     * Proses submit form login.
     */
    public function login()
    {
        // Aturan validasi input
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        // Cek validasi
        if (!$this->validate($rules)) {
            // Jika gagal, kembalikan ke halaman login dengan pesan error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data input
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Coba login pakai AuthService
        if ($this->authService->attempt($email, $password)) {
            // Jika berhasil, arahkan ke dashboard sesuai role
            return $this->redirectBasedOnRole();
        }

        // Jika gagal login (email/password salah)
        return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
    }

    /**
     * Helper untuk mengarahkan user setelah login berdasarkan rolenya.
     */
    protected function redirectBasedOnRole()
    {
        // Jika admin, ke dashboard admin
        if ($this->authService->isAdmin()) {
            return redirect()->to('/admin/dashboard');
        }
        // Jika customer, ke dashboard customer
        return redirect()->to('/customer/dashboard');
    }
}
