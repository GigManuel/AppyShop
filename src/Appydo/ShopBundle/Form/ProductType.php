<?php

namespace Appydo\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('category')
            ->add('name')
            ->add('description')
            ->add('price')
			->add('file')
            ->add('hide')
        ;
    }

    public function getName()
    {
        return 'appydo_shopbundle_producttype';
    }
}
