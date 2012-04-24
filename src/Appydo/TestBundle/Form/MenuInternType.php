<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MenuInternType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('position')
            ->add('hide')
            ->add('topic')
            ->add('menu')
            ->add('author')
            ->add('project')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_menuinterntype';
    }
}
