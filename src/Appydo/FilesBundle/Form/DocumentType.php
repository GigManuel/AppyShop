<?php

namespace Appydo\FilesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('file');
    }
    
    public function getName()
    {
        return 'document';
    }
}