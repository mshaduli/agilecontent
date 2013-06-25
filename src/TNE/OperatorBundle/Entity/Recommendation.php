<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Recommendation
 */
class Recommendation
{
    /**
     * @var integer
     */
    private $id;
    
    private $accommodation;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;  

     /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getAccommodation()
    {
        return $this->accommodation;
    }
    
    public function setAccommodation($accommodation)
    {
        $this->accommodation = $accommodation;
    }    

    /**
     * Set title
     *
     * @param string $title
     * @return Recommendation
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return AccommodationRoom
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

}
