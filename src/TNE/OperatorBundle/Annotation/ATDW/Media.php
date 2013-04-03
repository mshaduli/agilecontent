<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class Media {
    
    public static function getXpathString(){
        return '/atdw_data_results/product_distribution/product_multimedia/row[attribute_id_multimedia_content="FULLIMAGE"]';
    }
    
    public static function getCommandType(){
        return 'set2';
    }
    
    public static function getAddMethodName(){
        return 'addMedia';
    }
    
    public static function getObjectClassName()
    {
        return 'TNE\OperatorBundle\Entity\OperatorMedia';
    }
}