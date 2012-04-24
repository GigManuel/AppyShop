<?php

namespace Appydo\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
			->add('file')
            ->add('hide')
        ;
    }

    public function getName()
    {
        return 'appydo_shopbundle_categorytype';
    }
    
}
