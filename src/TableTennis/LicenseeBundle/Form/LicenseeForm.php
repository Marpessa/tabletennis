<?php

namespace TableTennis\LicenseeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Model\UserInterface;

class LicenseeForm extends AbstractType
{
    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->securityContext->getToken()->getUser();

        $user_licensee = NULL;
        if (is_object($user) && $user instanceof UserInterface) {
          $user_licensee_number = $user->getLicenseeNumber();

          if( !empty( $user_licensee_number ) ){
              /*$user_licensee = $this->getDoctrine()
                                    ->getRepository('TableTennisLicenseeBundle:Licensee')
                                    ->getCurrentLicensee( $user_licensee_number )
                                    ->getArrayResult();

              $user_licensee = array_shift( $user_licensee );*/
          }
        }

        $builder->add('licensee_number', 'text', array('label'  => 'Votre N° licence', 
                                                       'required'  => false,
                                                       'attr' => array( 'placeholder' => 'Ex : 8513023'),
                                                       'data' => $user_licensee_number ) );

        $builder->add('licensee_category', 'choice', array(
                                                          /*'class' => 'TableTennisLicenseeBundle:Licensee',
                                                          'query_builder' => function(EntityRepository $er) {

                                                          },*/
                                                          'choices'   => array( "P" => "P", 
                                                                                "B1" => "B1",  "B2" => "B2",  
                                                                                "M1" => "M1", "M2" => "M2",
                                                                                "C1" => "C1", "C2" => "C2",
                                                                                "J1" => "J1", "J2" => "J2", "J3" => "J3",
                                                                                "S" => "S",
                                                                                "V1" => "V1", "V2" => "V2", "V3" => "V3", "V4" => "V4" ),
                                                          //'preferred_choices' => array('S'),
                                                          'empty_value' => 'Choisissez une catégorie',
                                                          'label'     => 'Catégorie',
                                                          //'data' => "S",
                                                          'required'  => false )
                               );
    }

    public function getName()
    {
        return 'licensee';
    }
}