<?php


namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\Authentication;
use FireStorm\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends AbstractController
{
    private Session $session;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }


    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function register(Request $request): Response
    {
        $userRepository = new UserRepository();
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if ($email !== null && $password !== null && empty($userRepository->findEmail($email))) {
            $userRepository->createUser($email, password_hash($password, PASSWORD_BCRYPT));
            $this->session->getFlashBag()->add('success', 'Votre inscription a bien été effectué !');
            return new RedirectResponse("/");
        }

        $this->session->getFlashBag()->add('error', 'Cette email a déjà été utilisé !');
        $flashs = $this->session->getFlashBag()->all();

        return new Response($this->render('user/register.html.twig', [
            'email' => $email,
            'password' => $password,
            'flashs' => $flashs
        ]));
    }

    public function showRegister(): Response
    {
        if ($this->session->get('email') !== null)
        {
            return new RedirectResponse('/');
        }
        return new Response($this->render('user/register.html.twig'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $userRepository = new UserRepository();
        $authentication = new Authentication();

        if (
            $email !== null &&
            $password !== null &&
            password_verify($password, $userRepository->findEmail($email)->getPassword())
        ) {
            $authentication->setSession($userRepository->findEmail($email));
            $this->session->getFlashBag()->add('success', 'Vous êtes maintenant connecté !');
        }

        return new RedirectResponse('/');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showLogin(): Response
    {
        if ($this->session->get('email') !== null)
        {
            return new RedirectResponse('/');
        }
        return new Response($this->render('user/login.html.twig'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->session->clear();
        $this->session->getFlashBag()->add('success', 'Vous avez été déconnecté avec succès !');
        return new RedirectResponse('/');
    }
}
