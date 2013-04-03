<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class State {    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution[1]/product_address/row/state_name';
    }

    public static function getCommandType(){
        return 'set1';
    }    
}

