<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class ContactMobile {
    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution/product_communication/row[attribute_id_communication_description="Mobile Telephone Enquiries"]/communication_detail';
    }
    
    public static function getCommandType(){
        return 'set2';
    }
}