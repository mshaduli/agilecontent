<?php
/**
 *
 * User: Muhammmadali Shaduli
 * Date: 22/06/13
 * Time: 6:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace TNE\OperatorBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use TNE\OperatorBundle\Admin\Filter\OwnerFilter;

class OwnerAwareAdmin extends Admin
{

    private $ownerFilter;

    public function setOwnerFilter(OwnerFilter $filter){
        $this->ownerFilter = $filter;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $this->ownerFilter->apply($query);
        return $query;
    }

    public function isGranted($name, $object = null)
    {
        if(!is_null($object))
        {
            if($this->ownerFilter->isOwner($object))
            {
                return parent::isGranted($name, $object);
            }
            else
            {
                return false;
            }
        }
        return parent::isGranted($name, $object);
    }

}