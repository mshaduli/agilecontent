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

    private $keyStr;

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

    public function setKeyStr($key)
    {
        $this->keyStr = $key;
    }

    public function getKeyStr()
    {
        return $this->keyStr;
    }

    public function setAccommodation($accommodation)
    {
        $this->accommodation = $accommodation;

        return $this;
    }

    public function getAccommodation()
    {
        return $this->accommodation;
    }

    function __toString()
    {
        return $this->getName();
    }


}
