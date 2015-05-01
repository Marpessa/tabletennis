<?php

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use TableTennis\LicenseeBundle\Form\LicenseeForm as LicenseeFormType;

class ProfileFormType extends BaseType
{
    private $securityContext;

    public function __construct($class, SecurityContext $securityContext)
    {
        parent::__construct($class);
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('lastname', 'text', array( 'label'  => 'Nom', 'required'  => true ) );
        $builder->add('firstname', 'text', array( 'label'  => 'Prénom', 'required'  => true ) );

        $builder->add('licensee', new LicenseeFormType( $this->securityContext ) );
    }

    public function getName()
    {
        return 'application_sonata_user_profile';
    }
}