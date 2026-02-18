<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- Public Routes ---
// Akses publik (login/logout)
$routes->get('/', 'Auth\LoginController::index');
$routes->get('login', 'Auth\LoginController::index');
$routes->post('login', 'Auth\LoginController::login');
$routes->get('logout', 'Auth\LogoutController::index');

// --- Admin Routes ---
// Protected by 'admin' filter.
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    
    // Manajemen Customer
    $routes->get('customers', 'Admin\CustomerController::index');
    $routes->get('customers/rows', 'Admin\CustomerController::listRows'); // Polling/refresh HTMX
    $routes->post('customers', 'Admin\CustomerController::store');
    $routes->post('customers/update/(:num)', 'Admin\CustomerController::update/$1');
    $routes->get('customers/delete/(:num)', 'Admin\CustomerController::delete/$1');
    
    // Manajemen Video
    $routes->get('videos', 'Admin\VideoController::index');
    $routes->get('videos/rows', 'Admin\VideoController::listRows');
    $routes->post('videos', 'Admin\VideoController::store');
    $routes->post('videos/update/(:num)', 'Admin\VideoController::update/$1');
    $routes->get('videos/delete/(:num)', 'Admin\VideoController::delete/$1');
    
    // Manajemen Request Akses
    $routes->get('requests', 'Admin\AccessRequestController::index');
    $routes->get('requests/rows', 'Admin\AccessRequestController::listRows');
    $routes->post('requests/approve/(:num)', 'Admin\AccessRequestController::approve/$1');
    $routes->post('requests/update/(:num)', 'Admin\AccessRequestController::update/$1');
    $routes->get('requests/delete/(:num)', 'Admin\AccessRequestController::delete/$1');
    $routes->post('requests/reject/(:num)', 'Admin\AccessRequestController::reject/$1');
});

// --- Customer Routes ---
// Protected by 'customer' filter.
$routes->group('customer', ['filter' => 'customer'], function($routes) {
    $routes->get('dashboard', 'Customer\DashboardController::index');
    $routes->get('videos', 'Customer\VideoController::index');
    $routes->get('videos/rows', 'Customer\VideoController::listRows');
    $routes->post('videos/request/(:num)', 'Customer\VideoController::requestAccess/$1');
    $routes->get('videos/watch/(:num)', 'Customer\VideoController::watch/$1');
    $routes->get('videos/stream/(:num)', 'Customer\VideoController::stream/$1'); // Endpoint streaming aman
});
