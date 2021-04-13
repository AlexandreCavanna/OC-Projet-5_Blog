<?php
namespace App\Controller;

use App\Service\Mail;
use FireStorm\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends AbstractController
{
    private Session $session;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    public function index(): Response
    {
        $session = new Session();
        $flashs = $session->getFlashBag()->get('success', []);

        return new Response($this->render('home/index.html.twig', [
            'flashs' => $flashs,
            ]));
    }

    public function mailer(): RedirectResponse
    {
        $mail = new Mail();
        $mail->sendMail();
        $this->session->getFlashBag()->add('success', 'Votre message à été envoyé.');
        return new RedirectResponse('/');
    }
}
