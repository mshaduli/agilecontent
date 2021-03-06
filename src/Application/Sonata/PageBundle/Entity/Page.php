<?php
/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Entity;

use Sonata\PageBundle\Entity\BasePage as BasePage;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class Page extends BasePage
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
     *
     * @var type bodyCopy
     */
    protected $bodyCopy;

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
    
    public function getBodyCopy()
    {
        return $this->bodyCopy;
    }    
    
    public function setBodyCopy($bodyCopy)
    {
        $this->bodyCopy = $bodyCopy;
    }
    
    public function getTaggedMedia()
    {   
        $taggedMedia = array();
        foreach ($this->getTags() as $tag){
                $taggedMedia = array_merge($taggedMedia, $tag->getMedia()->toArray());
        }
        
        return array_unique($taggedMedia);
    }
    
    public function getTaggedOperators()
    {   
        $taggedOperators = array();
        foreach ($this->getTags() as $tag){
            $taggedOperators = array_merge($taggedOperators, $tag->getAccommodation()->toArray());
        }
        return array_unique($taggedOperators);
    }
    
}