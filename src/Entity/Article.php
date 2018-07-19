<?php

namespace App\Entity;

use App\Controller\HelperTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    use HelperTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $slug;


    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $featuredImage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $special;

    /**
     * @ORM\Column(type="boolean")
     */
    private $spotlight;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * Plusieurs articles appartienne a une catégorie
     * invertedBy => c'est la variable qui est utilisé pour retrouver la catégorie pour nos articles
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     * Nullable à false pour obligé d'avoir une catégorie pour retourner un article
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="array")
     */
    private $status;

    /**
     * Article constructor.
     *
     * @param $id
     * @param $title
     * @param $slug
     * @param $content
     * @param $featuredImage
     * @param $special
     * @param $spotlight
     * @param $createdDate
     * @param $category
     * @param $user
     * @param $status
     */
    public function __construct(
        $id = null,
        $title,
        $slug,
        $content,
        $featuredImage,
        $special,
        $spotlight,
        $createdDate,
        $category,
        $user,
        $status
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->featuredImage = $featuredImage;
        $this->special = $special;
        $this->spotlight = $spotlight;
        $this->createdDate = $createdDate;
        $this->category = $category;
        $this->user = $user;
        $this->status = $status;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage($featuredImage): self
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    public function getSpecial(): ?bool
    {
        return $this->special;
    }

    public function setSpecial(bool $special): self
    {
        $this->special = $special;

        return $this;
    }

    public function getSpotlight(): ?bool
    {
        return $this->spotlight;
    }

    public function setSpotlight(bool $spotlight): self
    {
        $this->spotlight = $spotlight;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
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
     * @return Article
     */
    public function setCategory($category)
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
     * @return Article
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function update(
        string $title,
        string $slug,
        string $content,
        string $featuredImage,
        bool $special,
        bool $spotlight,
        \DateTime $createdDate,
        Category $category
    ) {
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->featuredImage = $featuredImage;
        $this->special = $special;
        $this->spotlight = $spotlight;
        $this->createdDate = $createdDate;
        $this->category = $category;
    }

    /**
     * @param mixed $id
     *
     * @return Article
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return Article
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
