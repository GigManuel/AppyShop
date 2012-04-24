<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TopicType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('content')
            ->add('hide')
            ->add('rss')
            ->add('comment')
            ->add('created')
            ->add('updated')
            ->add('translate_index')
            ->add('language')
            ->add('author')
            ->add('project')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_topictype';
    }
}
