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
        $classifications = isset($filters['classifications'])?$filters['classifications']:array();
        $dates = $filters['dates'];
        $dates_ary = explode(' to ',$dates);
        
        $date_from =  strtotime(str_replace('/','-',$dates_ary[0]));
        $date_to =  strtotime(str_replace('/','-',$dates_ary[1]));
        
        $datediff = $date_to - $date_from;
        $days = floor($datediff/(60*60*24));
//        if($days == 0)
//            $days = 1;
//        echo $days;
        for($i = 0; $i<= $days ;$i++)
        {
            
            $date = date('Y-m-d', strtotime('+ '.$i.' day',$date_from));
            
            $qry = "SELECT accommodation_room_id as room_id FROM  `AccommodationRoomCalendar` ARC WHERE  `date`  =   '".$date."' and `available` = 0";
            $bookedStmt = $em->getConnection()->prepare($qry);
            $bookedStmt->execute();
            $bookedRoomIdsRes = $bookedStmt->fetchAll();
            
            $bookedRoomIds = array();
            foreach($bookedRoomIdsRes as $room)
            {
                $bookedRoomIds[] = $room['room_id'];
            }
            
            if(isset($room_ids))
            {
                $room_ids = array_intersect($room_ids, $bookedRoomIds);
            }else{
                $room_ids = $bookedRoomIds;
            }
        }
        
      
        
        $accom = array();
        
        if(isset($room_ids) && count($room_ids) > 0)
        {
            $roomIds = implode(',', $room_ids);


            $accomedationIdsQry = "SELECT accommodation_id  FROM  `AccommodationRoom` AR WHERE  id NOT IN ($roomIds) GROUP BY accommodation_id";
            $accomedationIdsStmt = $em->getConnection()->prepare($accomedationIdsQry);
            $accomedationIdsStmt->execute();
            $accomedationIds = $accomedationIdsStmt->fetchAll();

            foreach($accomedationIds as $accomedation)
            {
                $accom[] = $accomedation['accommodation_id'];
            }
        }
        $where = "";
        if(count($accom) > 0 )
        {
            $accomIds = implode(',', $accom);
             $where = "WHERE `ac`.`id` IN ($accomIds)";
        }
        
        
        if($filters['gridView'] == 'false')
        {
       $distanceQueryAccomm  =<<<EOD
        SELECT * FROM 
        (
        SELECT `ac`.`id` as `id`, `ac`.`name` as `name`,  `ac`.`atdw_product_description` as `description`, `ac`.`atdw_city_name` as `destination`, `ac`.`latitude` as `latitude`, `ac`.`longitude` as `longitude`, `ac`.`atdw_rate_from` as `min_rate`,
        (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance` FROM `Accommodation` ac 
               $where
        ) as op           
        HAVING distance < $distanceValue AND min_rate < $maxRate
        ORDER BY distance ASC
EOD;
        }else{
       $distanceQueryAccomm  = <<<EOD
               SELECT * FROM ( 
                    SELECT `ac`.`id` as `id`, `ac`.`name` as `name`, 
                    `ac`.`atdw_city_name` as `destination`, `ac`.`latitude` as `latitude`, `ac`.`longitude` as `longitude`, 
                   `ac`.`atdw_rate_from` as `min_rate`, 
                    (((acos(sin(($destination[latitude]*pi()/180)) * sin((`latitude`*pi()/180))+cos(($destination[latitude]*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($destination[longitude] - `longitude`)*pi()/180))))*180/pi())*60*1.1515) AS `distance`,
                    AR.id as room_id, AR.name as room_name
                    FROM `Accommodation` ac
                    INNER JOIN AccommodationRoom AR ON AR.accommodation_id = ac.id 
                    
                    $where
                   ) as op
            HAVING 
               distance < $distanceValue AND 
               min_rate < $maxRate 
               ORDER BY distance ASC
EOD;
        }
             
        
        $accStmt = $em->getConnection()->prepare($distanceQueryAccomm);

        $accStmt->execute();
        
        $results = $accStmt->fetchAll();
        if($filters['gridView'] == 'true')
        {
            

            $roomary = array();
            /**
             * 
             * 
             * 
             * Big Fix Needed
             * 
             * 
             * 
             * 
             */
            for($i = 0; $i<= $days ;$i++)
            {
                $date = date('Y-m-d', strtotime('+ '.$i.' day',$date_from));
                $qry = "SELECT * FROM  `AccommodationRoomCalendar` ARC WHERE  `date` = '".$date."'";
                $roomStmt = $em->getConnection()->prepare($qry);
                $roomStmt->execute();
                $room_calendars = $roomStmt->fetchAll();
                foreach($room_calendars as $room_calendar)
                {   
                    $roomary[$room_calendar['accommodation_room_id']][$date]['rate']= $room_calendar['rate'];
                    $roomary[$room_calendar['accommodation_room_id']][$date]['available']= ($room_calendar['available'] == 1)?"true":"false";
                }
            }
        }
        
        foreach ($results as $result)
        {

            $operator = $em->getRepository('TNEOperatorBundle:Accommodation')->find($result['id']);
            
            if($filters['gridView'] == 'true')
            {
                for($i = 0; $i<= $days ;$i++)
                {
                    $date = date('Y-m-d', strtotime('+ '.$i.' day',$date_from));
                    
                    if(isset($roomary[$result['room_id']]))
                    {
                        if(isset($roomary[$result['room_id']][$date]))
                        {
                            $result[$date]['rate'] = $roomary[$result['room_id']][$date]['rate'];
                            $result[$date]['available'] = $roomary[$result['room_id']][$date]['available'];   
                        }
                        else
                        {
                            $result[$date]['rate'] = '0';
                            $result[$date]['available'] = 'true';
                        }
                    }
                    else
                    {
                        $result[$date]['rate'] = '0';
                        $result[$date]['available'] = 'true';
                    }
                }
            }
            
            
            $operatorMedia = $operator->getMedia()->first();
            $result['image'] = $this->getOperatorImage($operatorMedia);
            $result['rating'] = $operator->getRating();
            $result['tags'] = array();
            $result['min_rate'] = number_format($result['min_rate'], 0);

            if($operator->getClassifications()->count() > 0) $result['type'] = $operator->getClassifications()->first()->getName();
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
        if($mediaItem === null) return '/uploads/media/noimg.gif';
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
    
    public function tripadvisorAction()
    {   
        $url = 'http://api.tripadvisor.com/api/partner/1.0/location/89575?key=b8e9b5af-ac5c-4193-bda5-f013fae5f050';
        $results =  file_get_contents($url);
        $data = json_decode($results, TRUE);
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
        return $this->render('TNEOperatorBundle:Default:tripadvisor.html.twig', array(
            'results'     => $data
        ));
    }
    
    public function operatorDetailsAction($id)
    {   
        $em = $this->get('doctrine.orm.entity_manager');
        $operator = $em->getRepository('TNEOperatorBundle:Accommodation')->find($id);
       
        return $this->render('TNEOperatorBundle:Default:operatorDetails.html.twig', array(
            'operator'     => $operator
        ));

    }
    
    public function roomAvailabilityAction()
    {
        $id =$this->getRequest()->get('id');
         $distanceQueryAccomm  = <<<EOD

                    SELECT `ac`.`id` as `id`, `ac`.`name` as `name`, 
                    `ac`.`atdw_city_name` as `destination`, `ac`.`atdw_rate_from` as `min_rate`, AR.id as room_id, AR.name as room_name
                    FROM `Accommodation` ac INNER JOIN AccommodationRoom AR ON AR.accommodation_id = ac.id 
                   where ac.id = $id
EOD;
        
             
        $em =$this->getDoctrine()->getEntityManager();
        $accStmt = $em->getConnection()->prepare($distanceQueryAccomm);

        $accStmt->execute();
        
        $results = $accStmt->fetchAll();
        $roomary = array();

        for ($i = 0; $i <= 10; $i++) {
            $date = date('Y-m-d', strtotime('+ ' . $i . ' day', time()));
            $qry = "SELECT * FROM  `AccommodationRoomCalendar` ARC WHERE  `date` = '" . $date . "'";
            $roomStmt = $em->getConnection()->prepare($qry);
            $roomStmt->execute();
            $room_calendars = $roomStmt->fetchAll();
            foreach ($room_calendars as $room_calendar) {
                $roomary[$room_calendar['accommodation_room_id']][$date]['rate'] = $room_calendar['rate'];
                $roomary[$room_calendar['accommodation_room_id']][$date]['available'] = ($room_calendar['available'] == 1) ? "true" : "false";
            }
        }
        
        
        foreach ($results as $result)
        {
            $operator = $em->getRepository('TNEOperatorBundle:Accommodation')->find($result['id']);
            for($i = 0; $i<= 10 ;$i++)
            {
                $date = date('Y-m-d', strtotime('+ '.$i.' day',time()));

                if(isset($roomary[$result['room_id']]))
                {
                    if(isset($roomary[$result['room_id']][$date]))
                    {
                        $result[$date]['rate'] = ($roomary[$result['room_id']][$date]['rate'] == 0)?$operator->getAtdwRateFrom():$roomary[$result['room_id']][$date]['rate'];
                        $result[$date]['available'] = ($roomary[$result['room_id']][$date]['available'] == "1")?"true":"false";   
                    }
                    else
                    {
                        $result[$date]['rate'] = $operator->getAtdwRateFrom();
                        $result[$date]['available'] = 'true';
                    }
                }
                else
                {
                    $result[$date]['rate'] = $operator->getAtdwRateFrom();
                    $result[$date]['available'] = 'true';
                }
            }
                $operators []= $result;
        }
        return new JsonResponse($operators);
    }
}
