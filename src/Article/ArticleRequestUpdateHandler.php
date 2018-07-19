<?php

namespace App\Article;

use App\Controller\HelperTrait;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleRequestUpdateHandler
{
    use HelperTrait;
    private $em;
    private $assetsDirectory;

    /**
     * ArticleRequestUpdateHandler constructor.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     * @param string                                     $assetsDirectory
     */
    public function __construct(ObjectManager $manager, string $assetsDirectory)
    {
        $this->em = $manager;
        $this->assetsDirectory = $assetsDirectory;
    }

    /**
     * @param \App\Article\ArticleRequest $articleRequest
     * @param \App\Entity\Article         $article
     *
     * @return \App\Entity\Article
     */
    public function handle(ArticleRequest $articleRequest, Article $article)
    {
        #Traitement upload image
        $file = $articleRequest->getFeaturedImage();

        /**
         * TODO : pensez à supprimer l'ancienne image du FTP
         */
        if (null !== $file) {
            #Nom fichier
            $fileName = rand(0, 100).$this->slugify($articleRequest->getTitle()).'.'.$file->guessExtension();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->assetsDirectory,
                $fileName
            );

            #Mise a jour de l'image si il y en a une nouvelle
            $articleRequest->setFeaturedImage($fileName);
        } else {
            // ON récupère l'ancienne image si il y en a pas d'autre
            $articleRequest->setFeaturedImage($article->getFeaturedImage());
        }

        #mise à jour du contenu
        $article->update(
            $articleRequest->getTitle(),
            $this->slugify($articleRequest->getTitle()),
            $articleRequest->getContent(),
            $articleRequest->getFeaturedImage(),
            $articleRequest->getSpecial(),
            $articleRequest->getSpotlight(),
            $articleRequest->getCreatedDate(),
            $articleRequest->getCategory()
        );

        $this->em->flush();

        #On retourne notre article
        return $article;
    }
}
