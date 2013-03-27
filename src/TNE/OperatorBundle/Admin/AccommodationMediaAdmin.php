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
        $formMapper
                ->add('mediaItem','sonata_media_type', array('provider' => 'sonata.media.provider.image', 'context'  => 'default'), array('label'=>'Image'))
                ->add('alt_text', 'textarea', array('label'=>'Description'))
                ->add('authored_date', 'text', array('label'=>'Authored Date', 'attr'=>array('disabled'=>'disabled')))
        ;
    }
}