<?php

namespace App\Article;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Workflow\Registry;

/**
 * Service pour alléger le controller la méthode workflow
 * Class ArticleWorkflowHandler
 * @package App\Article
 */
class ArticleWorkflowHandler
{
    private $workflows;
    private $manager;

    public function __construct(Registry $workflows, ObjectManager $manager)
    {
        $this->workflows = $workflows;
        $this->manager = $manager;
    }

    /**
     * @param \App\Entity\Article $article
     * @param                     $status
     */
    public function handle(Article $article, $status)
    {
        #récupération du workflow
        $workflow = $this->workflows->get($article);

        #Changement du workflow
        $workflow->apply($article, $status);

        $em = $this->manager;
        $em->flush();


        if ($workflow->can($article, 'to_be_published')) {
            $workflow->apply($article, 'to_be_published');
            $em->flush();
        }
    }
}
