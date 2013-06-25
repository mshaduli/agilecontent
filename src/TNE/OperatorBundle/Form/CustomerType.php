<?php

namespace TNE\OperatorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company')
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('mobile')
//            ->add('guest')
//            ->add('payment')
            ->add('guest', new GuestType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TNE\OperatorBundle\Entity\Customer'
        ));
    }

    public function getName()
    {
        return 'tne_operatorbundle_customertype';
    }
}
