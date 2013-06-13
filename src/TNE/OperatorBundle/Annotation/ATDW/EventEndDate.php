<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class EventEndDate {    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution[$index]/product_record/frequency_end_date';
    }

    public static function getCommandType(){
        return 'set1';
    }    
}

