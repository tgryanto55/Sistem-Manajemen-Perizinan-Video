<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter khusus Customer
 * Menjaga route agar hanya bisa diakses oleh user dengan role 'customer'.
 */
class CustomerFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek User Login
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }

        // 2. Cek apakah role user adalah 'customer'
        if (session()->get('user_role') !== 'customer') {
            // Jika bukan customer, lempar 404 (Security through obscurity)
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada proses setelah request
    }
}
