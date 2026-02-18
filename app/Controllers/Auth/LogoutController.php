<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\AuthService;

/**
 * Controller untuk menangani proses logout.
 */
class LogoutController extends BaseController
{
    protected $authService;

    public function __construct()
    {
        // Inisialisasi AuthService
        $this->authService = new AuthService();
    }

    /**
     * Proses logout user.
     */
    public function index()
    {
        // Hapus session/data login user
        $this->authService->logout();
        
        // Arahkan kembali ke halaman login
        return redirect()->to('/login');
    }
}
