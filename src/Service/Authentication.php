<?php


namespace App\Service;


use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Authentication
{
    private Session $session;

    /**
     * Authentication constructor.
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * @param \App\Entity\User $user
     */
    public function setSession(User $user)
    {
        $this->session->set("id", $user->getId());
        $this->session->set("email", $user->getEmail());
    }
}
