<?php

use App\Config\Container;
use App\Controller\HomeController;
use App\Controller\PostController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$container = new Container(new ContainerBuilder());

$routes = new RouteCollection;

$routes->add('home', new Route('/', [
    '_controller' => [(new HomeController()), 'index']
]));

$routes->add('mailer', new Route('/send-email', [
    '_controller' => [(new HomeController()), 'mailer']
]));

$routes->add('posts', new Route('/posts', [
    '_controller' => [(new PostController()), 'indexPosts']
]));

$routes->add('post', new Route('/post/{id}', [
    '_controller' => [(new PostController()), 'showPost'],
    'id' => $id
]));

return $routes;
