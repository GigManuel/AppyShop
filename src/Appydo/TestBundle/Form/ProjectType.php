<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('keywords')
            ->add('banner')
            ->add('note')
            ->add('footer')
            ->add('subtitle')
            ->add('hits')
            ->add('menu')
            ->add('comment')
            ->add('contact')
            ->add('hide')
            ->add('config')
			->add('stat')
			->add('log')
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
