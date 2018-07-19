<?php

namespace App\Article;

use App\Entity\Article;

class ArticleFactory
{
    public function createFromArticleRequest(ArticleRequest $request) :Article
    {
        return new Article(
            '',
            $request->getTitle(),
            $request->getSlug(),
            $request->getContent(),
            $request->getFeaturedImage(),
            $request->getSpecial(),
            $request->getSpotlight(),
            $request->getCreatedDate(),
            $request->getCategory(),
            $request->getUser(),
            $request->getStatus()

        );
    }
}
