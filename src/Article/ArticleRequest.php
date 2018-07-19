<?php

namespace App\Article;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ArticleRequest
 * Gere la validation et la persistance en BDD
 * @property null id
 * @package App\Article
 */
class ArticleRequest
{
    /**
     * Validation
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $title;
    private $slug;
    private $content;

    /**
     * @Assert\Image(mimeTypesMessage="Votre image n'est pas reconnue")
     */
    private $featuredImage;
    private $special;
    private $spotlight;
    private $createdDate;
    private $category;
    private $user;
    private $imageURL;

    private $status;

    /**
     * ArticleRequest constructor.
     *
     * @param $user
     */
    public function __construct(User $user)
    {
        #On donne l'auter directement
        $this->user = $user;
        $this->createdDate = new \DateTime();
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return ArticleRequest
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * @return ArticleRequest
     */
    public function setSlug($slug): ArticleRequest
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     *
     * @return ArticleRequest
     */
    public function setContent($content): ArticleRequest
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    /**
     * @param mixed $featuredImage
     *
     * @return ArticleRequest
     */
    public function setFeaturedImage($featuredImage): ArticleRequest
    {
        $this->featuredImage = $featuredImage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * @param mixed $special
     *
     * @return ArticleRequest
     */
    public function setSpecial($special): ArticleRequest
    {
        $this->special = $special;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpotlight()
    {
        return $this->spotlight;
    }

    /**
     * @param mixed $spotlight
     *
     * @return ArticleRequest
     */
    public function setSpotlight($spotlight): ArticleRequest
    {
        $this->spotlight = $spotlight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param mixed $createdDate
     *
     * @return ArticleRequest
     */
    public function setCreatedDate($createdDate): ArticleRequest
    {
        $this->createdDate = $createdDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     *
     * @return ArticleRequest
     */
    public function setCategory($category): ArticleRequest
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     *
     * @return ArticleRequest
     */
    public function setUser($user): ArticleRequest
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageURL()
    {
        return $this->imageURL;
    }

    /**
     * @param mixed $imageURL
     *
     * @return ArticleRequest
     */
    public function setImageURL($imageURL): ArticleRequest
    {
        $this->imageURL = $imageURL;
        return $this;
    }

    /**
     * Création d'un Article request depuis un article
     *
     * @param \App\Entity\Article               $article
     *
     * @param \Symfony\Component\Asset\Packages $package
     *
     * @param string                            $assetDirectory
     *
     * @return \App\Article\ArticleRequest
     */
    public static function createFromArticle(Article $article, Packages $package, string $assetDirectory) :self
    {
        $articleRequest = new self($article->getUser());
        $articleRequest->id = $article->getId();
        $articleRequest->title = $article->getTitle();
        $articleRequest->slug = $article->getSlug();
        $articleRequest->content = $article->getContent();
        #Création d'une nouvelle image recommandé dans la doc SF
        $articleRequest->featuredImage = new File($assetDirectory.DIRECTORY_SEPARATOR.$article->getFeaturedimage());
        #Juste pour le pluggin Dropify
        $articleRequest->imageURL = $package->getUrl('images/product/'.$article->getFeaturedimage());
        $articleRequest->special = $article->getSpecial();
        $articleRequest->spotlight = $article->getSpotlight();
        $articleRequest->createdDate = $article->getCreatedDate();
        $articleRequest->category = $article->getCategory();
        $articleRequest->status = $article->getStatus();


        return $articleRequest;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return ArticleRequest
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
