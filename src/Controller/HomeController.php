<?php
namespace App\Controller;

use App\Config\Container;
use App\Service\Mail;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function __construct()
    {
        parent::__construct(new Container(new ContainerBuilder()));
    }

    public function index(): Response
    {
        return new Response($this->render('home/index.html.twig'));
    }

    public function mailer(): RedirectResponse
    {
        $mail = new Mail();
        $mail->sendMail();
        return new RedirectResponse('/');
    }
}
