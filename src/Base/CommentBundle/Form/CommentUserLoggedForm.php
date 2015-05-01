<?php

namespace Base\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentUserLoggedForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'textarea', array( 'label'  => 'Votre message', 'attr'=> array('rows' => '8', 'class'=>'form-control', 'placeholder'=>'Votre message...') ) );
        $builder->add('link', 'hidden', array() );
    }

    public function getName()
    {
        return 'comment';
    }
}