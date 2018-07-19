<?php

namespace App\Article\Source;

use App\Article\ArticleCatalogue;
use App\Article\ArticleRepositoryInterface;
use App\Entity\Article;

abstract class ArticleAbstractSource implements ArticleRepositoryInterface
{
    /**
     * @var
     */
    protected $catalogue;

    /**
     * @param \App\Article\ArticleCatalogue $catalogue
     */
    public function setCatalogue(ArticleCatalogue $catalogue): void
    {
        $this->catalogue = $catalogue;
    }

    /**
     * Permet de convertir un tableau en Objet
     * @param iterable $article article sous forme de tableau
     *
     * @return \App\Entity\Article|null
     */
    abstract protected function createFromArray(iterable $article): ?Article;
}
