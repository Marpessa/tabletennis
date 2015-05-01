<?php

namespace Application\Sonata\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AvatarForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('media_id', 'sonata_media_type', array(
             'label' => 'Avatar',
             'provider' => 'sonata.media.provider.image',
             'context'  => 'avatar'
        ));

        $builder->get('media_id')->remove('unlink');

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {

                $event->getForm()->get('media_id')->add('unlink', null, array('mapped' => false));
            }
        );
    }

    public function getName()
    {
        return 'application_sonata_user_avatar';
    }
}