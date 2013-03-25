<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class PhysicalAddress {    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution[$index]/product_address/row/address_line_1';
    }
    public static function getCommandType(){
        return 'set1';
    }    
}

