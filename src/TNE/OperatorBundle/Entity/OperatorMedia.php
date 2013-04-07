<?php

namespace TNE\OperatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * OperatorMedia
 */
class OperatorMedia
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $multimedia_id;
    
    /**
     * @var integer
     */
    protected $accommodation;
    
    /**
     * @var integer
     */
    protected $event;    

    /**
     * @var \DateTime
     */
    protected $authored_date;

    /**
     * @var string
     */
    protected $remote_path;

    /**
     * @var string
     */
    protected $local_path;

    /**
     * @var string
     */
    protected $media_type;

    /**
     * @var string
     */
    protected $media_orientation;

    /**
     * @var string
     */
    protected $alt_text;
    
    
    protected $mediaItem;
    
    
    protected $height;
    
    protected $width;

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
     * Set multimedia_id
     *
     * @param integer $multimediaId
     * @return OperatorMedia
     */
    public function setMultimediaId($multimediaId)
    {
        $this->multimedia_id = $multimediaId;
    
        return $this;
    }

    /**
     * Get multimedia_id
     *
     * @return integer 
     */
    public function getMultimediaId()
    {
        return $this->multimedia_id;
    }

    /**
     * Set accommodation_id
     *
     * @param integer $productId
     * @return OperatorMedia
     */
    public function setAccommodation($accommodation)
    {
        $this->accommodation = $accommodation;
    
        return $this;
    }

    /**
     * Get accommodation_id
     *
     * @return integer 
     */
    public function getAccommodation()
    {
        return $this->accommodation;
    }

    /**
     * Set authored_date
     *
     * @param \DateTime $authoredDate
     * @return OperatorMedia
     */
    public function setAuthoredDate($authoredDate)
    {
        $this->authored_date = $authoredDate;
    
        return $this;
    }

    /**
     * Get authored_date
     *
     * @return \DateTime 
     */
    public function getAuthoredDate()
    {
        return $this->authored_date;
    }

    /**
     * Set remote_path
     *
     * @param string $remotePath
     * @return OperatorMedia
     */
    public function setRemotePath($remotePath)
    {
        $this->remote_path = $remotePath;
    
        return $this;
    }

    /**
     * Get remote_path
     *
     * @return string 
     */
    public function getRemotePath()
    {
        return $this->remote_path;
    }

    /**
     * Set local_path
     *
     * @param string $localPath
     * @return OperatorMedia
     */
    public function setLocalPath($localPath)
    {
        $this->local_path = $localPath;
    
        return $this;
    }

    /**
     * Get local_path
     *
     * @return string 
     */
    public function getLocalPath()
    {
        return $this->local_path;
    }

    /**
     * Set media_type
     *
     * @param string $mediaType
     * @return OperatorMedia
     */
    public function setMediaType($mediaType)
    {
        $this->media_type = $mediaType;
    
        return $this;
    }

    /**
     * Get media_type
     *
     * @return string 
     */
    public function getMediaType()
    {
        return $this->media_type;
    }

    /**
     * Set media_orientation
     *
     * @param string $mediaOrientation
     * @return OperatorMedia
     */
    public function setMediaOrientation($mediaOrientation)
    {
        $this->media_orientation = $mediaOrientation;
    
        return $this;
    }

    /**
     * Get media_orientation
     *
     * @return string 
     */
    public function getMediaOrientation()
    {
        return $this->media_orientation;
    }

    /**
     * Set alt_text
     *
     * @param string $altText
     * @return OperatorMedia
     */
    public function setAltText($altText)
    {
        $this->alt_text = $altText;
    
        return $this;
    }

    /**
     * Get alt_text
     *
     * @return string 
     */
    public function getAltText()
    {
        return $this->alt_text;
    }
    
    public function getMediaItem() {
        return $this->mediaItem;
    }

    public function setMediaItem($mediaItem) {
        $this->mediaItem = $mediaItem;
    }
    
    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    
    public function getEvent() {
        return $this->event;
    }

    public function setEvent($event) {
        $this->event = $event;
    }

                
    public function setField($key, $value)
    {
        switch($key)
        {
            case 'multimedia_id':
                $this->setMultimediaId($value);
                break;
            case 'authored_date':
                $this->setAuthoredDate($value);
                break;
            case 'server_path':
                $this->setRemotePath($value);
                break;
            case 'attribute_id_multimedia_content':
                $this->setMediaType($value);
                break;
            case 'attribute_id_size_orientation':
                $this->setMediaOrientation($value);
                break;            
            case 'alt_text':
                $this->setAltText($value);
                break;   
            case 'height':
                $this->setHeight($value);
                break;   
            case 'width':
                $this->setWidth($value);
                break;               
            default:
                // Do nothing
        }
    }
}
