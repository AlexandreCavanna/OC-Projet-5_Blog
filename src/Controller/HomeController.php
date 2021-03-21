<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        return new Response($this->load('home.html.twig'));
    }
}
