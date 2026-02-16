<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes: Accessible by anyone, primarily for authentication.
$routes->get('/', 'Auth\LoginController::index');
$routes->get('login', 'Auth\LoginController::index');
$routes->post('login', 'Auth\LoginController::login');
$routes->get('logout', 'Auth\LogoutController::index');

// Admin Routes: Protected by 'admin' filter.
// These routes allow managers to control videos, customers, and access permissions.
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    
    // Customers Management
    $routes->get('customers', 'Admin\CustomerController::index');
    $routes->get('customers/rows', 'Admin\CustomerController::listRows'); // Used for HTMX polling/refresh
    $routes->post('customers', 'Admin\CustomerController::store');
    $routes->post('customers/update/(:num)', 'Admin\CustomerController::update/$1');
    $routes->get('customers/delete/(:num)', 'Admin\CustomerController::delete/$1');
    
    // Videos Management
    $routes->get('videos', 'Admin\VideoController::index');
    $routes->get('videos/rows', 'Admin\VideoController::listRows');
    $routes->post('videos', 'Admin\VideoController::store');
    $routes->post('videos/update/(:num)', 'Admin\VideoController::update/$1');
    $routes->get('videos/delete/(:num)', 'Admin\VideoController::delete/$1');
    
    // Video Access Request Handling
    $routes->get('requests', 'Admin\AccessRequestController::index');
    $routes->get('requests/rows', 'Admin\AccessRequestController::listRows');
    $routes->post('requests/approve/(:num)', 'Admin\AccessRequestController::approve/$1');
    $routes->post('requests/update/(:num)', 'Admin\AccessRequestController::update/$1');
    $routes->get('requests/delete/(:num)', 'Admin\AccessRequestController::delete/$1');
    $routes->post('requests/reject/(:num)', 'Admin\AccessRequestController::reject/$1');
});

// Customer Routes: Protected by 'customer' filter.
// These routes allow clients to see their available videos and watch them.
$routes->group('customer', ['filter' => 'customer'], function($routes) {
    $routes->get('dashboard', 'Customer\DashboardController::index');
    $routes->get('videos', 'Customer\VideoController::index');
    $routes->get('videos/rows', 'Customer\VideoController::listRows');
    $routes->post('videos/request/(:num)', 'Customer\VideoController::requestAccess/$1');
    $routes->get('videos/watch/(:num)', 'Customer\VideoController::watch/$1');
    $routes->get('videos/stream/(:num)', 'Customer\VideoController::stream/$1'); // Secure streaming endpoint
});
