<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection;

$routes->add('home', new Route('/', [
    '_controller' => 'App\Controller\HomeController::index',
]));

return $routes;
