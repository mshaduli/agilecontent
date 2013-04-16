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

    /**
     * 
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @abstract
     * For all types
     * $distanceQueryAll =<<<EOD
        SELECT * FROM 
        (
        SELECT 'accommodation' as `type`, `ac`.`name` as `name`,  `ac`.`atdw_product_description` as `description`, `ac`.`latitude` as `latitude`, `ac`.`longitude` as `longitude`, (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Accommodation` ac
        UNION ALL 
        SELECT 'attraction' as `type`, `att`.`name` as `name`,  `att`.`description` as `description`, `att`.`latitude` as `latitude`, `att`.`longitude` as `longitude`, (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Attraction` att
        UNION ALL 
        SELECT 'event' as `type`, `ev`.`name` as `name`,  `ev`.`description` as `description`, `ev`.`latitude` as `latitude`, `ev`.`longitude` as `longitude`, (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Event` ev
        ) as op 
        HAVING distance < $distanceValue
        ORDER BY distance ASC
        EOD;
     * 
     */
    
    
    public function indexAction()
    {     
        $em = $this->get('doctrine.orm.entity_manager');
        $filters = $this->fromRequestOrDb($this->getRequest()->query->all(), $em);        
                
        $destination = $em->createQuery('SELECT d FROM TNEOperatorBundle:Destination d WHERE d.id = :town')->setParameter('town', $filters['town'])->getSingleResult(Query::HYDRATE_ARRAY);   
        
        $distanceValue = \str_replace('km', '', $filters['distance']);
        
        $distanceQueryAccomm  =<<<EOD
        SELECT * FROM 
        (
        SELECT 'accommodation' as `type`, `ac`.`name` as `name`,  `ac`.`atdw_product_description` as `description`, `ac`.`latitude` as `latitude`, `ac`.`longitude` as `longitude`, (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Accommodation` ac
        ) as op 
        HAVING distance < $distanceValue
        ORDER BY distance ASC
EOD;
        
        $accStmt = $em->getConnection()->prepare($distanceQueryAccomm);
        
        $accStmt->execute();
  
        return new JsonResponse($accStmt->fetchAll());
    }
    
    public function attractionsAction()
    {
        
        $em = $this->get('doctrine.orm.entity_manager');
        $filters = $this->fromRequestOrDb($this->getRequest()->query->all(), $em);        
                
        $destination = $em->createQuery('SELECT d FROM TNEOperatorBundle:Destination d WHERE d.id = :town')->setParameter('town', $filters['town'])->getSingleResult(Query::HYDRATE_ARRAY);   
        
        $distanceValue = \str_replace('km', '', $filters['distance']);
        
        
        $distanceQueryAttr  =<<<EOD
        SELECT * FROM 
        (
        SELECT 'attraction' as `type`, `att`.`name` as `name`,  `att`.`description` as `description`, `att`.`latitude` as `latitude`, `att`.`longitude` as `longitude`, (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Attraction` att
        ) as op 
        HAVING distance < $distanceValue
        ORDER BY distance ASC
EOD;
        
        $attStmt = $em->getConnection()->prepare($distanceQueryAttr);
        $attStmt->execute();
        
        return new JsonResponse($attStmt->fetchAll());
    }
    
    public function eventsAction()
    {
        
        $em = $this->get('doctrine.orm.entity_manager');
        $filters = $this->fromRequestOrDb($this->getRequest()->query->all(), $em);        
                
        $destination = $em->createQuery('SELECT d FROM TNEOperatorBundle:Destination d WHERE d.id = :town')->setParameter('town', $filters['town'])->getSingleResult(Query::HYDRATE_ARRAY);   
        
        $distanceValue = \str_replace('km', '', $filters['distance']);
        
        
        $distanceQueryEvent  =<<<EOD
        SELECT * FROM 
        (
        SELECT 'event' as `type`, `ev`.`name` as `name`,  `ev`.`description` as `description`, `ev`.`latitude` as `latitude`, `ev`.`longitude` as `longitude`, (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Event` ev
        ) as op 
        HAVING distance < $distanceValue
        ORDER BY distance ASC
EOD;
        
        $evStmt = $em->getConnection()->prepare($distanceQueryEvent);
        $evStmt->execute();
        
        return new JsonResponse($evStmt->fetchAll());
    }    
    
    private function operatorImageAction(){
        
        $imageProvider = $this->get('sonata.media.provider.image');
        
        if(!$media) return 'none.jpg';
        $format = $provider->getFormatName($media, 'big');
      
        return $this->get('sonata.media.twig.extension')->path($media, $format);
              
    }
    
    private function fromRequestOrDb($filters, $em)
    {
        
        if(!isset($filters['town']))
        {
            $destinations = $em->createQuery(
                'SELECT d.id, d.name, d.latitude, d.longitude FROM TNEOperatorBundle:Destination d'
            )->getResult(Query::HYDRATE_ARRAY);
            
            $filters['town'] = $destinations[0]['id'];
        }
        if(!isset($filters['distance'])) $filters['distance'] = '10';
        
        return $filters;
    }


    public function destinationsAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $d = $em->createQuery(
            'SELECT d.id, d.name, d.latitude, d.longitude FROM TNEOperatorBundle:Destination d'
        )
        ->getResult(Query::HYDRATE_ARRAY);
        
        return new JsonResponse($d);
    }
}
