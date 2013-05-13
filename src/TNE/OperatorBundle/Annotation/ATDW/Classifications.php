<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class Classifications {
    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution/product_vertical_classification/row';
    }
    
    public static function getCommandType(){
        return 'set2';
    }
    
    public static function getSetManyToManyMethodName(){
        return 'setAccommodation';
    }
    
    public static function getObjectClassName()
    {
        return 'TNE\OperatorBundle\Entity\AccommodationClassification';
    }

    public static function getRepoName()
    {
        return 'TNEOperatorBundle:AccommodationClassification';
    }
}