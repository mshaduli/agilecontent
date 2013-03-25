<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class StarRating {
    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution/product_attribute/row[attribute_type_id="RATING AAA"]/attribute_id_description';
    }
    
    public static function getCommandType(){
        return 'set2';
    }
}