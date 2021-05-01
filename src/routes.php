<?php

use App\Controller\AdminController;
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
    [],
    [],
    null,
    [],
    'GET'
]));

$routes->add('login', new Route(
    '/login',
    [
    '_controller' => [UserController::class, 'login']],
    [],
    [],
    null,
    [],
    'POST'
));

$routes->add('show_login', new Route('/login', [
    '_controller' => [UserController::class, 'showLogin'],
    [],
    [],
    null,
    [],
    'GET'
]));


$routes->add('logout', new Route('/logout', [
    '_controller' => [UserController::class, 'logout'],
]));

$routes->add('index', new Route('/admin', [
    '_controller' => [AdminController::class, 'index'],
]));

$routes->add('show_post_comments', new Route('/admin/post/{id}/comments', [
    '_controller' => [AdminController::class, 'showCommentsByPost'],
    'id' => null
    ],
    [],
    [],
    null,
    [],
    'GET'
));

$routes->add('approve_post_comment', new Route('/admin/post/{idPost}/comment/{idComment}/approve', [
    '_controller' => [AdminController::class, 'approveComment'],
    'idPost' => null,
    'idComment' => null
    ],
));

$routes->add('edit_post', new Route('/admin/post/{id}/edit', [
    '_controller' => [AdminController::class, 'editPost'],
    'id' => null,
],
));

$routes->add('delete_post', new Route('/admin/post/{id}/delete', [
    '_controller' => [AdminController::class, 'deletePost'],
    'id' => null,
],
));

$routes->add('delete_comment', new Route('/admin/comment/{id}/delete', [
    '_controller' => [AdminController::class, 'deleteComment'],
    'id' => null,
],
));

$routes->add('report_comment', new Route('comment/{id}/report', [
    '_controller' => [UserController::class, 'reportComment'],
    'id' => null,
],
));

return $routes;
