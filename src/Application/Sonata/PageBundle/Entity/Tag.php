<?php

namespace Application\Sonata\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 */
class Tag
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $pages;
    
        /**
     * @var array
     */
    private $media;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Tag
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

    /**
     * Set pages
     *
     * @param array $pages
     * @return Tag
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    
        return $this;
    }

    /**
     * Get pages
     *
     * @return array 
     */
    public function getPages()
    {
        return $this->pages;
    }
    
    
    /**
     * Set media
     *
     * @param array $media
     * @return Tag
     */
    public function setMedia($media)
    {
        $this->media = $media;
    
        return $this;
    }

    /**
     * Get media
     *
     * @return array 
     */
    public function getMedia()
    {
        return $this->media;
    }    
    
    public function __toString() {
        return $this->getName();
    }
}
