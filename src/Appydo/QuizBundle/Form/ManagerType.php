<?php

namespace Appydo\QuizBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ManagerType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('hide')
        ;
    }

    public function getName()
    {
        return 'appydo_quizbundle_managertype';
    }
}
