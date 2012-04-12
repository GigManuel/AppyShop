<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('hide')
            ->add('menu', null ,array(
                    'required' => false,
                ))
            // ->add('created')
            // ->add('updated')
            // ->add('author')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_menutype';
    }
   
}
