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

class AccommodationAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Details')
                ->add('name')
                ->add('site')
                ->add('description')
                ->add('address')
                ->add('tags')
                ->add('atdwStarRating', null, array('label'=>'Star Rating'))
            ->end()
            ->with('Media')
            ->end()
            ->with('Rooms')
            ->end()
            ->with('TXA Settings')
            ->end()                
            ->with('Top 5 Recommended Operators')
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
            ->add('latitude')
            ->add('longitude')
        ;
    }
}