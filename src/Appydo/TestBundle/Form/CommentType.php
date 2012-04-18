<?php

namespace Appydo\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;

class CommentType extends AbstractType
{
    public function __construct ($project)
    {
        $this->project = $project;
    }
    public function buildForm(FormBuilder $builder, array $options)
    {
        $project = $this->project;
        $builder
            ->add('topic', 'entity', array(
                'class' => 'AppydoTestBundle:Topic',
                'query_builder' => function(EntityRepository $er) use ($project)  {
                    return $er->createQueryBuilder('c')->where("c.project = $project");
                }))
            ->add('name')
            ->add('content')
            ->add('hide')
        ;
    }

    public function getName()
    {
        return 'appydo_testbundle_commenttype';
    }
}
