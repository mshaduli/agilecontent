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

class EventAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('name')
                ->add('description', 'textarea', array(
                        'attr' => array(
                            'class' => 'tinymce span6',
                            'data-theme' => 'advanced'
                        )
                    ))
                ->add('startDate')
                ->add('endDate')
                ->add('frequency')
            ->end()
            ->with('Media', array('collapsed' => true))
                 //->add('gallery', 'sonata_type_model_list', array('required' => false), array('link_parameters' => array('context' => 'default')))
                ->add('media', 'sonata_type_collection', array(
                        'required' => false,
                        'by_reference' => false,
                        'label' => 'Media List'
                    ), array(
                        'edit' => 'inline',
                        'inline' => 'table',
//                        'sortable'  => 'position',
//                        'link_parameters' => array('context' => 'default'),
//                        'help' => 'Optionally add or select media items for the story text.'
                    )
                )
            ->end()                
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('frequency')
            ->add('startDate')
            ->add('endDate')           
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('frequency')
            ->add('startDate')
            ->add('endDate')                
        ;
    }
}