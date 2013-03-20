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
    public static function getAtdwKey(){
        return '/atdw_data_results/product_distribution/product_record/product_name';
    }
}

