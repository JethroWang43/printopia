<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes for Index controller
$routes->get('/', 'Index::index');
$routes->get('dashboard', 'Index::index');
$routes->get('printopia/dashboard', 'Index::index');
$routes->get('products', 'Products::index');
$routes->get('how-it-works', 'Howitwork::index');
$routes->get('contact', 'Contact::index');
$routes->get('custom-order', 'Index::customOrder');

$routes->get('admin', 'Admin::index');
$routes->get('employee', 'Employee::index');
$routes->get('admin/gallery/files', 'Gallery::files');
$routes->get('admin/gallery/open/(:num)', 'Gallery::open/$1');
$routes->post('admin/gallery/save_to_db', 'Gallery::save_to_db');
$routes->post('admin/gallery/delete/(:num)', 'Gallery::delete/$1');
$routes->get('admin/inventory/list', 'Inventory::list');
$routes->post('admin/inventory/save', 'Inventory::save');
$routes->post('admin/inventory/delete/(:num)', 'Inventory::delete/$1');
$routes->get('admin/account/list', 'Account::list');
$routes->post('admin/account/save', 'Account::save');
$routes->post('admin/account/delete/(:num)', 'Account::delete/$1');
$routes->get('admin/discount/list', 'Discount::list');
$routes->get('admin/discount/users', 'Discount::users');
$routes->post('admin/discount/save', 'Discount::save');
$routes->post('admin/discount/delete/(:num)', 'Discount::delete/$1');

// Trello task management embedded app
$routes->get('trello-task/index.html', 'Admin::trelloTaskIndex');
$routes->post('trello-task/proxy', 'Admin::trelloTaskProxy');
$routes->get('trello-task/lib/(:any)', 'Admin::trelloTaskLibAsset/$1');
$routes->get('trello-task/(:any)', 'Admin::trelloTaskAsset/$1');

// Support prefixed paths when app is accessed via /Printopia
$routes->get('printopia', 'Index::index');
$routes->get('Printopia', 'Index::index');
$routes->get('printopia/products', 'Products::index');
$routes->get('Printopia/products', 'Products::index');
$routes->get('printopia/how-it-works', 'Howitwork::index');
$routes->get('Printopia/how-it-works', 'Howitwork::index');
$routes->get('printopia/contact', 'Contact::index');
$routes->get('Printopia/contact', 'Contact::index');
$routes->get('printopia/custom-order', 'Index::customOrder');
$routes->get('Printopia/custom-order', 'Index::customOrder');
$routes->get('printopia/admin', 'Admin::index');
$routes->get('Printopia/admin', 'Admin::index');
$routes->get('printopia/employee', 'Employee::index');
$routes->get('Printopia/employee', 'Employee::index');
// $routes->get('printopia/admin/gallery/files', 'Admin::galleryFiles');
// $routes->get('Printopia/admin/gallery/files', 'Admin::galleryFiles');
// $routes->get('printopia/admin/gallery/file/(:any)', 'Admin::galleryFile/$1');
// $routes->get('Printopia/admin/gallery/file/(:any)', 'Admin::galleryFile/$1');

// Gallery Group
$routes->group('printopia/admin/gallery', function($routes) {
    $routes->get('files', 'Gallery::files');
    $routes->get('open/(:num)', 'Gallery::open/$1');
    $routes->post('save_to_db', 'Gallery::save_to_db');
    // Change this to post
    $routes->post('delete/(:num)', 'Gallery::delete/$1'); 
});

$routes->group('printopia/admin/inventory', function($routes) {
    $routes->get('list', 'Inventory::list');
    $routes->post('save', 'Inventory::save');
    $routes->post('delete/(:num)', 'Inventory::delete/$1');
});

$routes->group('printopia/admin/account', function($routes) {
    $routes->get('list', 'Account::list');
    $routes->post('save', 'Account::save');
    $routes->post('delete/(:num)', 'Account::delete/$1');
});

$routes->group('printopia/admin/discount', function($routes) {
    $routes->get('list', 'Discount::list');
    $routes->get('users', 'Discount::users');
    $routes->post('save', 'Discount::save');
    $routes->post('delete/(:num)', 'Discount::delete/$1');
});

$routes->group('Printopia/admin/inventory', function($routes) {
    $routes->get('list', 'Inventory::list');
    $routes->post('save', 'Inventory::save');
    $routes->post('delete/(:num)', 'Inventory::delete/$1');
});

$routes->group('Printopia/admin/account', function($routes) {
    $routes->get('list', 'Account::list');
    $routes->post('save', 'Account::save');
    $routes->post('delete/(:num)', 'Account::delete/$1');
});

$routes->group('Printopia/admin/discount', function($routes) {
    $routes->get('list', 'Discount::list');
    $routes->get('users', 'Discount::users');
    $routes->post('save', 'Discount::save');
    $routes->post('delete/(:num)', 'Discount::delete/$1');
});

$routes->get('printopia/trello-task/index.html', 'Admin::trelloTaskIndex');
$routes->get('Printopia/trello-task/index.html', 'Admin::trelloTaskIndex');
$routes->get('printopia/trello-task/lib/(:any)', 'Admin::trelloTaskLibAsset/$1');
$routes->get('Printopia/trello-task/lib/(:any)', 'Admin::trelloTaskLibAsset/$1');
$routes->get('printopia/trello-task/(:any)', 'Admin::trelloTaskAsset/$1');
$routes->get('Printopia/trello-task/(:any)', 'Admin::trelloTaskAsset/$1');

// Routes for Users controller
$routes->get('users', 'Users::index');
$routes->get('users/add', 'Users::add');
$routes->post('users/insert', 'Users::insert');
$routes->get('users/view/(:num)', 'Users::view/$1');
$routes->get('users/edit/(:num)', 'Users::edit/$1');
$routes->post('users/update/(:num)', 'Users::update/$1');
$routes->get('users/delete/(:num)', 'Users::delete/$1');

// Routes for Customer controller
$routes->group('customer', function($routes) {
    $routes->get('/', 'Customer::index'); // This makes /customer work
    $routes->get('dashboard', 'Customer::index'); // This makes /customer/dashboard work
});
$routes->get('customer/my-designs', 'Designs::index');
$routes->post('designs/upload', 'Designs::upload');
$routes->post('designs/delete/(:num)', 'Designs::delete/$1');

// SIGNUP, LOGIN, LOGOUT
// Web pages
$routes->get('/signup', 'Api\AuthController::showSignupForm');
$routes->get('/login', 'Api\AuthController::showLoginform');
$routes->get('/printopia/login', 'Api\AuthController::showLoginform');


// API endpoints
$routes->post('api/auth/register', 'Api\AuthController::register');
$routes->post('api/auth/login', 'Api\AuthController::login');


// FOR TEST
$routes->get('/test', 'Test::index');
$routes->get('/hash', 'Test::hashing');