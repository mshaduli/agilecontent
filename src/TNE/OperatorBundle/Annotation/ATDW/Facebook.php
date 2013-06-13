<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class Facebook {
    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution/product_external_system/row[external_system_code="Facebook"]/external_system_text';
    }
    
    public static function getCommandType(){
        return 'set2';
    }
}