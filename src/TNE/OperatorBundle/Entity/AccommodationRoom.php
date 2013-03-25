<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccommodationRoom
 */
class AccommodationRoom
{
    /**
     * @var integer
     */
    private $id;
    
    private $accommodation;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var float
     */
    private $rateFrom;

    /**
     * @var float
     */
    private $rateTo;
    


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
     * Set name
     *
     * @param string $name
     * @return AccommodationRoom
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

    /**
     * Set rate_from
     *
     * @param float $rateFrom
     * @return AccommodationRoom
     */
    public function setRateFrom($rateFrom)
    {
        $this->rateFrom = $rateFrom;
    
        return $this;
    }

    /**
     * Get rate_from
     *
     * @return float 
     */
    public function getRateFrom()
    {
        return $this->rateFrom;
    }

    /**
     * Set rate_to
     *
     * @param float $rateTo
     * @return AccommodationRoom
     */
    public function setRateTo($rateTo)
    {
        $this->rateTo = $rateTo;
    
        return $this;
    }

    /**
     * Get rate_to
     *
     * @return float 
     */
    public function getRateTo()
    {
        return $this->rateTo;
    }

    /**
     * Set photos
     *
     * @param array $photos
     * @return AccommodationRoom
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    
        return $this;
    }

    /**
     * Get photos
     *
     * @return array 
     */
    public function getPhotos()
    {
        return $this->photos;
    }
    
}
