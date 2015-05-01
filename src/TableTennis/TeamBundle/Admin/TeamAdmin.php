<?php

namespace TableTennis\TeamBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

use Symfony\Component\DependencyInjection\ContainerInterface;

class TeamAdmin extends Admin
{
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'updated_at' // name of the ordered field (default = the model id field, if any)
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

        $creation_user_id = $entity->getCreationUserId();
        if( empty( $creation_user_id ) ) {
            $creation_user_id = $this->container->get('security.context')->getToken()->getUser();
        }

        $modification_user_id = $entity->getModificationUserId();
        if( empty( $modification_user_id ) ) {
            $modification_user_id = $this->container->get('security.context')->getToken()->getUser();
        }

        $formMapper
            ->with('Média')
                ->add('media_id', 'sonata_type_model_list',
                      array('label' => 'Image'),
                      array('link_parameters'=> array( 'context' => 'news', 'provider'=>'sonata.media.provider.image')))
            ->end()
            ->with('Contenu')
                ->add('name', null, array('label' => 'Nom'))
                ->add('content', null, array('label' => 'Contenu', 'attr' => array('class' => 'tinymce', 'data-theme' => 'advanced')))
            ->end()
            ->with('Options')
              ->add('num_order', null, array('label' => 'Ordre'))
              ->add('team_category', null, array('label' => 'Catégorie' ))
            ->end()
            ->with('Infos générales')
               ->add('created_at', 'datetime', array('label' => 'Date de création', 'data' => $created_at ))
               ->add('updated_at', 'datetime', array('label' => 'Date de modification', 'data' => $updated_at ))
               ->add('creation_user_id', null, array('label' => 'Créé par', 'data' => $creation_user_id ))
               ->add('modification_user_id', null, array('label' => 'Modifié par', 'data' => $modification_user_id ))
            ->end()

        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Nom'))
            ->add('team_category', null, array('label' => 'Catégorie' ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->addIdentifier('name', null, array('label' => 'Nom'))
            ->add('media_id', null, array('label' => 'Image', 'template' => 'TableTennisTeamBundle:Admin:featuredimage.html.twig' ))
            ->add('team_category', null, array('label' => 'Catégorie' ))
            ->add('num_order', null, array('label' => 'Ordre'))
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