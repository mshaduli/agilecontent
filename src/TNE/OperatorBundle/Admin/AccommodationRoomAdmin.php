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

class AccommodationRoomAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('name', 'text', array('attr'=>array('class'=>'span12')))
                ->add('description', 'textarea', array(
                        'attr' => array(
                            'class' => 'tinymce',
                            'data-theme' => 'advanced'
                        ),
                        'required' => false
                    ))
                ->add('rateFrom', 'text', array('attr'=>array('class'=>'span5')))
                ->add('rateTo', 'text', array('attr'=>array('class'=>'span5')))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('name')
                ->add('description')
                ->add('rateFrom')
                ->add('rateTo')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('name')
                ->add('description')
                ->add('rateFrom')
                ->add('rateTo')
        ;
    }
}