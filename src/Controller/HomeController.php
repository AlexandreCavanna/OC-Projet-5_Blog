<?php
namespace App\Controller;

use App\Service\Mail;
use FireStorm\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
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
