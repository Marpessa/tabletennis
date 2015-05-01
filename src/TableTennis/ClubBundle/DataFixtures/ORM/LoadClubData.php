<?php

// src/TableTennis/ClubBundle/DataFixtures/ORM/LoadClubData.php
namespace TableTennis\ClubBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use TableTennis\ClubBundle\Entity\Club;

class LoadClubData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_club.php');

        foreach( $dataList as $data )
        {
            if( !empty( $data["creation_user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["creation_user_id"] );
            }
            if( !empty( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }

            $club = new Club();
            $club->setId( $data["id"] );
            // $team->setMediaId();
            if( isset( $creationUser ) ) {
                $club->setCreationUserId( $creationUser );
            }
            if( isset( $modificationUser ) ) {
                $club->setModificationUserId( $modificationUser );
            }
            $club->setName( $data["name"] );
            // $club->setCityId(  );
            $club->setClubNumber( $data["club_number"] );
            $club->setWebsite( $data["website"] );
            // $club->setSlug();
            $club->setCreatedAt( new \DateTime($data["created_at"]) );
            $club->setUpdatedAt( new \DateTime($data["updated_at"]) );


            $manager->persist($club);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($club));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->flush();
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 1000;
    }
}

?>