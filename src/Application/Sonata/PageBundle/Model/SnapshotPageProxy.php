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
        $snapshot = $this->manager->findEnableSnapshot(array('pageId'=>$this->getPage()->getId()));
        $tagIds = array();
        $MediaItem = array();
        
        if($this->getTags())
        {
            foreach ($this->getTags() as $tag)
            {   
                $tagIds[] = $tag['id'];
            }
            
            $taggedMedia =  $this->manager->getTaggedMediaSnaps($tagIds, $snapshot, $this->getPage());
            
            foreach($taggedMedia as $snapshots)
            {
                foreach($snapshots->getTags() as $tagItem)
                {
                    foreach($tagItem->getMedia() as $media)
                    {
                        $MediaItem[] = $media;
                    }
                }
            }
            
            return $MediaItem;
        }
    }
    
    public function getTags()
    {
        return $this->getPage()->getTags();
    }
}