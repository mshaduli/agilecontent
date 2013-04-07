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
     * @var integer
     * @ATDW\ProductAttrId
     */    
    private $atdwId;
        
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
     *@ATDW\EventStartDate
     */
    private $startDate;

    
    /**
     *@ATDW\EventEndDate
     */    
    private $endDate;
    
    
    /**
     * @ATDW\Latitude
     */    
    protected $latitude;
    
    /**
     * @ATDW\Longitude
     */    
    protected $longitude;

    /**
     * @ATDW\PhysicalAddress
     */
    private $address;
    
    /**
     *@ATDW\Media
     */
    protected $media;    

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
    
    /**
     * @Vich\GeographicalQuery
     */
    public function getCoordinates()
    {
        return array($this->getLatitude(),$this->getLongitude());
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

    public function getFromDate() {
        return $this->fromDate;
    }

    public function setFromDate($fromDate) {
        $this->fromDate = $fromDate;
    }

    public function getToDate() {
        return $this->toDate;
    }

    public function setToDate($toDate) {
        $this->toDate = $toDate;
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

    public function getMedia()
    {
        return $this->media;
    }
    
    public function setMedia($media)
    {
        $this->media = $media;
    }    
    
    public function addMedia($media){
        $media->setEvent($this);
        $this->media->add($media);        
    }
    
    public function removeMedia($media){
        $this->media->remove($media);
    }   
    public function getAtdwId() {
        return $this->atdwId;
    }

    public function setAtdwId($atdwId) {
        $this->atdwId = $atdwId;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }


}
