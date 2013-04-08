<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccommodationRoomCalendar
 */
class AccommodationRoomCalendar
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var float
     */
    private $rate;
    
    private $available = true;
    
    private $room;

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
     * Set date
     *
     * @param \DateTime $date
     * @return AccommodationRoomCalendar
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set rate
     *
     * @param float $rate
     * @return AccommodationRoomCalendar
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    
        return $this;
    }

    /**
     * Get rate
     *
     * @return float 
     */
    public function getRate()
    {
        return $this->rate;
    }
    
    public function getAvailable() {
        return $this->available;
    }

    public function setAvailable($available) {
        $this->available = $available;
    }
    public function getRoom() {
        return $this->room;
    }

    public function setRoom($room) {
        $this->room = $room;
    }

    public function __toString() {
        return 'Manage';
    }
    
    public function json()
    {
        return \json_encode(array('id'=>$this->getId(),'room_id'=>  $this->getRoom()->getId(), 'rate'=>  $this->getRate(), 'available'=>  $this->getAvailable()));
    }

}
