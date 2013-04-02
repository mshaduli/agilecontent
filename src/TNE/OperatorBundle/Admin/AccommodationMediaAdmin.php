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

class AccommodationMediaAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        //Write transformer for accommodation field
        $formMapper
           ->add('accommodation')
           ->add('mediaItem', 'sonata_type_model_list', array('required' => false), array(
                'link_parameters' => array('provider' => 'sonata.media.provider.image', 'context'  => 'default')
            ))
        ;
    }
    
}