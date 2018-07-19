<?php

namespace App\User\EventListener;

use App\Entity\Newsletter;
use App\Entity\User;
use App\User\UserEvent;
use App\User\UserEvents;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class UserSubscriber implements EventSubscriberInterface
{
    private $manager;

    /**
     * UserSubscriber constructor.
     *
     * @param $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
            UserEvents::USER_CREATED => 'onCreatedUser'
        ];
    }

    /**
     * @param \App\User\UserEvent $event
     */
    public function onCreatedUser(UserEvent $event)
    {
        /**
         * Quand un utilisateur s'incrit, on le rajoute automatiquement a la newsletter
         */
        $newsletter = new Newsletter();
        $newsletter->setEmail($event->getUser()->getEmail());
        $this->manager->persist($newsletter);
        $this->manager->flush();
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        #Mettre à jour notre date de dezrnière connexion
        if ($user instanceof User) {
            #Mise a jour et sauvegarde
            $user->setLastConnectionDate();
            $this->manager->flush();
        }
    }
}
