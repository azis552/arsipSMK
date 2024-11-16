<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'UsersController::login');
$routes->get('/users', 'UsersController::index');
$routes->get('/users/create', 'UsersController::create');
$routes->post('users/store', 'UsersController::store');
$routes->get('/users/edit/(:num)', 'UsersController::edit/$1');
$routes->post('/users/update/(:num)', 'UsersController::update/$1');
$routes->get('/users/delete/(:num)', 'UsersController::delete/$1');
$routes->post('users/loginCheck', 'UsersController::loginCheck');
$routes->get('/logout', 'UsersController::logout');

$routes->get('/suratmasuk', 'SuratMasuk::index');
$routes->get('/suratmasuk/create', 'SuratMasuk::create');
$routes->post('suratmasuk/store', 'SuratMasuk::store');
$routes->post('suratmasuk/uploadfile', 'SuratMasuk::uploadfile');
$routes->get('/suratmasuk/edit/(:num)', 'SuratMasuk::edit/$1');
$routes->post('/suratmasuk/update/(:num)', 'SuratMasuk::update/$1');
$routes->get('/suratmasuk/delete/(:num)', 'SuratMasuk::delete/$1');
$routes->get('/suratmasuk/signature/(:num)', 'SuratMasuk::signature/$1');
$routes->get('/suratmasuk/framesignature/(:num)', 'SuratMasuk::framesignature/$1');


$routes->post('/surat/save-coordinates', 'SuratMasuk::saveCoordinates');

$routes->get('/dashboard', 'Home::index');

