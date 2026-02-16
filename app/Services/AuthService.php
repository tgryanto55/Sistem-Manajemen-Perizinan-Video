<?php

namespace App\Services;

use App\Models\UserModel;

/**
 * AuthService
 * 
 * This service handles all authentication-related tasks, including login, 
 * logout, and checking user roles from the session.
 * It abstracts the session management and model interactions.
 */
class AuthService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Attempts to log in a user with email and password.
     * 
     * @param string $email
     * @param string $password
     * @return bool True if successful, false otherwise.
     */
    public function attempt(string $email, string $password): bool
    {
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return false;
        }

        // Verify the provided password against the hashed password in the DB.
        if (password_verify($password, $user['password'])) {
            // Store essential user data in the session for global access.
            session()->set('user_id', $user['id']);
            session()->set('user_role', $user['role']);
            session()->set('user_name', $user['name']);
            return true;
        }

        return false;
    }

    /**
     * Logs out the current user by destroying the entire session.
     */
    public function logout()
    {
        session()->destroy();
    }

    /**
     * Retrieves the data of the currently logged-in user.
     * 
     * @return array|null User data array or null if not logged in.
     */
    public function user()
    {
        if (!session()->has('user_id')) {
            return null;
        }
        return $this->userModel->find(session()->get('user_id'));
    }

    /**
     * Checks if a user is currently logged in.
     */
    public function check(): bool
    {
        return session()->has('user_id');
    }

    /**
     * Checks if the logged-in user has the 'admin' role.
     */
    public function isAdmin(): bool
    {
        return session()->get('user_role') === 'admin';
    }

    /**
     * Checks if the logged-in user has the 'customer' role.
     */
    public function isCustomer(): bool
    {
        return session()->get('user_role') === 'customer';
    }
}
