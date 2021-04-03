<?php

require_once __DIR__ . '/../vendor/autoload.php';


use App\Config\Container;
use FireStorm\Framework;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

class Front
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        session_start();
        $this->container->build()->setParameter('routes', include __DIR__.'/../src/routes.php');
        $request = Request::createFromGlobals();
        $response = $this->container->build()->get(Framework::class)->handle($request);
        $response->send();
    }
}

$front = new Front(new Container(new ContainerBuilder()));
$front->run();
