<?php

namespace Base\ForumBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class TopicAdmin extends Admin
{
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'createdAt' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Contenu')
                ->add('subject', null, array('label' => 'Sujet'))
                ->add('category', null, array('label' => 'Catégorie'))
                ->add('isClosed', null, array('label' => 'Fermé ?', 'required' => false))
                ->add('isPinned', null, array('label' => 'Epinglé ?', 'required' => false))
                ->add('isBuried', null, array('label' => 'Brûlé ?', 'required' => false))
            ->end()

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('subject', null, array('label' => 'Sujet'))
            ->add('category', null, array('label' => 'Catégorie'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->addIdentifier('subject', null, array('label' => 'Sujet'))
            ->add('category', null, array('label' => 'Catégorie'))
            ->add('num_posts', null, array('label' => 'Nb messages'))
            ->add('num_views', null, array('label' => 'Nb vues'))
            ->add('isClosed', 'boolean', array('label' => 'Fermé ?', 'editable' => true))
            ->add('isPinned', 'boolean', array('label' => 'Epinglé ?', 'editable' => true))
            ->add('isBuried', 'boolean', array('label' => 'Brûlé ?', 'editable' => true))
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