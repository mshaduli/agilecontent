<?php

namespace TNE\OperatorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Description of MapType
 *
 * @author zuhairnaqvi
 */
class MapType extends AbstractType {
    public function getDefaultOptions(array $options)
    {
        return array(
            'location' => array(
                'latitude' => '',
                'longitude' => '',
            ),
        );
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'map';
    }
}

?>
