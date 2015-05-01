<?php

namespace Base\ForumBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends Admin
{
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'updatedAt' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Contenu')
                ->add('topic', null, array('label' => 'Sujet'))
                ->add('message', null, array('label' => 'Message', 'attr' => array('class' => 'tinymce', 'data-theme' => 'advanced')))
            ->end()

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('message', null, array('label' => 'Message'))
            ->add('topic', null, array('label' => 'Sujet'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->addIdentifier('message', null, array('label' => 'Message'))
            ->add('topic', null, array('label' => 'Sujet'))
            ->add('created_at', 'datetime', array('label' => 'Date de création'))
            ->add('updated_at', 'datetime', array('label' => 'Date de mis à jour'))
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