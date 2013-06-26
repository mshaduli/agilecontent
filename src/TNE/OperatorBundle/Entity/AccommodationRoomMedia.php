<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * AccommodationRoomMedia
 */
class AccommodationRoomMedia
{
    /**
     * @var integer
     */
    protected $id;

    
    /**
     * @var integer
     */
    protected $accommodation_room;
    
    protected $mediaItem;
    
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
     * Set accommodation_room_id
     *
     * @param integer $accommodation_room_id
     * @return AccommodationRoomMedia
     */
    public function setAccommodationRoom($accommodation)
    {
        $this->accommodation_room = $accommodation;
    
        return $this;
    }

    /**
     * Get accommodation_room_id
     *
     * @return integer 
     */
    public function getAccommodationRoom()
    {
        return $this->accommodation_room;
    }
    
    
    public function getMediaItem() {
        return $this->mediaItem;
    }

    public function setMediaItem($mediaItem) {
        $this->mediaItem = $mediaItem;
    }
    
}
