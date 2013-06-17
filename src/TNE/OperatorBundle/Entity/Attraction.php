<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Vich\GeographicalBundle\Annotation as Vich;
use TNE\OperatorBundle\Annotation\ATDW as ATDW;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Vich\Geographical(lat="latitude", lng="longitude")
 * @Vich\Geographical(on="update")
 * 
 * @ATDW\Entity(type="ATTRACTION")
 * 
 */
class Attraction
{
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var integer
     * @ATDW\ProductAttrId
     */    
    private $atdwId = 0;
        

    /**
     * @ATDW\ProductName
     */      
    private $name;

    /**
     *@ATDW\ProductDescription
     */
    private $description;
    
    /**
     * @ATDW\PhysicalAddress
     */
    private $address;    
    
    /**
     *@ATDW\CityName
     */    
    private $destination;
    
    /**
     * @ATDW\Latitude
     */    
    protected $latitude;
    
    /**
     * @ATDW\Longitude
     */    
    protected $longitude;
            

    /**
     *@ATDW\Media
     */
    protected $media;    
    
    protected $tags;

    public function __construct() {
        $this->media = new ArrayCollection();
    }    
    
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
     * @return Attraction
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
     * @return Attraction
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
    
    public function getAtdwId() {
        return $this->atdwId;
    }

    public function setAtdwId($atdwId) {
        $this->atdwId = $atdwId;
    }
        
    public function getMedia()
    {
        return $this->media;
    }
    
    public function setMedia($media)
    {
        $this->media = $media;
    }    
    
    public function addMedia($media){
        $media->setAttraction($this);
        $this->media->add($media);        
    }
    
    public function removeMedia($media){
        $this->media->remove($media);
    }         
    
    public function getDestination() {
        return $this->destination;
    }

    public function setDestination($destination) {
        $this->destination = $destination;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function setLongitude($longitude) {
        $this->longitude = $longitude;
    }

    /**
     * @Vich\GeographicalQuery
     */
    public function getCoordinates()
    {
        return array($this->getLatitude(),$this->getLongitude());
    }
    
    public function __toString() {
        return $this->getName();
    }
    
    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getTags() {
        return $this->tags;
    }

    public function setTags($tags) {
        $this->tags = $tags;
    }



}
