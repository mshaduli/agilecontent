<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class CityName {    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution[$index]/product_address/row/city_name';
    }
}

