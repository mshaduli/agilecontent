<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class Latitude {    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution[$index]/product_address/row/geocode_gda_latitude';
    }
}

