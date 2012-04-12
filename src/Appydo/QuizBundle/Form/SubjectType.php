<?php

namespace Appydo\QuizBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('hide')
            ->add('manager')
            ->add('file')
            ->add('file_explication')
        ;
    }

    public function getName()
    {
        return 'appydo_quizbundle_subjecttype';
    }
}
