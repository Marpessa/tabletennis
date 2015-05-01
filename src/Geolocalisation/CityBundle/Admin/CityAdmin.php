<?php

namespace Geolocalisation\CityBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class CityAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Contenu')
                ->add('title', null, array('label' => 'Titre'))
                ->add('content', null, array('label' => 'Contenu'))
            ->end()
            ->with('Infos générales')
               ->add('created_at', 'datetime', array('label' => 'Date de création')) //'data' => new \DateTime('now')
               ->add('updated_at', 'datetime', array('label' => 'Date de modification'))
               ->add('creation_user_id', null, array('label' => 'Créé par'))
               ->add('modification_user_id', null, array('label' => 'Modifié par'))
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
            ->addIdentifier('title')
        ;
    }

    /*public function validate(ErrorElement $errorElement, $object)
    {

    }*/
}

?>