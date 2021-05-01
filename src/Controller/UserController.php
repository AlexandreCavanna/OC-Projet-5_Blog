<?php


namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Service\Authentication;
use FireStorm\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private Session $session;
    /**
     * @var \App\Repository\CommentRepository
     */
    private CommentRepository $commentRepository;
    /**
     * @var \App\Repository\UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var \App\Service\Authentication
     */
    private Authentication $authentication;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->commentRepository = new CommentRepository();
        $this->authentication = new Authentication();
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showRegister(): Response
    {
        if ($this->session->get('email') !== null) {
            return new RedirectResponse('/');
        }
        return new Response($this->render('user/register.html.twig'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function login(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if (
            $email !== null &&
            $password !== null &&
            password_verify($password, $this->userRepository->findEmail($email)->getPassword())
        ) {
            $this->authentication->setSession($this->userRepository->findEmail($email));
            $this->session->getFlashBag()->add('success', 'Vous êtes maintenant connecté !');
        }
        $flashs = $this->session->getFlashBag()->all();
        return new Response($this->render('home/index.html.twig', [
            'flashs' => $flashs
        ]));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showLogin(): Response
    {
        if ($this->session->get('email') !== null) {
            return new RedirectResponse('/');
        }
        return new Response($this->render('user/login.html.twig'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function logout(): Response
    {
        $this->session->clear();
        $this->session->getFlashBag()->add('success', 'Vous avez été déconnecté avec succès !');
        $flashs = $this->session->getFlashBag()->all();
        return new Response($this->render('home/index.html.twig', [
            'flashs' => $flashs
        ]));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reportComment($id): RedirectResponse
    {
        $commentRepository = $this->commentRepository;
        $commentRepository->reportComment($id);
        $comment = $commentRepository->getComment($id);

        return new RedirectResponse('/post/'. $comment->getPostId());
    }
}
