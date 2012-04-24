<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('email', 'email');
        $builder->add('phone', 'number');
        $builder->add('subject', 'file');
        $builder->add('message', 'textarea');
    }
    
    public function getName()
    {
        return 'contact';
    }
}