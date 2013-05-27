<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class EventFrequency {    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution[$index]/product_record/attribute_id_frequency';
    }

    public static function getCommandType(){
        return 'set1';
    }    
}

