<?php

namespace Application\Sonata\PageBundle\Model;

use Sonata\PageBundle\Model\PageBlockInterface;
use Sonata\PageBundle\Model\SnapshotPageProxy as BaseProxy;

/**
 * SnapshotProxyExtend
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class SnapshotPageProxy extends BaseProxy
{
    public function getBodyCopy()
    {
       return $this->getPage()->getBodyCopy();
    } 
    
    public function getTaggedMedia()
    {
        return $this->getPage()->getTaggedMedia();
    }
    
    public function getTags()
    {
        return $this->getPage()->getTags();
    }
}