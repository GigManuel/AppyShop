<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('content')
            ->add('hide')
            ->add('topic')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_commenttype';
    }
}
