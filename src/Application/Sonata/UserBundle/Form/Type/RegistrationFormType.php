<?php

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use TableTennis\LicenseeBundle\Form\LicenseeForm as LicenseeFormType;

class RegistrationFormType extends BaseType
{
    private $securityContext;

    public function __construct($class, SecurityContext $securityContext)
    {
        parent::__construct($class);
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);

        $builder
            ->add('username', null, array('label' => 'Login', 'translation_domain' => 'FOSUserBundle', 'required'  => true ))
            ->add('email', 'email', array('label' => 'Adresse email', 'translation_domain' => 'FOSUserBundle', 'required'  => true ))
            ->add('plainPassword', 'repeated', array(
                  'type' => 'password',
                  'options' => array('translation_domain' => 'FOSUserBundle'),
                  'first_options' => array('label' => 'Mot de passe'),
                  'second_options' => array('label' => 'Confirmer le mot de passe'),
                  'invalid_message' => 'fos_user.password.mismatch',
                  'required'  => true
             ))
        ;
        
        $builder->add('lastname', 'text', array( 'label'  => 'Nom', 'required'  => true ) );
        $builder->add('firstname', 'text', array( 'label'  => 'Prénom', 'required'  => true ) );

        $builder->add('licensee', new LicenseeFormType( $this->securityContext ));
    }

    public function getName()
    {
        return 'application_sonata_user_registration';
    }
}