<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class Rooms {
    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution/product_service/row';
    }
    
    public static function getCommandType(){
        return 'set2';
    }
    
    public static function getAddMethodName(){
        return 'addRoom';
    }
    
    public static function getObjectClassName()
    {
        return 'TNE\OperatorBundle\Entity\AccommodationRoom';
    }
}