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
        $taggedMedia = array();
        foreach ($this->getPage()->getTags() as $tag){
            //$taggedMedia [] = $tag->getMedia();
            //array_merge($taggedMedia, $tag->getMediaList());
            //$taggedMedia [] = $tag->getMedia()->toArray();
            $taggedMedia = array_merge($taggedMedia, $tag->getMedia()->toArray());
        }
        return array_unique($taggedMedia);
    }
    
    public function getTags()
    {
        return $this->getPage()->getTags();
    }
}