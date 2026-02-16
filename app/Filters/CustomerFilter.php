<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * CustomerFilter
 * 
 * Secure filter that only allows users with the 'customer' role.
 * Similar to AdminFilter but tailored for customer-only areas.
 */
class CustomerFilter implements FilterInterface
{
    /**
     * Pre-checks the request for customer permissions.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Must be logged in.
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }

        // 2. Must specifically have the 'customer' role.
        if (session()->get('user_role') !== 'customer') {
            // Throwing a 404 is a security best practice to avoid 
            // leaking route info to unauthorized users.
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed.
    }
}
