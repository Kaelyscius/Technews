<?php

namespace App\Article;

use App\Article\Source\ArticleAbstractSource;

/**
 * Interface ArticleCatalogueInterface
 * @package App\Article
 */
interface ArticleCatalogueInterface extends ArticleRepositoryInterface
{
    /**
     * @param $source
     */
    public function addSource(ArticleAbstractSource $source): void;

    /**
     * @param $sources
     */
    public function setSources(iterable $sources):void;

    /**
     * @return iterable
     */
    public function getSources():iterable;
}
