<?php

namespace TNE\OperatorBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;


/**
 * Description of SiteListener
 *
 * @author zuhairnaqvi
 */

class SiteListener
{
    private $driver;
    
    public function __construct($driver)
    {
        $this->driver = $driver;
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if (null != $this->driver->getReader()->getClassAnnotation(new \ReflectionClass(get_class($entity)), 'TNE\\OperatorBundle\\Annotation\\ATDW\\Entity') && method_exists($entity, 'setSite') && method_exists($entity, 'getDestination')) {
            
            $entity->setSite(
                    $entityManager->getRepository('Application\Sonata\PageBundle\Entity\Site')->findOneBy(
                            array('context'=>$entity->getDestination())
                            )
                    );
            
        }
    }
}

