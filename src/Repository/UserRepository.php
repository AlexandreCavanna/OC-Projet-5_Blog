<?php


namespace App\Repository;

use App\Entity\User;
use FireStorm\AbstractRepository;

class UserRepository extends AbstractRepository
{

private User $user;
    /**
     * @param $email
     * @param $password
     */
    public function createUser($email, $password)
    {
        $sql = 'INSERT INTO user(email, password) VALUES(:email, :password)';
        $fields = [
            'email' => $email,
            'password' => $password,
        ];

        $this->create($sql, $fields);
    }
}
