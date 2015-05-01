<?php

// src\Base\ForumBundle\Controller\NewTopicFormType.php
namespace Base\ForumBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Base\ForumBundle\Form\PostFormType;

use Herzult\Bundle\ForumBundle\Form\NewTopicFormType as HerzultNewTopicFormType;

class NewTopicFormType extends HerzultNewTopicFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subject', 'text', array('label'  => 'Sujet :', 'attr'=> array('class'=>'form-control')));
        $builder->add('category', null, array('label'  => 'Catégorie :', 'attr'=> array('class'=>'form-control')) );
        $builder->add('firstPost', $options['post_form'], array('data_class' => $options['post_class'], 'label' => 'Premier message :' ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'post_class'    => 'Base\ForumBundle\Entity\Post',
            'post_form'     => new PostFormType(),
            'data_class'    => 'Base\ForumBundle\Entity\Topic',
        ));
    }
    
    // Bug de déclaration des paramètres incompatibles avec Symfony
    /*public function getDefaultOptions(array $options)
    {
        return array();
    }*/
}