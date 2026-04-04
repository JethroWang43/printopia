<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes for Index controller
$routes->get('/', 'Index::index');
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
$routes->get('essenovate_printopia', 'Index::index');
$routes->get('essenovate_printopia', 'Index::index');
$routes->get('essenovate_printopia/products', 'Products::index');
$routes->get('essenovate_printopia/products', 'Products::index');
$routes->get('essenovate_printopia/how-it-works', 'Howitwork::index');
$routes->get('essenovate_printopia/how-it-works', 'Howitwork::index');
$routes->get('essenovate_printopia/contact', 'Contact::index');
$routes->get('essenovate_printopia/contact', 'Contact::index');
$routes->get('essenovate_printopia/custom-order', 'Index::customOrder');
$routes->get('essenovate_printopia/custom-order', 'Index::customOrder');
$routes->get('essenovate_printopia/admin', 'Admin::index');
$routes->get('essenovate_printopia/admin', 'Admin::index');
$routes->get('essenovate_printopia/employee', 'Employee::index');
$routes->get('essenovate_printopia/employee', 'Employee::index');

$routes->get('essenovate_printopia/trello-task/index.html', 'Admin::trelloTaskIndex');
$routes->get('essenovate_printopia/trello-task/index.html', 'Admin::trelloTaskIndex');
$routes->get('essenovate_printopia/trello-task/lib/(:any)', 'Admin::trelloTaskLibAsset/$1');
$routes->get('essenovate_printopia/trello-task/lib/(:any)', 'Admin::trelloTaskLibAsset/$1');
$routes->get('essenovate_printopia/trello-task/(:any)', 'Admin::trelloTaskAsset/$1');
$routes->get('essenovate_printopia/trello-task/(:any)', 'Admin::trelloTaskAsset/$1');

// Routes for Users controller
$routes->get('users', 'Users::index');
$routes->get('users/add', 'Users::add');
$routes->post('users/insert', 'Users::insert');
$routes->get('users/view/(:num)', 'Users::view/$1');
$routes->get('users/edit/(:num)', 'Users::edit/$1');
$routes->post('users/update/(:num)', 'Users::update/$1');
$routes->get('users/delete/(:num)', 'Users::delete/$1');

//Cloudinary image gallery routes

// For subfolder access
// Add these to handle opening folders
$routes->get('gallery/index/(:any)', 'Gallery::index/$1');
$routes->post('essenovate_printopia/gallery/save_to_db', 'Gallery::save_to_db');
$routes->get('essenovate_printopia/gallery', 'Gallery::index');
$routes->get('essenovate_printopia/gallery/index/(:any)', 'Gallery::index/$1');
$routes->post('essenovate_printopia/gallery/upload', 'Gallery::upload');
$routes->get('essenovate_printopia/gallery/delete/(:any)', 'Gallery::delete/$1');
