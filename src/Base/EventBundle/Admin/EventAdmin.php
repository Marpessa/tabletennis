<?php

namespace Base\EventBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

use Symfony\Component\DependencyInjection\ContainerInterface;

class EventAdmin extends Admin
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
                ->add('media_id', 'sonata_media_type',
                      array('label' => 'Image', 'required' => false, 'context' => 'news', 'provider' => 'sonata.media.provider.image'))
                /*->add('media_id', 'sonata_type_model_list',
                      array('label' => 'Image'),
                      array('link_parameters'=> array( 'context' => 'news', 'provider'=>'sonata.media.provider.image')))*/
            ->end()
            ->with('Contenu')
                ->add('title', null, array('label' => 'Titre'))
                ->add('content', null, array('label' => 'Contenu', 'attr' => array('class' => 'tinymce', 'data-theme' => 'advanced')))
                ->add('datetime_event', 'sonata_type_date_picker', array('label' => 'Date de l`événement'))
                ->add('is_published', null, array('label' => 'Publié ?', 'required' => false ))
            ->end()
            ->with('Infos générales')
               ->add('created_at', 'sonata_type_date_picker', array('label' => 'Date de création', 'data' => $created_at ))
               ->add('updated_at', 'sonata_type_date_picker', array('label' => 'Date de modification', 'data' => $updated_at ))
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
            ->add('datetime_event', null, array('label' => 'Date de l\'événement'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'ID'))
            ->addIdentifier('title', null, array('label' => 'Titre'))
            ->add('media_id', null, array('label' => 'Image', 'template' => 'BaseEventBundle:Admin:featuredimage.html.twig' ))
            ->add('datetime_event', 'datetime', array('label' => 'Date de l\'événement'))
            ->add('created_at', 'datetime', array('label' => 'Date de création'))
            ->add('updated_at', 'datetime', array('label' => 'Date de mis à jour'))
            ->add('is_published', 'boolean', array('label' => 'Publié ?', 'editable' => true, 'template' => 'BaseEventBundle:Admin:published.html.twig' ))
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