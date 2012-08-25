<?php

namespace Msi\Bundle\MessageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipients', 'entity', array('label' => 'To', 'multiple' => true, 'class' => 'AcmeUserBundle:User'))
            ->add('subject')
            ->add('body', 'textarea')
        ;
    }

    public function getName()
    {
        return 'msi_message_message';
    }
}
