<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/** @var ContainerBuilder $container */
$container = include '../src/Config/container.php';

$container->setParameter('routes', include '../src/routes.php');

$session = new Session();
$session->start();

$request = Request::createFromGlobals();

try {
    $response = $container->get('framework')->handle($request);
} catch (ServiceNotFoundException | ParameterNotFoundException $e ) {
    echo $e->getMessage();
}

$response->send();
