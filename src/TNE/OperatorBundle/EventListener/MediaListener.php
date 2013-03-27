<?php

namespace TNE\OperatorBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Application\Sonata\MediaBundle\Entity\Media;


/**
 * Description of SiteListener
 *
 * @author zuhairnaqvi
 */

class MediaListener
{
    private $driver;
    private $kernel;
    private $provider;

    public function __construct($driver, $kernel, $provider)
    {
        $this->driver = $driver;
        $this->kernel = $kernel;
        $this->provider = $provider;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        $entityManager = $args->getEntityManager();

        if (null != $this->driver->getReader()->getClassAnnotation(new \ReflectionClass(get_class($entity)), 'TNE\\OperatorBundle\\Annotation\\ATDW\\Entity') && method_exists($entity, 'addMedia')) {
            
            $newMedia = $entity->getMedia();
            
            foreach($newMedia as &$operatorMedia){
                
                $remotePath = parse_url($operatorMedia->getRemotePath());
                $remotePathInfo = pathinfo($remotePath['path']);

                echo "\n ";
                
                echo "\033[32m Downloading Media: " . $remotePathInfo['basename'] . " \033[37m";

                copy($operatorMedia->getRemotePath(), $this->kernel->getRootDir().'/../web/uploads/media/default/0001/01/'.$remotePathInfo['basename']);
                
                $mediaItem = new Media();
                $mediaItem->setProviderName('sonata.media.provider.image');
                $mediaItem->setProviderReference($remotePathInfo['basename']);
                $mediaItem->setProviderMetadata(array('filename'=>$remotePathInfo['basename']));

                $mediaItem->setName($operatorMedia->getMultimediaId());
                $mediaItem->setDescription($operatorMedia->getAltText());
                $mediaItem->setAuthorName('ATDW');
                $mediaItem->setContext('default');
                $mediaItem->setHeight($operatorMedia->getHeight());
                $mediaItem->setWidth($operatorMedia->getWidth());
                $mediaItem->setContentType('image/jpeg');
                $mediaItem->setProviderStatus(Media::STATUS_OK);
                $mediaItem->setEnabled(true);
                $mediaItem->setCreatedAt(new \Datetime());
                $mediaItem->setUpdatedAt(new \Datetime());
                
//                $this->provider->generateThumbnails($mediaItem);
                
                $operatorMedia->setMediaItem($mediaItem);
            }
            
            unset($operatorMedia);
            
            $entity->setMedia($newMedia);
        }
    }
}

