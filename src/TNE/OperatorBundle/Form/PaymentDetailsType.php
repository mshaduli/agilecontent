<?php

namespace TNE\OperatorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name_on_card')
            ->add('card_type')
            ->add('card_number')
            ->add('ccv')
            ->add('expiry_month')
            ->add('expiry_year')
//            ->add('customer')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TNE\OperatorBundle\Entity\PaymentDetails'
        ));
    }

    public function getName()
    {
        return 'tne_operatorbundle_paymentdetailstype';
    }
}
