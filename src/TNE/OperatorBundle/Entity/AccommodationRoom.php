<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    private $temp;
     
    public $path;
    
    public $file;

    
    private $dates;

    private $bookings;
    
    private $maxCapacity;
    
    public function __construct() {
        $this->dates = new ArrayCollection();
//        $this->media = new ArrayCollection();
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
    
    
    public function getDates() {
        return $this->dates;
    }

    public function setDates($dates) {
        $this->dates = $dates;
    }

    public function addDate($date)
    {
        $date->setRoom($this);
        $this->dates->add($date);
    }
    
    public function removeDate($date)
    {
        $this->dates->remove($date);
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

    public function setField($key, $value)
    {
        switch($key)
        {
            case 'service_name':
                $this->setName($value);
                break;
            case 'service_description':
                $this->setDescription($value);
                break;
            case 'rate_from':
                $this->setRateFrom($value);
                break;
            case 'rate_to':
                $this->setRateTo($value);
                break;
            default:
                // Do nothing
        }
    }
    
    public function setId($id) {
        $this->id = $id;
    }


    /**
     * @param mixed $bookings
     */
    public function setBookings($bookings)
    {
        $this->bookings = $bookings;
    }

    /**
     * @return mixed
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null != $this->getFile()) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
            
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->getFile()->move($this->getUploadRootDir(), $this->path);
        if ($this->temp) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/rooms';
    }
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? 'noimg.jpg'
            : $this->getUploadRootDir().'/'.$this->path;
    }
    
    /**
     * Set $maxCapacity
     *
     * @param string $maxCapacity
     * @return AccommodationRoom
     */
    public function setMaxCapacity($maxCapacity)
    {
        $this->maxCapacity = $maxCapacity;
    
        return $this;
    }

    /**
     * Get $maxCapacity
     *
     * @return string 
     */
    public function getMaxCapacity()
    {
        return $this->maxCapacity;
    }    

}
