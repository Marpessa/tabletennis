<?php

namespace TableTennis\PointsCalculationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\Email;
//use Symfony\Component\Validator\Constraints\MinLength;
//use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Component\Validator\Constraints\Collection;

class PointsCalculationForm extends AbstractType
{
    protected $matchType_list;
    
    public function __construct($matchType_list)
    {
        $this->matchType_list = $matchType_list;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Bof bof quoi (problème de value dans le select après )
        $matchs_list = array();
        
        foreach( $this->matchType_list as $match ) {
            $matchs_list[ $match->getId() ] = $match->getTitle();
        }

        
        $builder->add('match_type', 'choice', array(
                                                'choices'   => $matchs_list,
                                                'label'     => 'Sélectionnez votre compétition',
                                                'required'  => true )
                     );
        $builder->add('mensual_points', 'text', array(
                                                 'attr'   => array('placeholder' => 'Vos points actuels'),
                                                 'label'  => 'Vos points mensuels',
                                                 'required'  => true )
                     );

        for($i=0;$i<10;$i++) {
            $builder->add('opponent_point_' . $i, 'text', array(
                                                                    'required'  => false,
                                                                    'attr'      => array('placeholder' => 'Points mensuels'),
                                                                    'label'  => 'Votre adversaire n°' . ($i+1))
                     );
            $builder->add('opponent_status_' . $i, 'choice', array(
                                                              'expanded'    => true,
                                                              'required'    => false,
                                                              'attr'        => array('class' => '' ),
                                                              'choices'     => array('v' => 'Victoire', 'd' => 'Défaite'),
                                                              'empty_value' => false,
                                                              'label'       => 'Victoire ?',
                                                              'data'        => 'v' )
                     );
        }
    }

    public function getName()
    {
        return 'points_calculation';
    }

    /*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'match_type' => new NotBlank(),
            'mensual_points' => new NotBlank()
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }*/
}