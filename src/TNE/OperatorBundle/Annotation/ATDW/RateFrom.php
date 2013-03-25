<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class RateFrom {    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution/product_record/product_name';
    }
    public static function getCommandType(){
        return 'set2';
    }    
}

