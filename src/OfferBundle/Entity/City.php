<?php

namespace OfferBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="cities")
 * @ORM\Entity(repositoryClass="OfferBundle\Repository\CityRepository")
 */
class City
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="OfferBundle\Entity\Article", mappedBy="city")
     *
     * @var ArrayCollection
     */
    private $articles;

    /**
     * City constructor.
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|Article[]
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param $article
     *
     * @return City
     */
    public function addArticles($article)
    {
        $this->articles = $article;
        return $this;
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
     * Set name
     *
     * @param string $name
     *
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

