<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection;

$routes->add('hello', new Route('/hello/{name}', [
    '_controller' => 'App\Controller\HomeController::index'
]));

return $routes;
