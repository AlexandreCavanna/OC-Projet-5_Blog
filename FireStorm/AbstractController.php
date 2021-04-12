<?php
namespace FireStorm;

use Exception;
use Symfony\Bridge\Twig\Extension\DumpExtension;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

abstract class AbstractController
{
    /**
     * @param string $view
     * @param array $params
     * @var Environment $twig
     * @return string
     * @throws Exception
     */
    public function render(string $view, $params = []): string
    {
        $container = include __DIR__ . '/container.php';

       /* @var Environment $twig */
        $twig = $container->get('twig');
        $twig->addFunction(new TwigFunction('dump', fn ($value) => dump($value)));
        $twig->addGlobal('session', $_SESSION);

        try {
            return $twig->render($view, $params);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return $e->getMessage();
        }
    }
}
