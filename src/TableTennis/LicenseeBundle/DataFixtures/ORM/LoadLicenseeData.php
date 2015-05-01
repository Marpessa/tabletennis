<?php

// src/TableTennis/LicenseeBundle/DataFixtures/ORM/LoadLicenseeData.php
namespace TableTennis\LicenseeBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use TableTennis\LicenseeBundle\Entity\Licensee;

class LoadLicenseeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_licensee.php');

        foreach( $dataList as $data )
        {
            if( !empty( $data["creation_user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["creation_user_id"] );
            }
            if( !empty( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }
            if( !empty( $data["licensee_user_id"] ) ) {
                $licenseeUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["licensee_user_id"] );
            }

            $licensee = new Licensee();
            $licensee->setId( $data["id"] );
            if( isset( $creationUser ) ) {
                $licensee->setCreationUserId( $creationUser );
            }
            if( isset( $modificationUser ) ) {
                $licensee->setModificationUserId( $modificationUser );
            }

            if( !empty( $data["licensee_user_id"] ) ) {
                $licensee->setLicenseeUserId( $licenseeUser );
            }
            
            $licensee->setLicenseeNumber( $data["licensee_number"] );
            $licensee->setLastname( $data["lastname"] );
            $licensee->setFirstname( $data["firstname"] );
            $category = $data["category"];
            if( in_array($data["category"], array("S1", "S2", "S3", "S4")) ) {
                $category = "S";
            }
            if( in_array($data["category"], array("P1", "P2", "P3", "P4")) ) {
                $category = "P";
            }

            $licensee->setCategory( $category );
            $licensee->setStatus( $data["status"] == "joue" ? "play" : "rest" );
            // $licensee->setTeamId( $data["type"] );
            // $licensee->setClubId( $data["type"] );
            // $licensee->setSlug();
            $licensee->setCreatedAt( new \DateTime($data["created_at"]) );
            $licensee->setUpdatedAt( new \DateTime($data["updated_at"]) );


            $manager->persist($licensee);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($licensee));
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