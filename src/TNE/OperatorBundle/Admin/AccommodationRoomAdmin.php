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
use TNE\OperatorBundle\Form\Type\RoomCalendarType;

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
                 ->add('file', 'file', array(
                            'data_class' => null
                        )
                    )
//               ->add('media', 'sonata_type_collection', array(
//                        'required' => false,
//                        'by_reference' => false,
//                        'label' => 'Media List'
//                    ), array(
//                        'edit' => 'inline',
//                        'inline' => 'table',
//                    )
//                )
//                ->add('rateFrom', 'text', array('attr'=>array('class'=>'span5')))
//                ->add('rateTo', 'text', array('attr'=>array('class'=>'span5')))                                   
                ->add('id', new RoomCalendarType(), array('label'=>'Rates & Availability'))
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