<?php

namespace App\Article\Source;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class DoctrineSource extends ArticleAbstractSource
{
    private $repository;
    private $entity = Article::class;

    /**
     * DoctrineSource constructor.
     * @param $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->repository = $manager->getRepository($this->entity);
    }

    /**
     * Permet de retourner un article sur la
     * base de son identifiant unique.
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        return $this->repository->find($id);
    }

    /**
     * Retourne la liste de tous les articles
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {
        return $this->repository->findAll();
    }

    /**
     * Retourne les 5 derniers articles depuis
     * l'ensemble de nos sources...
     * @return iterable|null
     */
    public function findLastFiveArticles(): ?iterable
    {
        return $this->repository->findLastFiveArticles();
    }

    /**
     * Retourne le nombre d'éléments de chaque source.
     * @return int
     */
    public function count(): int
    {
        return $this->repository->findTotalArticles();
    }

    /**
     * Permet de convertir un tableau en Article.
     * @param iterable $article Un article sous forme de tableau
     * @return Article|null
     */
    protected function createFromArray(iterable $article): ?Article
    {
        return null;
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        // TODO: Implement findBy() method.
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param mixed[] $criteria The criteria.
     *
     * @return object|null The object.
     */
    public function findOneBy(array $criteria)
    {
        // TODO: Implement findOneBy() method.
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        // TODO: Implement getClassName() method.
    }
}
