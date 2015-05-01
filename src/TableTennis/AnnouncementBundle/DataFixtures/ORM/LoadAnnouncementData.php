<?php

// src/TableTennis/AnnouncementBundle/DataFixtures/ORM/LoadAnnouncementData.php
namespace TableTennis\AnnouncementBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use TableTennis\AnnouncementBundle\Entity\Announcement;

class LoadAnnouncementData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_announcement.php');

        foreach( $dataList as $data )
        {
            if( !is_null( $data["creation_user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["creation_user_id"] );
            }
            if( !is_null( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }

            $announcement = new Announcement();
            $announcement->setId( $data["id"] );
            if( isset( $creationUser ) ) {
                $announcement->setCreationUserId( $creationUser );
            }
            if( isset( $modificationUser ) ) {
                $announcement->setModificationUserId( $modificationUser );
            }
            $announcement->setTitle( $data["title"] );
            $announcement->setContent( $data["description"] );
            $announcement->setLink( $data["type"] == "home" ? "/" : "/equipes-du-club.html" );
            // $announcement->setSlug();
            $announcement->setCreatedAt( new \DateTime($data["created_at"]) );
            $announcement->setUpdatedAt( new \DateTime($data["updated_at"]) );
            $announcement->setIsPublished( 1 );


            $manager->persist($announcement);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($announcement));
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