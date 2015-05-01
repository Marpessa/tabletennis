<?php

namespace Base\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Herzult\Bundle\ForumBundle\Form\PostFormType as HerzultPostFormType;

class PostFormType extends HerzultPostFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message', 'textarea', array('required' => false, 'label'  => 'Message :') );
    }

    public function getName()
    {
        return 'Post';
    }
}
