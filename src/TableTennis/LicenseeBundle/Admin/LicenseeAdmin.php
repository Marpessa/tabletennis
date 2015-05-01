<?php

namespace TableTennis\LicenseeBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

use Symfony\Component\DependencyInjection\ContainerInterface;

class LicenseeAdmin extends Admin
{
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'lastname' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    public function __construct($code, $class, $baseControllerName, ContainerInterface $container)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $entity = $this->getSubject();

        $created_at = $entity->getCreatedAt();
        if( empty( $created_at ) ) {
            $created_at = new \DateTime('now');
        }

        $updated_at = $entity->getUpdatedAt();
        if( empty( $updated_at ) ) {
            $updated_at = new \DateTime('now');
        }
        
        $formMapper
            ->with('Contenu')
                ->add('licensee_number', null, array('label' => 'N° licence'))
                ->add('lastname', null, array('label' => 'Nom'))
                ->add('firstname', null, array('label' => 'Prénom'))
            ->end()
            ->with('Options')
              ->add('ranking', null, array('label' => 'Classement'))
              ->add('nb_current_points', null, array('label' => 'Nb points'))
              ->add('monthly_increase', null, array('label' => 'Progression mensuel'))
              ->add('club_id', 'choice', array('label' => 'Club'))
              ->add('team_id', 'choice', array('label' => 'Equipe'))
              ->add('category', 'choice', array('label' => 'Catégorie'))
              ->add('status', 'choice', array('choices' => array('play'=> 'Joue', 'rest' => 'En repos')))
            ->end()
            ->with('Infos générales')
               ->add('created_at', 'sonata_type_date_picker', array('label' => 'Date de création', 'data' => $created_at ))
               ->add('updated_at', 'sonata_type_date_picker', array('label' => 'Date de modification', 'data' => $updated_at ))
            ->end()

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('licensee_number', null, array('label' => 'N° licence'))
            ->add('lastname', null, array('label' => 'Nom'))
            ->add('firstname', null, array('label' => 'Prénom'))
            ->add('category', null, array('label' => 'Catégorie'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->addIdentifier('licensee_number', null, array('label' => 'N° licence'))
            ->add('lastname', null, array('label' => 'Nom'))
            ->add('firstname', null, array('label' => 'Prénom'))
            ->add('category', null, array('label' => 'Catégorie'))
            ->add('ranking', null, array('label' => 'Classement'))
            ->add('nb_current_points', null, array('label' => 'Nb points'))
            ->add('monthly_increase', null, array('label' => 'Progression mensuel'))
            ->add('_action', 'actions', array(
            'actions' => array(
                //'view' => array(),
                'edit' => array(),
                'delete' => array(),
            )
        ))
        ;
    }

    /*public function validate(ErrorElement $errorElement, $object)
    {

    }*/
}

?>