<?php

namespace App\Service\Twig;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;

/**
 * Class AppExtention
 * Permet de créer des fonctions pour twig
 * @package App\Service\Twig
 */
class AppExtension extends AbstractExtension
{
    /**
     * variable privé pour avoir doctrine
     * @var $em
     */
    private $em;

    private $session;

    private $user;

    /**
     * AppExtention constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface                                                $manager
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface                          $session
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(
        EntityManagerInterface $manager,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage
    ) {
        #Doctrince
        $this->em = $manager;

        #Sessions
        $this->session = $session;

        #Récupération de l'auteur si un token existe
        if ($tokenStorage->getToken()) {
            $this->user = $tokenStorage->getToken()->getUser();
        }
    }


    public function getFunctions()
    {
        return array(
            new \Twig_Function('getCategories', function () {
                return $this->em->getRepository(Category::class)->findCategoriesHavingArticles();
            }),
            new \Twig_Function('isUserInvited', function () {
                return $this->session->get('inviteUserModal');
            }),
            new \Twig_Function('pendingArticles', function () {
                return $this->em->getRepository(Article::class)->countAuthorArticlesByStatus(
                    $this->user->getId(),
                    'review'
                );
            }),
            new \Twig_Function('publishedArticles', function () {
                return $this->em->getRepository(Article::class)->countAuthorArticlesByStatus(
                    $this->user->getId(),
                    'published'
                );
            }),
            new \Twig_Function('approvalArticles', function () {
                return $this->em->getRepository(Article::class)->countArticlesByStatus('editor');
            }),
            new \Twig_Function('correctorArticles', function () {
                return $this->em->getRepository(Article::class)->countArticlesByStatus('corrector');
            }),
            new \Twig_Function('publisherArticles', function () {
                return $this->em->getRepository(Article::class)->countArticlesByStatus('publisher');
            }),
        );
    }

    public function getFilters()
    {
        return [
            new \Twig_Filter('summary', function ($text) {
                #Supprimer les balises HTML
                $string = strip_tags($text);

                #Si ma chaine est supérieur a 170
                #Je poursuis sinon c'est inutile
                if (strlen($string) > 170) {
                    #je coupe ma chaine a 170
                    $stringCut = substr($string, 0, 170);

                    #Je m'assure de ne pas couper de mot
                    $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
                }
                return $string;
            })
        ];
    }
}
