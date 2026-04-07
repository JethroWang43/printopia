<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes for Index controller
$routes->get('/', 'Index::index');
$routes->get('dashboard', 'Index::index');
$routes->get('products', 'Products::index');
$routes->get('how-it-works', 'Howitwork::index');
$routes->get('contact', 'Contact::index');
$routes->get('custom-order', 'Index::customOrder');
$routes->get('admin', 'Admin::index');
$routes->get('employee', 'Employee::index');

// Trello task management embedded app
$routes->get('trello-task/index.html', 'Admin::trelloTaskIndex');
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


// SIGNUP, LOGIN, LOGOUT
$routes->get('/signup', 'Auth::showSignupForm');
$routes->post('/auth/signup', 'Auth::signup');

$routes->get('/login', 'Auth::showLoginForm');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');


// FOR TEST
$routes->get('/test', 'Test::index');