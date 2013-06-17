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

class AccommodationCalendarAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('date')
                ->add('rate')
                ->add('available')
                ->add('room')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('date')
                ->add('rate')
                ->add('available')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->add('date')
                ->add('rate')
                ->add('available')
        ;
    }
}