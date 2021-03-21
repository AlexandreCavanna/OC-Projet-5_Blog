<?php
namespace App\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class AbstractController
{
    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function load(string $view, $params = []): string
    {
        $loader = new FilesystemLoader('../src/views');
        $twig = new Environment($loader, [
            'cache' => '../cache/twig',
            'auto_reload' => true
        ]);

        try {
            return $twig->render($view, $params);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return $e->getMessage();
        }
    }
}
