<?php
namespace App\Controller;

use App\Service\Mail;
use FireStorm\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private Session $session;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(): Response
    {
        $session = new Session();
        $flashs = $session->getFlashBag()->get('success', []);

        return new Response($this->render('home/index.html.twig', [
            'flashs' => $flashs,
            ]));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function mailer(): RedirectResponse
    {
        $mail = new Mail();
        $mail->sendMail();
        $this->session->getFlashBag()->add('success', 'Votre message à été envoyé.');
        return new RedirectResponse('/');
    }
}
