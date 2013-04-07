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
    
    public function setServices($driver, $kernel) {
        $this->driver = $driver;
        $this->kernel = $kernel;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if (null != $this->driver->getReader()->getClassAnnotation(new \ReflectionClass(get_class($entity)), 'TNE\\OperatorBundle\\Annotation\\ATDW\\Entity') && method_exists($entity, 'addMedia')) {
            
            $newMedia = $entity->getMedia();
            
            foreach($newMedia as &$operatorMedia){
                
                $remotePath = parse_url($operatorMedia->getRemotePath());
                $remotePathInfo = pathinfo($remotePath['path']);

//                TryAgain:
                    
                echo "\n ";
                
                echo "\033[32m Downloading Media: " . $remotePathInfo['basename'] . " \033[37m";
                
                try 
                {
                    $localFile = $this->kernel->getRootDir().'/../web/uploads/tmp/'.$remotePathInfo['basename'];
                    echo "Local File: $localFile";
                    copy($operatorMedia->getRemotePath(), $localFile);
                
                }
                catch (\Exception $e)
                {
                    echo 'Download failed with message: ' . $e->getMessage() . '. Trying again...';
//                    goto TryAgain;
                    continue;
                }
                
                $mediaItem = new Media();
                $mediaItem->setBinaryContent($localFile);
                $mediaItem->setProviderReference($remotePathInfo['basename']);
                $mediaItem->setProviderMetadata(array('filename'=>$remotePathInfo['basename']));

                $mediaItem->setName($operatorMedia->getMultimediaId());
                $mediaItem->setDescription($operatorMedia->getAltText());
                $mediaItem->setAuthorName('ATDW');
                $mediaItem->setContext('default');
                
                if(null === $operatorMedia->getHeight()){
                    $height = $operatorMedia->getMediaOrientation()=='LARGELAND'?600:800;
                    $width = $operatorMedia->getMediaOrientation()=='LARGELAND'?800:600;
                }
                else {
                    $height = $operatorMedia->getHeight();
                    $width = $operatorMedia->getWidth();
                }
                
                $mediaItem->setHeight($height);
                $mediaItem->setWidth($width);
                $mediaItem->setContentType('image/jpeg');
                $mediaItem->setProviderStatus(Media::STATUS_OK);
                $mediaItem->setEnabled(true);
                $mediaItem->setCreatedAt(new \Datetime());
                $mediaItem->setUpdatedAt(new \Datetime());
                $mediaItem->setProviderName('sonata.media.provider.image');
 
                $operatorMedia->setMediaItem($mediaItem);
                                              
            }
            
            unset($operatorMedia);
            
            $entity->setMedia($newMedia);
        }
    }
}

