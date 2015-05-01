<?php

namespace Base\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EnquiryType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array( 'label'  => 'Votre nom', 'attr'=> array('class'=>'form-control') ) );
        $builder->add('email', 'email', array( 'label'  => 'Votre email', 'attr'=> array('class'=>'form-control') ) );
        $builder->add('subject', 'text', array( 'label'  => 'Votre demande', 'attr'=> array('class'=>'form-control') ) );
        $builder->add('body', 'textarea', array( 'label'  => 'Votre message', 'attr'=> array('rows' => '8', 'class'=>'form-control') ) );
    }

    public function getName()
    {
        return 'contact';
    }
}