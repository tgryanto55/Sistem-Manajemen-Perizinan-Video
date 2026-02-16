<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\AuthService;

class LogoutController extends BaseController
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function index()
    {
        $this->authService->logout();
        return redirect()->to('/login');
    }
}
