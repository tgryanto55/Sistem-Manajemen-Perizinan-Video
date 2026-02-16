<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\AuthService;

class LoginController extends BaseController
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if ($this->authService->attempt($email, $password)) {
            return $this->redirectBasedOnRole();
        }

        return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
    }

    protected function redirectBasedOnRole()
    {
        if ($this->authService->isAdmin()) {
            return redirect()->to('/admin/dashboard');
        }
        return redirect()->to('/customer/dashboard');
    }
}
