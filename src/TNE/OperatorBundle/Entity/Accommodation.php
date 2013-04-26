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
 * @ATDW\Entity(type="ACCOMM")
 * 
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
    private $atdwId = 0;
    
    /**
     * @ATDW\ProductName
     */       
    private $name;
    
    /**
     *ATDW\rateFrom
     */
    private $atdwRateFrom;
    
    /**
     *@ATDW\ProductDescription
     */
    private $description;
    
    /**
     *@ATDW\StarRating
     */
    private $atdwStarRating;
    
    private $selfRating = 0;

    private $starRating = 0;

    /**
     *@ATDW\CityName
     */    
    private $destination;

            
    /**
     *@ATDW\PhysicalAddress
     */    
    private $address;


    /**
     * @ATDW\Latitude
     */    
    protected $latitude;
    
    /**
     * @ATDW\Longitude
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
     *@ATDW\Twitter
     */
    protected $twitterUrl;


    /**
     *@ATDW\Facebook
     */
    protected $facebookUrl;


    /**
     *@ATDW\TripAdvisor
     */
    protected $tripadvisorUrl;
    
    /**
     *@ATDW\ContactEmail
     */
    protected $atdwContactEmail;
    
    /**
     *@ATDW\ContactPhone
     */
    protected $atdwContactPhone;    
    
    /**
     *@ATDW\ContactMobile
     */
    protected $atdwContactMobile;    
    
    /**
     *@ATDW\ContactUrl
     */
    protected $atdwContactUrl;    
    
    /**
     *@ATDW\Rooms
     */
    protected $rooms;
    
    /**
     *@ATDW\Media
     */
    protected $media;
    
    
    protected $hiddenSecret;


    protected $gallery;


    public function __construct() {
        $this->rooms = new ArrayCollection();
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
    
    /**
     * @Vich\GeographicalQuery
     */
    public function getCoordinates()
    {
        return array($this->getLatitude(),$this->getLongitude());
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
    
    public function getAtdwStarRating()
    {
        return $this->atdwStarRating;
    }

    public function setAtdwStarRating($atdwStarRating)
    {
        $this->atdwStarRating = $atdwStarRating;
        return $this;
    }    

    public function setTags($tags)
    {
        $this->tags = $tags;
    }       
    
    public function getAtdwContactEmail(){
        return $this->atdwContactEmail;
    }
    
    public function getAtdwContactPhone(){
        return $this->atdwContactPhone;
    }    

    public function getAtdwContactMobile(){
        return $this->atdwContactMobile;
    }    
    
    public function getAtdwContactUrl(){
        return $this->atdwContactUrl;
    }
    
    public function setAtdwContactEmail($atdwContactEmail){
        $this->atdwContactEmail = $atdwContactEmail;
    }
    
    public function setAtdwContactPhone($atdwContactPhone){
        $this->atdwContactPhone = $atdwContactPhone;
    }    

    public function setAtdwContactMobile($atdwContactMobile){
        $this->atdwContactMobile = $atdwContactMobile;
    }    
    
    public function setAtdwContactUrl($atdwContactUrl){
        $this->atdwContactUrl = $atdwContactUrl;
    }        
    
    public function getRooms()
    {
        return $this->rooms;
    }
    
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;
    }    
    
    public function addRoom($room){
        $room->setAccommodation($this);
        $this->rooms->add($room);
    }
    
    public function removeRoom($room){
        $this->rooms->remove($room);
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
        $media->setAccommodation($this);
        $this->media->add($media);        
    }
    
    public function removeMedia($media){
        $this->media->remove($media);
    }       
    
    public function getGallery()
    {
        return $this->gallery;
    }
    
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
    }    
    
    public function getFacebookUrl()
    {
        return $this->facebookUrl;
    }
    
    public function setFacebookUrl($facebookUrl)
    {
        $this->facebookUrl = $facebookUrl;
    } 
    
    public function getTwitterUrl()
    {
        return $this->twitterUrl;
    }    
    
    public function setTwitterUrl($twitterUrl)
    {
        $this->twitterUrl = $twitterUrl;
    }     
    
    
    public function getTripadvisorUrl()
    {
        return $this->tripadvisorUrl;
    }    
    
    public function setTripadvisorUrl($tripadvisorUrl)
    {
        $this->tripadvisorUrl = $tripadvisorUrl;
    }     
        
    
    public function getRoomCount()
    {
        return $this->getRooms()->count();
    }
    
    public function getHiddenSecret() {
        return $this->hiddenSecret;
    }

    public function setHiddenSecret($hiddenSecret) {
        $this->hiddenSecret = $hiddenSecret;
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
    
    public function getHeroImage()
    {
        foreach ($this->getMedia() as $media)
        {
            //if($media->getMediaItem()->hasTag('hero')) return $media->getMediaItem();
 
            return $media->getMediaItem();
        }
 
        return false;
    }
    
    public function getTagLinks()
    {
        $tagLinks = array();
        foreach($this->getTags() as $tag)
        {
            if(!$tag->getHidden())
            {
                $tagLinks[]=$tag->getName();
            }
        }
        return $tagLinks;
    }    
    
    public function getSelfRating() {
        return $this->selfRating;
    }

    public function setSelfRating($selfRating) {
        $this->selfRating = $selfRating;
    }
    
    public function getRating()
    {
        return $this->getStarRating() == 0 ? $this->getSelfRating() : $this->getStarRating();
    }
    
    public function computeStarRating()
    {        
        if ($this->getAtdwStarRating() == 'Not Available' ) $this->setStarRating(0); 
        else $this->setStarRating($this->fractionToDec($this->getAtdwStarRating()));
    }
    
    public function fractionToDec($input)
    {
        $rating = explode(' ', \str_replace(' Stars', '', $input));
        $result = 0;
        $fraction = array('whole'=>0);
        if(count($rating)>1)
        {
            $fraction['whole'] = $rating[0];
            $part = explode('/', $rating[1]);
            $fraction['n'] = $part[0];
            $fraction['d'] = $part[1];
            $result = $fraction['whole'] + $fraction['n'] / $fraction['d'];
        }
        return $result;
    }


    public function getStarRating() {
        return $this->starRating;
    }

    public function setStarRating($starRating) {
        $this->starRating = $starRating;
    }


    
}
