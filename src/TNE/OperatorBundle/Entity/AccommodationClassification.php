<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccommodationClassification
 */
class AccommodationClassification
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    private $key;

    private $accommodation;


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
     * @return AccommodationClassification
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

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setAccommodation($accommodation)
    {
        $this->accommodation = $accommodation;
    }

    public function getAccommodation()
    {
        return $this->accommodation;
    }


}
