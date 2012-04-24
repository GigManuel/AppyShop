<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('username')
            //->add('salt')
            ->add('password')
            // ->add('confirmPassword')
            ->add('current')
            //->add('isActive')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_usertype';
    }
}
