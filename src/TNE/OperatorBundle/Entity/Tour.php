<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TNE\OperatorBundle\Annotation\ATDW as ATDW;

/**
 * 
 * @ATDW\Entity(type="TOUR")
 * 
 */
class Tour
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
     *@ATDW\ProductDescription
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

    /**
     * Set name
     *
     * @param string $name
     * @return Tour
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
     * @return Tour
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
    
    public function __toString() 
    {
        return $this->getName();
    }

}
