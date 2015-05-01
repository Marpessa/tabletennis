<?php

// src/Base/PageBundle/DataFixtures/ORM/LoadPageData.php
namespace Base\PageBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Base\PageBundle\Entity\Page;

class LoadPageData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_page.php');

        foreach( $dataList as $data )
        {
            if( !empty( $data["creation_user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["creation_user_id"] );
            }
            if( !empty( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }

            $page = new Page();
            $page->setId( $data["id"] );
            if( isset( $creationUser ) ) {
                $page->setCreationUserId( $creationUser );
            }
            if( isset( $modificationUser ) ) {
                $page->setModificationUserId( $modificationUser );
            }
            $page->setTitle( $data["title"] );
            $page->setContent( $data["content"] );
            // $club->setSlug();
            $page->setCreatedAt( new \DateTime($data["created_at"]) );
            $page->setUpdatedAt( new \DateTime($data["updated_at"]) );


            $manager->persist($page);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($page));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->flush();
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 3;
    }
}

?>