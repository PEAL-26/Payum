<?php

namespace Config;

$routes = Services::routes();

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('/', 'HomeController::index');

$routes->group('api/v1', ['namespace' => 'App\Controllers\v1'], static function ($routes) {
    $routes->post('references', 'ReferenceCreateController::handle');
    $routes->post('references/cancel', 'ReferenceCancelController::handle');
    $routes->get('references', 'ReferenceQueryController::handle');
});

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
