<?php

namespace TNE\OperatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Doctrine\ORM\Query;

class DefaultController extends Controller
{
    public function searchAction()
    {
        return $this->render('TNEOperatorBundle:Default:index.html.twig', array('name' => 'test'));
    }
    
    public function indexAction()
    {
        $accArray = array();
        $em = $this->get('doctrine.orm.entity_manager');
        $acc = $em->createQuery(
            'SELECT a FROM TNEOperatorBundle:Accommodation a WHERE a.destination = :destination'
        )->setParameter('destination', 'Beechworth')
        ->getResult();
        
        $imageProvider = $this->get('sonata.media.provider.image');
        

        foreach ($acc as $a) {            
            $accArray []= array('name'=> $a->getName(), 'description' => $a->getDescription(), 'image' => $this->imageName($a->getHeroImage(), $imageProvider));           
        }

        return new JsonResponse($accArray);
    }
    
    private function imageName($media, $provider){
        
        if(!$media) return 'none.jpg';
        $format = $provider->getFormatName($media, 'small');
      
        return $this->get('sonata.media.twig.extension')->path($media, $format);
              
    }
}
