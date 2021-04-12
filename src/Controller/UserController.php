<?php


namespace App\Controller;

use App\Repository\UserRepository;
use FireStorm\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
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

        if ($email !== null && $password !== null ) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $userRepository->createUser($email, $password);
        }
        return new Response($this->render('user/register.html.twig', [
            'email' => $email,
            'password' => $password
        ]));
    }
}
