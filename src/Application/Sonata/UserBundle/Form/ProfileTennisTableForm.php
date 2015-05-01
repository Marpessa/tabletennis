<?php

namespace Application\Sonata\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileTennisTableForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('licensee_number', 'text', array('label' => 'N° licence*', 'required'  => true ));
        $builder->add('category', 'choice', array('label' => 'Catégorie*',
                                                  'choices' => array('P' => 'P', 'B1' => 'B1', 'B2' => 'B2',
                                                                     'M1' => 'M1', 'M2' => 'M2', 'C1' => 'C1',
                                                                     'C2' => 'C2', 'J1' => 'J1', 'J2' => 'J2',
                                                                     'J3' => 'J3', 'S' => 'S', 'V1' => 'V1',
                                                                     'V2' => 'V2', 'V3' => 'V3', 'V4' => 'V4'
                                                                    ),
                                                  'required'  => true ));
    }

    public function getName()
    {
        return 'application_sonata_user_profile_tennis_table';
    }
}