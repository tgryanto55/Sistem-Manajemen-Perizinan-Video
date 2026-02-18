<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter khusus Admin
 * Menjaga route agar hanya bisa diakses oleh user dengan role 'admin'.
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah user sudah login
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }

        // 2. Cek apakah role user adalah 'admin'
        if (session()->get('user_role') !== 'admin') {
            // Jika bukan admin, lempar 404 biar seolah-olah halaman gak ada (Security through obscurity)
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada proses setelah request
    }
}
