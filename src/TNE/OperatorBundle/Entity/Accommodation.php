<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\GeographicalBundle\Annotation as Vich;
use TNE\OperatorBundle\Annotation\ATDW as ATDW;

/**
 * @Vich\Geographical(lat="latitude", lng="longitude")
 * @Vich\Geographical(on="update")
 */
class Accommodation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     * @ATDW\ProductAttrId
     */    
    private $atdwId;
    
    /**
     * @ATDW\ProductName
     */       
    private $name;
    
    /**
     *ATDW\rateFrom
     */
    private $atdwRateFrom;
    
    /**
     *ATDW\productDescription
     */
    private $description;
    
    /**
     *ATDW\starRating
     */
    private $atdwStarRating;

    
    /**
     *ATDW\cityName
     */    
    private $destination;

            
    /**
     *ATDW\address
     */    
    private $address;


    /**
     * @var decimal
     */    
    protected $latitude;
    
    /**
     * @var decimal
     */    
    protected $longitude;
        
    /**
     * @var type ArrayCollection
     */
    protected $tags;

    /**
     *
     * @var type int
     */
    protected $site;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getAtdwId(){
        return $this->atdwId;
    }
    
    public function setAtdwId($atdwId){
        $this->atdwId = $atdwId;
        return $this;
    }

    /**
     * Set alias
     *
     * @param string $name
     * @return Accommodation
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

    
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
 
    /**
     * Get address
     * @Vich\GeographicalQuery
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
    

    public function setLatitude($value)
    {
        $this->latitude = $value;
    }

    public function setLongitude($value)
    {
        $this->longitude = $value;
    }
    
    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }
    

    public function setSite($site)
    {
        $this->site = $site;
    }

    public function getSite()
    {
        return $this->site;
    }
    
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    public function getDestination()
    {
        return $this->destination;
    }    
    
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }        
    
    public function getTags()
    {
        return $this->tags;
    }
    
    
    public function setTags($tags)
    {
        $this->tags = $tags;
    }    
    
    public function __toString() {
        return $this->getName();
    }
    
    public function getMap()
    {
        return $this->getAddress();
    }
    
    public function setMap()
    {
        
    }
    
}
