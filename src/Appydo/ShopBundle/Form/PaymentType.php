<?php

namespace Appydo\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('created')
            ->add('updated')
            ->add('hide')
            ->add('product')
            ->add('author')
        ;
    }

    public function getName()
    {
        return 'appydo_shopbundle_paymenttype';
    }
}
