<?php
/**
 * Created by PhpStorm.
 * User: Etudiant0
 * Date: 29/06/2018
 * Time: 15:04
 */

namespace App\User;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface  $encoder)
    {
        $this->encoder = $encoder;
    }

    public function createFromUserRequest(UserRequest $request) :User
    {
        $user = new User();
        $user->setFirstname($request->getFirstname());
        $user->setLastname($request->getLastname());
        $user->setEmail($request->getEmail());
        $user->setRoles($request->getRoles());
        $user->setPassword($this->encoder->encodePassword($user, $request->getPassword()));
        return $user;
    }
}
