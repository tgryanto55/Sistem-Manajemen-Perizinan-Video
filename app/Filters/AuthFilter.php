<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter Autentikasi Dasar
 * Memastikan user sudah login sebelum mengakses route tertentu.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Cek apakah ada session user_id.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Jika tidak ada session 'user_id', berarti belum login
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada proses setelah request
    }
}
