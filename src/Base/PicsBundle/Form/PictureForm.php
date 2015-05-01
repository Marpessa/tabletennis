<?php

namespace Base\PicsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PictureForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('media_id', 'sonata_media_type', array(
             'label' => 'Photo',
             'provider' => 'sonata.media.provider.image',
             'context'  => 'photo_gallery'
        ));
    }

    public function getName()
    {
        return 'base_pics_picture';
    }
}