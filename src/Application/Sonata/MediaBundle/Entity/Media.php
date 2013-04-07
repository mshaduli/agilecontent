<?php
/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class Media extends BaseMedia
{

    /**
     * @var integer $id
     */
    protected $id;
    
    /**
     *
     * @var type ArrayCollection
     */
    protected $tags;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getTags()
    {
        return $this->tags;
    }
    
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
    
    public function getTagLinks()
    {
        $tagLinks = array();
        foreach($this->getTags() as $tag)
        {
            $tagLinks[]=$tag->getName();
        }
        return $tagLinks;
    }
    
    public function getTagIds()
    {
        $tagIds = array();
        foreach($this->getTags() as $tag)
        {
            $tagIds[]=$tag->getId();
        }
        return implode(' ', $tagIds);
    }
    
    public function hasTag($tagName)
    {
        foreach($this->getTags() as $tag)
        {
            if($tag->getTagName() == $tagName) return true;
        }
        
        return false;
    }
}