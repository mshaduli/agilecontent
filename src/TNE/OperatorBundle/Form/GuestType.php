<?php

namespace TNE\OperatorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('mobile')
            ->add('address_one')
            ->add('address_two')
            ->add('city')
            ->add('postcode')
            ->add('state')
            ->add('country')
            ->add('comments')
            ->add('customer')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TNE\OperatorBundle\Entity\Guest'
        ));
    }

    public function getName()
    {
        return 'tne_operatorbundle_guesttype';
    }
}
