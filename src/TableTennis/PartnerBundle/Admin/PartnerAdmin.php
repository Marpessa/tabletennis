<?php

namespace TableTennis\PartnerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class PartnerAdmin extends Admin
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
                ->add('media_id', 'sonata_media_type',
                      array('label' => 'Image', 'required' => false, 'context' => 'partner', 'provider' => 'sonata.media.provider.image'))
                /*->add('media_id', 'sonata_type_model_list',
                      array('label' => 'Image'),
                      array('link_parameters'=> array( 'context' => 'partner', 'provider'=>'sonata.media.provider.image')))*/
                ->add('title', null, array('label' => 'Nom'))
                ->add('content', 'textarea', array('label' => 'Description',
                                                   'attr' => array('class' => 'tinymce', 'data-theme' => 'advanced')))
                ->add('is_published', null, array('label' => 'Publié ?'))
                ->add('link', null, array('label' => 'Url du site', 'required' => false))
            ->end()

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null,  array('label' => 'Nom'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', null,  array('label' => 'Nom'))
            ->add('media_id', null, array('label' => 'Image', 'template' => 'TableTennisPartnerBundle:Admin:featuredimage.html.twig' ))
            ->add('link', null,  array('label' => 'Lien'))
            ->add('is_published', 'boolean', array('label' => 'Publié ?', 'editable' => true, 'template' => 'TableTennisPartnerBundle:Admin:published.html.twig' ))
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