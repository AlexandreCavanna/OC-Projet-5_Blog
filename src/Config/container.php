<?php

namespace App\Config;

use FireStorm\Framework;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$containerBuilder = new ContainerBuilder();

$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('services.yml');

$containerBuilder->register('context', Routing\RequestContext::class);

$containerBuilder->register('matcher', Routing\Matcher\UrlMatcher::class)
    ->setArguments(['%routes%', new Reference('context')])
;

$containerBuilder->register('request_stack', HttpFoundation\RequestStack::class);
$containerBuilder->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class);
$containerBuilder->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

$containerBuilder->register('listener.router', HttpKernel\EventListener\RouterListener::class)
    ->setArguments([new Reference('matcher'), new Reference('request_stack')])
;

$containerBuilder->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
    ->setArguments(['UTF-8'])
;

$containerBuilder->register('listener.exception', HttpKernel\EventListener\ErrorListener::class)
    ->setArguments(['App\Controller\ErrorController::exception'])
;

$containerBuilder->register('dispatcher', EventDispatcher::class)
    ->addMethodCall('addSubscriber', [new Reference('listener.router')])
    ->addMethodCall('addSubscriber', [new Reference('listener.response')])
    ->addMethodCall('addSubscriber', [new Reference('listener.exception')])
;

$containerBuilder->register('framework', Framework::class)
    ->setArguments([
        new Reference('dispatcher'),
        new Reference('controller_resolver'),
        new Reference('request_stack'),
        new Reference('argument_resolver'),
    ])
;

$containerBuilder->register('loader', FilesystemLoader::class)
    ->setArguments(['../views/'])
;

$containerBuilder->register('twig', Environment::class)
    ->setArguments([new Reference('loader'), [
        'cache' => '../cache/twig',
        'auto_reload' => true
    ]])
;

return $containerBuilder;
