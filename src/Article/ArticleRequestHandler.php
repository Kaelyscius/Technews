<?php

namespace App\Article;

use App\Controller\HelperTrait;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;

/**
 * Class ArticleRequestHandler
 *  On gère nous même la gestion de la requête de nos articles. (Une fois le formulaire soumis et validé
 * @package App\Article
 */
class ArticleRequestHandler
{
    use HelperTrait;
    private $em;

    private $assetsDirectory;

    private $articleFactory;
    private $packages;
    private $workflows;



    /**
     * ArticleRequestHandler constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Article\ArticleFactory          $articleFactory
     * @param                                      $assetsDirectory
     *
     * @param \Symfony\Component\Workflow\Registry $workflows
     * @param \Symfony\Component\Asset\Packages    $packages
     *
     * @internal  param $em
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ArticleFactory $articleFactory,
        $assetsDirectory,
                                Packages $packages,
        Registry $workflows
    ) {
        $this->em = $entityManager;
        $this->articleFactory = $articleFactory;
        $this->assetsDirectory = $assetsDirectory;
        $this->packages = $packages;
        $this->workflows = $workflows;
    }

    public function handle(ArticleRequest $request) : ?Article
    {


        #Traitement upload image
        $file = $request->getFeaturedImage();
        #Nom fichier
        $fileName = rand(0, 100).$this->slugify($request->getTitle()).'.'.$file->guessExtension();

        // moves the file to the directory where brochures are stored
        $file->move(
            $this->assetsDirectory,
            $fileName
        );
        $request->setFeaturedImage($fileName);

        #Mise à jour du slug
        $request->setSlug($this->slugify($request->getTitle()));

        #Récupération du workflow
        $workflow = $this->workflows->get($request);

        #Permet de voir les transitions possible (changement de status)
//        dd($workflow->getEnabledTransitions($request));
        try {
            $workflow->apply($request, 'to_review');
            #Appel a notre factory
            $article = $this->articleFactory->createFromArticleRequest($request);

            #insertion en BDD
            $this->em->persist($article);
            $this->em->flush();

            return $article;
        } catch (LogicException $e) {
            # Transistion non autorisé
            return null;
        }
    }

    /**
     * @param \App\Entity\Article $article
     *
     * @return \App\Article\ArticleRequest
     */
    public function prepareArticleFromRequest(Article $article) :ArticleRequest
    {
        return ArticleRequest::createFromArticle($article, $this->packages);
    }
}
