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

class AttractionAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('name')
                ->add('tags', null, array('required' => false))
                ->add('description', 'textarea', array(
                        'attr' => array(
                            'class' => 'tinymce span6',
                            'data-theme' => 'advanced'
                        )
                    ))
                ->add('destination')
            ->end()      
            ->with('Media', array('collapsed' => true))
                 //->add('gallery', 'sonata_type_model_list', array('required' => false), array('link_parameters' => array('context' => 'default')))
                ->add('media', 'sonata_type_collection', array(
                        'required' => false,
                        'by_reference' => false,
                        'label' => 'Media List'
                    ), array(
                        'edit' => 'inline',
                        'inline' => 'table'
                    )
                )
            ->end()                    
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('destination')
            ->add('tags')                
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('destination')
            ->add('tags')
        ;
    }
}