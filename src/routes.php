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
    'id' => $id = null
]));

return $routes;
