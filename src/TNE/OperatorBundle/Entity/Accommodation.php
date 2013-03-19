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
     * @var integer
     * @ATDW\ProductName
     */       
    private $name = 'test';

    /**
     * @var string
     */
    private $alias;
    
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
     * @param string $alias
     * @return Accommodation
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

 
    /**
     * Get address
     * @Vich\GeographicalQuery
     *
     * @return string 
     */
    public function getAddress()
    {
        return 'Surrey Ln, Beechworth VIC 3747';
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
    
    public function getTags()
    {
        return $this->tags;
    }
    
    
    public function setTags($tags)
    {
        $this->tags = $tags;
    }    
    
    public function __toString() {
        return $this->getAlias();
    }
    
    public function getMap()
    {
        return $this->getAddress();
    }
    
    public function setMap()
    {
        
    }
    
}
