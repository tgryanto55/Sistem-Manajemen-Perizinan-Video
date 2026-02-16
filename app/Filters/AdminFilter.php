<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AdminFilter
 * 
 * Secure filter that only allows users with the 'admin' role.
 * It first checks if the user is logged in, then verifies their role.
 */
class AdminFilter implements FilterInterface
{
    /**
     * Pre-checks the request for admin permissions.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Check if user is logged in.
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }

        // 2. Verify that the logged-in user actually has admin rights.
        if (session()->get('user_role') !== 'admin') {
            // If not an admin, we throw a 404 to hide the existence 
            // of the admin route from unauthorized users.
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed for this filter.
    }
}
