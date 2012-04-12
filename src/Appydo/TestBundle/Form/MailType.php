<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MailType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('subject')
            ->add('description')
            ->add('created')
            ->add('updated')
            ->add('project')
            ->add('user')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_mailtype';
    }
}
