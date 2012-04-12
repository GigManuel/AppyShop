<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url')
            ->add('type')
            ->add('hide')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_servicetype';
    }
}
