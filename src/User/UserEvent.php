<?php

namespace App\User;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event
{
    private $user;

    /**
     * UserEvent constructor.
     *
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \App\Entity\User
     */
    public function getUser(): \App\Entity\User
    {
        return $this->user;
    }
}
