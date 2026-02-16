<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter
 * 
 * Basic authentication filter.
 * Ensures that a user is logged in before allowing access to a route.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Checks if a user_id exists in the session.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login to continue.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed.
    }
}
