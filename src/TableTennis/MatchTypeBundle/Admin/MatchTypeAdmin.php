<?php

namespace TableTennis\MatchTypeBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class MatchTypeAdmin extends Admin
{
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'title' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Contenu')
                ->add('title', null, array('label' => 'Titre'))
                ->add('coefficient', null, array('label' => 'Coëfficient'))
            ->end()
            ->with('Options')
              ->add('type', 'choice', array('label' => 'Type', 'choices' => array('team'=> 'Equipes', 'individual' => 'Individuel')))
            ->end()

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->addIdentifier('title', null, array('label' => 'Titre'))
            ->add('coefficient', null, array('label' => 'Coëfficient'))
            ->add('type', null, array('label' => 'Type'))
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