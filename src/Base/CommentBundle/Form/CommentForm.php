<?php

namespace Base\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array( 'label'  => 'Votre nom*', 'attr' => array( 'placeholder' => 'Votre nom*', 'maxlength' => 30 ) ) );
        $builder->add('email', 'email', array( 'label'  => 'Votre email*', 'attr' => array( 'placeholder' => 'Votre email (ne sera pas publié)*' ) ) );
        $builder->add('content', 'textarea', array( 'label'  => 'Votre message', 'attr'=> array('rows' => '8', 'class'=>'form-control', 'placeholder'=>'Votre message...') ) );
        $builder->add('captcha', 'captcha', array( 'label'  => 'Vérification', 'attr' => array( 'placeholder' => 'Recopier le texte pour valider votre message*' ) ) );
        //$builder->add('content', 'hidden', array( 'label'  => 'Votre message*') );
        $builder->add('link', 'hidden', array() );
    }

    public function getName()
    {
        return 'comment';
    }
}