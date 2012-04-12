<?php

namespace Appydo\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('email', 'email');
        $builder->add('phone', 'text');
        $builder->add('subject', 'text');
        $builder->add('message', 'textarea');
        $builder->add('file', 'file');
        $builder->add('copy', 'checkbox');
    }

    public function getName()
    {
        return 'contact';
    }
}