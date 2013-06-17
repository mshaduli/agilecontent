<?php

namespace TNE\OperatorBundle\Annotation\ATDW;

/**
 * Description of Product
 * 
 * @author zuhairnaqvi
 * 
 * @Annotation
 */
class Entity {    
    
    public $type;
 
    public function __construct(array $data)
    {
        $this->type = $data['type'];
    }    
    
    public function getType()
    {
        return $this->type;
    }
}

