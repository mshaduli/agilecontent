<?php

namespace TNE\OperatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Doctrine\ORM\Query;

/**
 * fixme - Reduce code duplication
 */

class DefaultController extends Controller
{
    public function searchAction()
    {
        return $this->render('TNEOperatorBundle:Default:search.html.twig');
    }

    /**
     * 
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @abstract
     * 
     * For all types in one query
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
        $operators = array();
        
        $em = $this->get('doctrine.orm.entity_manager');
        $filters = $this->fromRequestOrDb($this->getRequest()->query->all(), $em);        
                
        $destination = $em->createQuery('SELECT d FROM TNEOperatorBundle:Destination d WHERE d.id = :destination')->setParameter('destination', $filters['destination'])->getSingleResult(Query::HYDRATE_ARRAY);   
        
        $distanceValue = \str_replace('km', '', $filters['distance']);
        $maxRate = \str_replace('$', '', $filters['price']);
        $rating = \str_replace('$', '', $filters['rating']);
        $classifications = $filters['classifications'];

        
        $distanceQueryAccomm  =<<<EOD
        SELECT * FROM 
        (
        SELECT `ac`.`id` as `id`, `ac`.`name` as `name`,  `ac`.`atdw_product_description` as `description`, `ac`.`atdw_city_name` as `destination`, `ac`.`latitude` as `latitude`, `ac`.`longitude` as `longitude`,
        (SELECT MIN(rate_from) FROM AccommodationRoom r WHERE `accommodation_id` = `ac`.`id`) as `min_rate`,
        (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Accommodation` ac 
        ) as op           
        HAVING distance < $distanceValue AND min_rate < $maxRate
        ORDER BY distance ASC
EOD;
        
        $accStmt = $em->getConnection()->prepare($distanceQueryAccomm);
        
        $accStmt->execute();
        
        $results = $accStmt->fetchAll();
        
        foreach ($results as $result)
        {
            
            $operator = $em->getRepository('TNEOperatorBundle:Accommodation')->find($result['id']);
                        
            $operatorMedia = $operator->getMedia()->first();
            $result['image'] = $this->getOperatorImage($operatorMedia);
            $result['rating'] = $operator->getRating();            
            $result['tags'] = [];
            $result['type'] = $operator->getClassifications()->first()->getName();
            foreach($operator->getTags() as $tag)
            {
                $result['tags'] []= $tag->getSingleName();
            }


            if($result['rating'] >= $rating && $this->operatorIsClassified($operator, $classifications))
            {
                $operators []= $result;
            }

        }
  
        return new JsonResponse($operators);
    }

    private function operatorIsClassified($operator, $classifications)
    {
        foreach($operator->getClassifications() as $classification)
        {
            if(in_array($classification->getKeyStr(), $classifications))
            {
                return true;
            }
        }
        return false;
    }
    
    public function attractionsAction()
    {
        $operators = array();
        $em = $this->get('doctrine.orm.entity_manager');
        $filters = $this->fromRequestOrDb($this->getRequest()->query->all(), $em);        
                
        $destination = $em->createQuery('SELECT d FROM TNEOperatorBundle:Destination d WHERE d.id = :destination')->setParameter('destination', $filters['destination'])->getSingleResult(Query::HYDRATE_ARRAY);   
        
        $distanceValue = \str_replace('km', '', $filters['distance']);
        
        
        $distanceQueryAttr  =<<<EOD
        SELECT * FROM 
        (
        SELECT `att`.`id` as `id`, `att`.`name` as `name`,  `att`.`description` as `description`, `att`.`latitude` as `latitude`, `att`.`longitude` as `longitude`, (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Attraction` att
        ) as op 
        HAVING distance < $distanceValue
        ORDER BY distance ASC
EOD;
        
        $attStmt = $em->getConnection()->prepare($distanceQueryAttr);
        $attStmt->execute();        
        
        $results = $attStmt->fetchAll();
        
        foreach ($results as $result)
        {
            $operatorMedia =  $em->getRepository('TNEOperatorBundle:Attraction')->find($result['id'])->getMedia()->first();
            $result['image'] = $this->getOperatorImage($operatorMedia);
            $operators []= $result;
        }
  
        return new JsonResponse($operators);
    }
    
    public function eventsAction()
    {
        $operators = array();
        $em = $this->get('doctrine.orm.entity_manager');
        $filters = $this->fromRequestOrDb($this->getRequest()->query->all(), $em);        
                
        $destination = $em->createQuery('SELECT d FROM TNEOperatorBundle:Destination d WHERE d.id = :destination')->setParameter('destination', $filters['destination'])->getSingleResult(Query::HYDRATE_ARRAY);   
        
        $distanceValue = \str_replace('km', '', $filters['distance']);
        
        
        $distanceQueryEvent  =<<<EOD
        SELECT * FROM 
        (
        SELECT `ev`.`id` as `id`, `ev`.`name` as `name`,  `ev`.`description` as `description`, `ev`.`latitude` as `latitude`, `ev`.`longitude` as `longitude`, (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Event` ev
        ) as op 
        HAVING distance < $distanceValue
        ORDER BY distance ASC
EOD;
        
        $evStmt = $em->getConnection()->prepare($distanceQueryEvent);
        $evStmt->execute();
        
        $results = $evStmt->fetchAll();
        
        
        foreach ($results as $result)
        {
            $operatorMedia =  $em->getRepository('TNEOperatorBundle:Event')->find($result['id'])->getMedia()->first();
            $result['image'] = $this->getOperatorImage($operatorMedia);
            $operators []= $result;
        }
  
        return new JsonResponse($operators);
    }    
    
    private function getOperatorImage($media)
    {
        if(!$media) return '/uploads/media/noimg.gif';
        $mediaItem = $media->getMediaItem();        
        $imageProvider = $this->get('sonata.media.provider.image');                
        $format = $imageProvider->getFormatName($mediaItem, 'big');      
        return $this->get('sonata.media.twig.extension')->path($mediaItem, $format);              
    }
    
    private function fromRequestOrDb($filters, $em)
    {
        
        if(!isset($filters['destination']))
        {
            $destinations = $em->createQuery(
                'SELECT d.id, d.name, d.latitude, d.longitude FROM TNEOperatorBundle:Destination d'
            )->getResult(Query::HYDRATE_ARRAY);
            
            $filters['destination'] = $destinations[0]['id'];
        }
        if(!isset($filters['distance'])) $filters['distance'] = '10';
        if(!isset($filters['price'])) $filters['price'] = '1000';
        if(!isset($filters['rating']) || $filters['rating'] == 'null') $filters['rating'] = 0;
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

    public function classificationsAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $d = $em->createQuery(
            'SELECT c.id, c.name, c.keyStr FROM TNEOperatorBundle:AccommodationClassification c'
        )
            ->getResult(Query::HYDRATE_ARRAY);

        return new JsonResponse($d);
    }
}
