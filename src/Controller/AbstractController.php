<?php
namespace App\Controller;

use App\Config\Container;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class AbstractController
{
    /**
     * @var Container
     */
    protected Container $container;


    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    public function render(string $view, $params = []): string
    {
       $twig = $this->container->build()->get('twig');
       $twig->addExtension(new DebugExtension());
       $twig->addGlobal('session', $_SESSION);

        try {
            return $twig->render($view, $params);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return $e->getMessage();
        }
    }
}
