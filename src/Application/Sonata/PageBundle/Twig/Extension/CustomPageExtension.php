<?php

/*
 * This file is part of sonata-project.
 *
 * (c) 2010 Thomas Rabaix
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Twig\Extension;

use Sonata\PageBundle\Model\PageBlockInterface;
use Sonata\BlockBundle\Block\BlockServiceManagerInterface;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\PageBundle\CmsManager\CmsManagerSelectorInterface;
use Sonata\PageBundle\Site\SiteSelectorInterface;
use Sonata\PageBundle\Exception\PageNotFoundException;
use Sonata\PageBundle\Model\SnapshotPageProxy;

use Symfony\Component\Routing\RouterInterface;

/**
 * PageExtension
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class CustomPageExtension extends \Twig_Extension
{
    /**
     * @var CmsManagerSelectorInterface
     */
    private $cmsManagerSelector;

    /**
     * @var SiteSelectorInterface
     */
    private $siteSelector;

    /**
     * @var array
     */
    private $resources;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;
//
//    /**
//     * Constructor
//     *
//     * @param CmsManagerSelectorInterface $cmsManagerSelector A CMS manager selector
//     * @param SiteSelectorInterface       $siteSelector       A site selector
//     * @param RouterInterface             $router             The Router
//     */
    private $em, $blockmanager;
    public function __construct( \Sonata\BlockBundle\Model\BlockManagerInterface $blockmanager)
    {
        
        $this->blockmanager       = $blockmanager;
//        $this->router             = $router;
    }
//   
    public function getFilters()
    {
        return array(
            'sonata_page_render_custome_block'        => new \Twig_Filter_Method($this, 'customeBlockRender'),
        );
    }
    
    public function customeBlockRender($container, $services){ 
        $container_html = $container;
        $p1 = strpos($container, '"');
        $p2 = strpos($container, '"',$p1+1);
        $container = substr($container, $p1+1, ($p2-$p1)-1);
        $container_id = str_replace('cms-block-', '', $container);
        
        $block = $this->blockmanager->findOneBy(array( 'id'=>$container_id, 'type'=>'sonata.page.block.container'));
//        $block = $this->blockmanager->findOneBy(array( 'name'=>$name, 'type'=>$service));
//        echo 'aaa'.$block->getId(); exit;
        $tobecreated = array();
        $flag = true;
        
        foreach($services as $key=> $service){
            $flag = true;
            foreach ($block->getChildren() as $child){
                if($service == $child->getType()){
                    $flag = false;
                }
            }
            if($flag)
                $tobecreated[$key] = $service;
        }
//        echo count($block->getChildren()).':';
//        print_r($services);
//        print_r($tobecreated);exit;
        foreach($tobecreated as $key=>$value){
            $newblock = $this->blockmanager->create();
            
            $newblock->setName($key);
            $newblock->setType($value);
            $newblock->setPage($block->getPage());
            $newblock->setParent($block);
             if($value == 'sonata.block.service.text')
                $newblock->setSettings(array("content" => "Insert your content here"));
             elseif($value == 'sonata.media.block.feature_media')
                $newblock->setSettings(array("media" => false,"orientation" => "left","title" => null,"content" => null,"context" => "default","format" => "default_big","mediaId" => null));
            $newblock->setEnabled(1);
            $newblock->setPosition(1);
            $this->blockmanager->save($newblock);
            
            
        }
        echo $container_html;
//        $loader = new \Twig_Loader_String();
//        $twig = new \Twig_Environment($loader);
//
//        echo $twig->render($container_html);
    }
    
    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_custom_page';
    }
//
//    /**
//     * @param string $template
//     * @param array  $parameters
//     *
//     * @return string
//     */
//    private function render($template, array $parameters = array())
//    {
//        if (!isset($this->resources[$template])) {
//            $this->resources[$template] = $this->environment->loadTemplate($template);
//        }
//
//        return $this->resources[$template]->render($parameters);
//    }
//
//    /**
//     * @param string $name
//     * @param null   $page
//     * @param bool   $useCache
//     *
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function renderContainer($name, $page = null, $useCache = true)
//    {
//        $cms        = $this->cmsManagerSelector->retrieve();
//        $site       = $this->siteSelector->retrieve();
//        $targetPage = false;
//
//        try {
//            if ($page === null) {
//                $targetPage = $cms->getCurrentPage();
//            } else {
//                if (!$page instanceof PageInterface && is_string($page)) {
//                    $targetPage = $cms->getInternalRoute($site, $page);
//                } else {
//                    if ($page instanceof PageInterface) {
//                        $targetPage = $page;
//                    }
//                }
//            }
//        } catch (PageNotFoundException $e) {
//            // the snapshot does not exist
//            $targetPage = false;
//        }
//
//        if (!$targetPage) {
//            return "";
//        }
//
//        $container = $cms->findContainer($name, $targetPage);
//
//        if (!$container) {
//            return "";
//        }
//
//        return $this->renderBlock($container, $useCache);
//    }
    
    
    
}