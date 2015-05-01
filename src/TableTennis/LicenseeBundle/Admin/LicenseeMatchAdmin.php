<?php

namespace TableTennis\LicenseeBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class LicenseeMatchAdmin extends Admin
{
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'datetime_match' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Contenu')
                ->add('licensee_id', null, array('label' => 'Licencié'))
                //->add('match_type_id', null, array('label' => 'Type du match'))
                ->add('datetime_match', null, array('label' => 'Date du match'))
                ->add('category', null, array('label' => 'Status'))
            ->end()
            ->with('Options')
                ->add('opponent_lastname', null, array('label' => 'Nom de l\'adversaire'))
                ->add('opponent_firstname', null, array('label' => 'Prénom de l\'adversaire'))
                ->add('opponent_ranking', null, array('label' => 'Classement de l\'adversaire'))
                ->add('opponent_licensee_number', null, array('label' => 'N° de licence l\'adversaire'))
            ->end()
            ->with('Infos générales')
               ->add('creation_user_id', null, array('label' => 'Créé par'))
               ->add('modification_user_id', null, array('label' => 'Modifié par'))
            ->end()

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('licensee_id', null, array('label' => 'Licencié'))
            //->add('match_type_id')
            ->add('datetime_match', null, array('label' => 'Date du match'))
            ->add('category', null, array('label' => 'Status'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->addIdentifier('licensee_id', null, array('label' => 'Licencié'))
            //->add('match_type_id', null, array('label' => 'Type de match'))
            ->add('datetime_match', null, array('label' => 'Date du match'))
            ->add('category', null, array('label' => 'Status'))
            ->add('opponent_lastname', null, array('label' => 'Nom de l\'adversaire'))
            ->add('opponent_firstname', null, array('label' => 'Prénom de l\'adversaire'))
            ->add('opponent_licensee_number', null, array('label' => 'N° de licence l\'adversaire'))
            ->add('opponent_ranking', null, array('label' => 'Classement de l\'adversaire'))
            ->add('coefficient', null, array('label' => 'Coefficient du match'))
            ->add('points_evaluation', null, array('label' => 'Gain/Perte'))
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