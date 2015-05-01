<?php

namespace Base\CategoryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class MenuAdmin extends Admin
{
    protected $baseRouteName = 'menu';
    
    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'num_order' // name of the ordered field (default = the model id field, if any)
        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

    public function __construct($code, $class, $baseControllerName, ContainerInterface $container)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
    }

    protected function configureRoutes(RouteCollection $collection) {
         
    }
/*
    public function createQuery($context = 'list') {
        $query = parent::createQuery($context);

        $query->andWhere( $query->expr()->eq($query->getRootAlias().'.type', ':type') );
        $query->setParameter('type', 'menu');

        return $query;
    }*/

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

        // Menu
        $em = $this->container->get('doctrine')->getEntityManager();
        $categories_list = $em->getRepository('BaseCategoryBundle:Menu')
                              ->getCategoriesMenu()
                              ->getResult();
        
        $formMapper
            ->with('Contenu')
                ->add('title', null, array('label' => 'Titre'))
                ->add('link', null, array('label' => 'Lien'))
            ->end()
            ->with('Options')
              ->add('category_parent_id', null, array('label' => 'Catégorie parente', 'choices' => $categories_list ))
              ->add('num_order', null, array('label' => 'Ordre'))
              ->add('is_published', null, array('label' => 'Publié ?'))
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
            ->add('title', null, array('label' => 'Titre'))
            ->add('is_published', null, array('label' => 'Publié ?'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->addIdentifier('title', null, array('label' => 'Titre'))
            ->add('is_published', 'boolean', array('label' => 'Publié ?', 'editable' => true, 'template' => 'BaseCategoryBundle:Admin:published.html.twig' ))
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