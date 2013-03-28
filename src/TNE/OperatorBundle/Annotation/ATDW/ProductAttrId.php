<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class ProductAttrId {    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution[$index]/product_record/product_id';
    }
    
    public static function getCommandType(){
        return 'set1';
    }    
}

