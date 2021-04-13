<?php

use App\Controller\HomeController;
use App\Controller\PostController;
use App\Controller\UserController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection;

$routes->add('home', new Route('/', [
    '_controller' => [HomeController::class, 'index']
]));

$routes->add('mailer', new Route('/send-email', [
    '_controller' => [HomeController::class, 'mailer']
]));

$routes->add('posts', new Route('/posts', [
    '_controller' => [PostController::class, 'indexPosts']
]));

$routes->add('post', new Route('/post/{id}', [
    '_controller' => [PostController::class, 'showPost'],
    'id' => null
]));

$routes->add('register', new Route(
    '/register',
    ['_controller' => [UserController::class, 'register']],
    [],
    [],
    null,
    [],
    'POST'
));

$routes->add('show_register', new Route('/register', [
    '_controller' => [UserController::class, 'showRegister'],
]));

$routes->add('login', new Route('/login', [
    '_controller' => [UserController::class, 'login']],
    [],
    [],
    null,
    [],
    'POST'
));

$routes->add('show_login', new Route('/login', [
    '_controller' => [UserController::class, 'showLogin'],
]));


$routes->add('logout', new Route('/logout', [
    '_controller' => [UserController::class, 'logout'],
]));

return $routes;
