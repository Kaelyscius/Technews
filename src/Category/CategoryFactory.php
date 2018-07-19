<?php

namespace App\Category;

use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;

class CategoryFactory
{
    public function createFromArticleRequest(CategoryRequest $request) :Category
    {
        return new Category(
            $request->getName(),
            $request->getSlug(),
            new ArrayCollection()
        );
    }
}
