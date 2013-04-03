<?php

namespace TNE\OperatorBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Description of SiteListener
 *
 * @author zuhairnaqvi
 */

class MediaThumbnailListener
{
    private $driver;
    private $provider;

    public function __construct($driver, $provider)
    {
        $this->driver = $driver;
        $this->provider = $provider;
    }
    
  
    public function postPersist(LifecycleEventArgs $args)
    {
        echo "\n Generating Thumbnail \n\n";
        $entity = $args->getEntity();
        
        if (null != $this->driver->getReader()->getClassAnnotation(new \ReflectionClass(get_class($entity)), 'TNE\\OperatorBundle\\Annotation\\ATDW\\Entity') && method_exists($entity, 'addMedia')) {
            foreach ($entity->getMedia() as $media)
            {
                //\Doctrine\Common\Util\Debug::dump($mediaItem);
                $this->provider->generateThumbnails($media->getMediaItem());  
            }
        }
    }
}

