<?php

namespace App\Newsletter;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class NewsletterSubscriber implements EventSubscriberInterface
{
    private $session;

    /**
     * NewsletterSubscriber constructor.
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     * Premier évènvement déclanché par SF avant d'arriver a un controller
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {

        #On s'assure que la requete vien de l'utilisateur et non de symfony
        if (!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        /**
         * On propose à l'utilisateur de s'incrire à la newsletter à partir de la troisieme page visité
         */
//        $session = $event->getRequest()->getSession();
        $this->session->set('countUserPages', $this->session->get('countUserPages', 0) + 1);

        #inviter l'utilisateur au bout de la troisieme page visitée
        if ($this->session->get('countUserPages') === 3) {
            $this->session->set('inviteUserModal', true);
        }
    }

    /**
     * Après la sortie du controller mais avant la fourniture de la réponse
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        #On s'assure que la requete vien de l'utilisateur et non de symfony
        if (!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        #on passe a false le invitUserModal
        if ($this->session->get('countUserPages') >= 3) {
            $this->session->set('inviteUserModal', false);
        }
    }
}
