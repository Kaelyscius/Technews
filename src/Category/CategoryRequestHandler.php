<?php

namespace App\Category;

use App\Controller\HelperTrait;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryRequestHandler
{
    use HelperTrait;

    private $em;

    private $categoryFactory;

    /**
     * ArticleRequestHandler constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Category\CategoryFactory        $categoryFactory
     * @param                                      $assetsDirectory
     *
     * @internal  param $em
     */
    public function __construct(EntityManagerInterface $entityManager, CategoryFactory $categoryFactory)
    {
        $this->em = $entityManager;
        $this->categoryFactory = $categoryFactory;
    }

    public function handle(CategoryRequest $request) : Category
    {


        #Mise à jour du slug
        $request->setSlug($this->slugify($request->getName()));

        #Appel a notre factory
        $article = $this->categoryFactory->createFromArticleRequest($request);

        #insertion en BDD
        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }
}
