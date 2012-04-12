<?php

namespace Appydo\QuizBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('hide')
            ->add('subject')
            ->add('correct')
        ;
    }

    public function getName()
    {
        return 'appydo_quizbundle_quiztype';
    }
}
