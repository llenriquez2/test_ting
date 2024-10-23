<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
 
// Protect student routes with the `auth` filter
// $routes->group('', ['filter' => 'Auth'], function($routes) {
// CRUD Routes
$routes->get('inventory', 'Inventory::index');
$routes->post('inventory/store', 'Inventory::store');
$routes->get('inventory/edit/(:num)', 'Inventory::edit/$1');
$routes->get('inventory/delete/(:num)', 'Inventory::delete/$1');
$routes->post('inventory/update', 'Inventory::update');
$routes->get('inventory/exportCSV', 'Inventory::exportCSV');
$routes->get('inventory/exportPDF', 'Inventory::exportPDF');


// });
service('auth')->routes($routes);
