<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace TNE\OperatorBundle\Admin;

use TNE\OperatorBundle\Admin\OwnerAwareAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AccommodationAdmin extends OwnerAwareAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('name')
                ->add('destination')
                ->add('site')
                ->add('description', 'textarea', array(
                        'attr' => array(
                            'class' => 'tinymce span6',
                            'data-theme' => 'advanced'
                        )
                    ))
                ->add('address')
                ->add('latitude')
                ->add('longitude')
                ->add('classifications', null, array('required' => false))
                ->add('tags', null, array('required' => false))
                ->add('selfRating', null, array('label'=>'Self Rating'))
                ->add('starRating', null, array('label'=>'Star Rating'))
                ->add('hiddenSecret', 'textarea', array(
                        'required' => false,
                        'attr' => array(
                            'class' => 'tinymce span6',
                            'data-theme' => 'advanced'
                        )
                    ))
                ->add('tripadvisorKey', null, array('label'=>'Tripadvisor Key','required' => false))
                ->add('termsAndConditions', 'textarea', array(
                        'attr' => array(
                            'class' => 'tinymce span6',
                            'data-theme' => 'advanced'
                        ),
                        'required' => false
                    ))                
            ->end()
            ->with('Contact', array('collapsed' => true))
                ->add('atdwContactEmail', null, array('label'=>'Email'))
                ->add('atdwContactPhone', null, array('label'=>'Phone'))
                ->add('atdwContactMobile', null, array('label'=>'Mobile'))
                ->add('atdwContactUrl', null, array('label'=>'URL'))
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
            ->with('Rooms', array('collapsed' => true))
                ->add('rooms', 'sonata_type_collection', array(
                        'required' => false,
                        'by_reference' => false,
                        'label' => 'Rooms List'
                    ), array(
                        'edit' => 'inline',
                        'inline' => 'table'
                    )
                )
            ->end()
            ->with('Recommendations', array('collapsed' => true))
                ->add('recommendations', 'sonata_type_collection', array(
                        'required' => false,
                        'by_reference' => false,
                        'label' => 'Recommendation List'
                    ), array(
                        'edit' => 'inline',
                        'inline' => 'table',
                    )
                )
            ->end()                
            ->with('Social Media', array('collapsed' => true))
                ->add('facebookUrl')
                ->add('twitterUrl')
                ->add('tripadvisorUrl')
            ->end()                
            ->with('TXA Settings', array('collapsed' => true))
            ->end()                               
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('destination')
            ->add('tags')
            ->add('classifications')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('destination')
            ->add('classifications')
            ->add('atdwStarRating', null, array('label'=>'Star Rating'))
            ->add('roomCount', null, array('label'=>'Rooms'))
            ->add('latitude')
            ->add('longitude')
        ;
    }
}