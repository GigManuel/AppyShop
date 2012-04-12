<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProjectNote extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('note')
            // ->add('created')
            // ->add('updated')
            // ->add('author')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_projecttype';
    }
}
