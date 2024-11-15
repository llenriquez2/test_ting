<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// $routes->get('/', 'Home::index');
$routes->get('/', 'Inventory::index');
 
// Protect student routes with the `auth` filter
// $routes->group('', ['filter' => 'Auth'], function($routes) {
// CRUD Routes
$routes->get('inventory', 'Inventory::index');
$routes->post('inventory/store', 'Inventory::store');
// $routes->post('inventory/store/(:num)', 'Inventory::store/$1');


$routes->get('inventory/edit/(:num)', 'Inventory::edit/$1');
$routes->post('inventory/delete/(:num)', 'Inventory::delete/$1');
$routes->post('inventory/update', 'Inventory::update');
$routes->get('inventory/exportCSV', 'Inventory::exportCSV');
// $routes->get('inventory/exportPDF', 'Inventory::exportPDF');
// $routes->get('inventory/exportPDF/(:num)', 'Inventory::exportPDF/$1');
$routes->get('inventory/exportPDF/(:num)/(:any)', 'Inventory::exportPDF/$1/$2');

// $routes->get('inventory/getChildSerials/(:num)', 'Inventory::getChildSerials/$1');

$routes->get('inventory/getChildSerialNumbers', 'Inventory::getChildSerialNumbers');


// $routes->get('features', 'Inventory::index');


// });
service('auth')->routes($routes);
