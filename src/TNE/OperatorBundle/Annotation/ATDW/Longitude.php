<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class Longitude {    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution[$index]/product_address/row/geocode_gda_longitude';
    }
    public static function getCommandType(){
        return 'set1';
    }    
}

