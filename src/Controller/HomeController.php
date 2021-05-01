<?php
namespace App\Controller;

use App\Service\Mail;
use FireStorm\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

        return new Response($this->render('home/index.html.twig'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function mailer(Request $request): Response
    {
        $requestMethod = $request->server->get('REQUEST_METHOD');

        $fullname = $request->request->get('name');
        $email = $request->request->get('email');
        $message = nl2br($request->request->get('message'));

        $validFields = [];
        if ($requestMethod === "POST") {
            if (!empty($fullname) && !empty($email) && !empty($message)) {
                $mail = new Mail();
                $mail->sendMail();
                $this->session->getFlashBag()->add('success', 'Votre message à été envoyé.');
            }
        }

        $mailValues = $request->request->all();
        foreach ($mailValues as $key => $item) {
            if (!empty($item)) {
                $this->session->getFlashBag()->add('success' . ucfirst($key), 'Le champ' . ucfirst($key) . ' est valide !');

                if (empty($validFields[$key])) {
                    $validFields[$key] = $item;
                }
            } elseif (array_key_exists($key, $validFields) === false) {
                $this->session->getFlashBag()->add('error', 'Tout les champs n\'ont pas été remplis !');
                $this->session->getFlashBag()->add('error' . ucfirst($key), 'Le champ "' . ucfirst($key) . '" n\'a pas été remplis');
            }
        }

        $flashs = $this->session->getFlashBag()->all();

        if (isset($flashs['error'])) {
            $tab = array_unique($flashs['error']);
            $flashs['error'] = $tab;
        }

        return new Response($this->render('home/index.html.twig', [
            'flashs' => $flashs,
            'validFields' => $validFields
        ]));
    }
}
