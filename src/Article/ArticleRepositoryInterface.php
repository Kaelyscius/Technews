<?php

namespace App\Article;

use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Interface ArticleRepositoryInterface
 * @package App\Article
 */
interface ArticleRepositoryInterface extends ObjectRepository
{
    /**
     * Permet de retourner un article sur la base
     * de son identifiant Unique
     *
     * @param mixed $id
     *
     * @return \App\Entity\Article|null
     */
    public function find($id): ?Article;

    /**
     * Retourne la liste de tout les articles
     * @return iterable|null
     */
    public function findAll(): ?iterable;

    /**
     * @return iterable|null
     */
    public function findLastFiveArticles(): ?iterable;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
}
