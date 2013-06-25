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
use Sonata\AdminBundle\Show\ShowMapper;

class CustomerAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('company')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('mobile')
            ->add('guest', new GuestType())
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('company')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('mobile')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('company')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('mobile')
            ->add('guest.firstname')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array()),
            ));
    }

    protected function configureShowField(ShowMapper $show)
    {
        $show
            ->add('company')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('mobile')
            ->add('guest.firstname')
            ->add('guest.lastname')
//            ->add('bookings')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array()),
            ));
    }
}