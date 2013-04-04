<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TNE\OperatorBundle\Annotation\ATDW as ATDW;

/**
 * 
 * @ATDW\Entity(type="EVENT")
 * 
 */
class Event
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @ATDW\ProductName
     */       
    private $name;
    
    /**
     *@ATDW\EventFrequency
     */
    private $frequency;
    

    /**
     *@ATDW\ProductDescription
     */
    private $description;

    /**
     * @var string
     */
    private $address;


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
     * @return Event
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
     * @return Event
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
     * Set address
     *
     * @param string $address
     * @return Event
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    
    public function getFrequency() {
        return $this->frequency;
    }

    public function setFrequency($frequency) {
        $this->frequency = $frequency;
    }
    
    public function __toString() {
        return $this->getName();
    }


}
