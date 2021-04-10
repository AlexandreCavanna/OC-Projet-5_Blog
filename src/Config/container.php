<?php

namespace App\Config;

use Exception;
use FireStorm\Framework;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Container
{
    private ContainerBuilder $containerBuilder;

    /**
     * Container constructor.
     * @param ContainerBuilder $containerBuilder
     */
    public function __construct(ContainerBuilder $containerBuilder)
    {
        $this->containerBuilder = $containerBuilder;
    }

    /**
     * @return ContainerBuilder
     */
    public function build(): ContainerBuilder
    {
        $loader = new YamlFileLoader($this->containerBuilder, new FileLocator(__DIR__));
        $loader->load('services.yml');

        $this->containerBuilder->register('context', Routing\RequestContext::class);
        $this->containerBuilder->register('matcher', Routing\Matcher\UrlMatcher::class)
            ->setArguments(['%routes%', new Reference('context')])
        ;
        $this->containerBuilder->register('request_stack', HttpFoundation\RequestStack::class);
        $this->containerBuilder->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class);
        $this->containerBuilder->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

        $this->containerBuilder->register('listener.router', HttpKernel\EventListener\RouterListener::class)
            ->setArguments([new Reference('matcher'), new Reference('request_stack')])
        ;
        $this->containerBuilder->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
            ->setArguments(['UTF-8'])
        ;

        $this->containerBuilder->register('dispatcher', EventDispatcher::class)
            ->addMethodCall('addSubscriber', [new Reference('listener.router')])
            ->addMethodCall('addSubscriber', [new Reference('listener.response')])
        ;
        $this->containerBuilder->register(Framework::class, Framework::class)
            ->setArguments([
                new Reference('dispatcher'),
                new Reference('controller_resolver'),
                new Reference('request_stack'),
                new Reference('argument_resolver'),
            ])
        ;

        $this->containerBuilder->register('loader', FilesystemLoader::class)
            ->setArguments(['../views/'])
        ;

        $this->containerBuilder->register('twig', Environment::class)
            ->setArguments([new Reference('loader'), [
                'cache' => '../cache/twig',
                'auto_reload' => true
            ]])
        ;


        return $this->getContainerBuilder();
    }

    /**
     * @return ContainerBuilder
     */
    private function getContainerBuilder(): ContainerBuilder
    {
        return $this->containerBuilder;
    }

}
