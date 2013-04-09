<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace TNE\OperatorBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class OperatorMediaAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        //Write transformer for accommodation field
        
        $object = $this->getSubject();
        
        $formMapper
           ->add('mediaItem', 'operator_media_list', array('required' => false, 'model_manager' => $this->getModelManager(), 'class' => 'Application\Sonata\MediaBundle\Entity\Media',), array(
                'link_parameters' => array('provider' => 'sonata.media.provider.image', 'context'  => 'default')
            ))
        ;
        
        if(method_exists($object, 'getAccommodation') && null != $object->getAccommodation())
        {
            $formMapper->add('accommodation');
        }
        
        if(method_exists($object, 'getEvent') && null != $object->getEvent())
        {
            $formMapper->add('event');
        }        
        
        if(method_exists($object, 'getAttraction') && null != $object->getAttraction())
        {
            $formMapper->add('attraction');
        }                
    }
    
}