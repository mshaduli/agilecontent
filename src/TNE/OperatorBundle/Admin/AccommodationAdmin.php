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
            ->with('Contact')
                ->add('atdwContactEmail', null, array('label'=>'Email'))
                ->add('atdwContactPhone', null, array('label'=>'Phone'))
                ->add('atdwContactMobile', null, array('label'=>'Mobile'))
                ->add('atdwContactUrl', null, array('label'=>'URL'))
            ->end()
            ->with('Media')
                 ->add('gallery', 'sonata_type_model_list', array('required' => false), array('link_parameters' => array('context' => 'default')))
            ->end()
            ->with('Rooms')
                ->add('rooms', 'sonata_type_collection', array(
                        'required' => false,
                        'by_reference' => false,
                        'label' => 'Rooms List'
                    ), array(
                        'edit' => 'inline',
                        'inline' => 'table',
//                        'sortable'  => 'position',
//                        'link_parameters' => array('context' => 'default'),
//                        'help' => 'Optionally add or select media items for the story text.'
                    )
                )
            ->end()                
            ->with('Social Media')
                ->add('facebookUrl')
                ->add('twitterUrl')
                ->add('tripadvisorUrl')
            ->end()                
            ->with('TXA Settings')
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
            ->add('atdwStarRating', null, array('label'=>'Star Rating'))
            ->add('roomCount', null, array('label'=>'Rooms'))
            ->add('latitude')
            ->add('longitude')
        ;
    }
}