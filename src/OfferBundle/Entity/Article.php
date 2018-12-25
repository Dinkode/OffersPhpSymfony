<?php

namespace OfferBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="OfferBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAdded", type="datetime")
     */
    private $dateAdded;

    /**
     * @var string
     *
     * @ORM\Column(name="featured_image", type="string")
     */
    private $featuredImage;

    /**
     * @var string
     */
    private $summary;


    /**
     * @var int
     *
     * @ORM\Column(name="author_id", type="integer")
     */
    private $authorId;

    /**
     * @var ArrayCollection|Image[]
     *
     * @ORM\OneToMany(targetEntity="OfferBundle\Entity\Image", mappedBy="item", cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="OfferBundle\Entity\Category", inversedBy="items")
     */
    private $category;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OfferBundle\Entity\Comment", mappedBy="item")
     */
    private $comments;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;


    /**
     * @var bool
     *
     * @ORM\Column(name="free_shipping", type="boolean")
     */
    private $freeShipping;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_new", type="boolean")
     */
    private $isNew;

    /**
     * @var int
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views;


    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string")
     */
    private $city;


    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->dateAdded = new \DateTime('now');
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return bool
     */
    public function isFreeShipping(): bool
    {
        return $this->freeShipping;
    }

    /**
     * @param bool $freeShipping
     */
    public function setFreeShipping(bool $freeShipping): void
    {
        $this->freeShipping = $freeShipping;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param bool $isNew
     */
    public function setIsNew(bool $isNew): void
    {
        $this->isNew = $isNew;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }


    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment | null $comment
     * @return Article
     */
    public function addComments($comment)
    {
        $this->comments[] = $comment;
        return $this;
    }



    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param $image
     *
     * @return Article
     */
    public function addImage($image)
    {

        $this->images[] = $image;
        return $this;
    }



    /**
     * @return string
     */
    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    /**
     * @param $featuredImage
     */
    public function setFeaturedImage($featuredImage)
    {
        $this->featuredImage = $featuredImage;
    }



    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param int $authorId
     * @return Article
     */
    public function setAuthorId(int $authorId)
    {
        $this->authorId = $authorId;
        
        return $this;
    }




    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="OfferBundle\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    private $author;

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return Article
     */
    public function setAuthor(User $author): Article
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        if (strlen($this->getContent())>50){
            return $this->getSummary();
        }
        return $this->getContent();
    }


    public function setSummary(): void
    {
        $this->summary = substr($this->getContent(), 0, strlen($this->getContent()/2))."...";
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Article
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }
}

