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
        $tagItem = array();
        
        if($this->getTags())
        {
            foreach ($this->getTags() as $tag)
            {
                $tagItem[] = $tag;
            }
            
            return $tagItem;
        }
    }
    
    public function getTaggedOperators()
    {
        $tagItem = array();
        
        if($this->getTags())
        {
            foreach ($this->getTags() as $tag)
            {
                $tagItem[] = $tag;
            }
            
            return $tagItem;
        }
    }    
    
    public function getTags()
    {
        return $this->getPage()->getTags();
    }
}