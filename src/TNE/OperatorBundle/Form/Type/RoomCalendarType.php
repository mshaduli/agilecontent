<?php

namespace TNE\OperatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Description of MapType
 *
 * @author zuhairnaqvi
 */
class RoomCalendarType extends AbstractType {
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'choices' => array(
//                'm' => 'Male',
//                'f' => 'Female',
//            )
//        ));
//    }

    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'room_calendar_type';
    }
}
