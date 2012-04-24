<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserEdit extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('oldPassword','password')
            ->add('newPassword','password')
            ->add('confirmPassword','password')
            ->add('email')
            ->add('current')
            //->add('isActive')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_usertype';
    }
    public function getDefaultOptions(array $options)
    {
        return array(
            'validation_groups' => array('edit')
        );
    }
}
