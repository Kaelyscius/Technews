<?php
/**
 * Created by PhpStorm.
 * User: Etudiant0
 * Date: 29/06/2018
 * Time: 15:04
 */

namespace App\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserRequestHandler
{
    private $em;

    private $userFactory;
    private $dispatcher;



    /**
     * ArticleRequestHandler constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface               $entityManager
     * @param \App\User\UserFactory                              $userFactory
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     *
     * @internal  param $em
     */
    public function __construct(EntityManagerInterface $entityManager, UserFactory $userFactory, EventDispatcherInterface $dispatcher)
    {
        $this->em = $entityManager;
        $this->userFactory = $userFactory;
        $this->dispatcher = $dispatcher;
    }

    public function handleAsUser(UserRequest $request) : User
    {

        #Appel a notre factory
        $user = $this->userFactory->createFromUserRequest($request);

        #insertion en BDD
        $this->em->persist($user);
        $this->em->flush();

        #on emet notre evenvement
        $this->dispatcher->dispatch(UserEvents::USER_CREATED, new UserEvent($user));

        return $user;
    }

    public function handleAsAuthor(UserRequest $request) : User
    {

        #Appel a notre factory
        $user = $this->userFactory->createFromUserRequest($request);

        #insertion en BDD
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function handleAsAdmin(UserRequest $request) : User
    {

        #Appel a notre factory
        $user = $this->userFactory->createFromUserRequest($request);

        #insertion en BDD
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
