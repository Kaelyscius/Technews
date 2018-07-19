<?php

namespace App\Category;

class CategoryRequest
{
    private $name;

    private $slug;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return CategoryRequest
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     *
     * @return CategoryRequest
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}
