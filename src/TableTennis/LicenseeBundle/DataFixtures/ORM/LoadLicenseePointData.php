<?php

// src/TableTennis/LicenseeBundle/DataFixtures/ORM/LoadLicenseePointData.php
namespace TableTennis\LicenseeBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use TableTennis\LicenseeBundle\Entity\LicenseePoint;

class LoadLicenseePointData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_licensee_point.php');

        foreach( $dataList as $data )
        {
            if( !is_null( $data["nb_points_fftt"] ) ) {

                if( !empty( $data["creation_user_id"] ) ) {
                    $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["creation_user_id"] );
                }
                if( !empty( $data["modification_user_id"] ) ) {
                    $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
                }
                if( !empty( $data["licensee_id"] ) ) {
                    $licensee = $manager->getRepository('TableTennis\LicenseeBundle\Entity\Licensee')->find( $data["licensee_id"] );
                }


                $licenseePoint = new LicenseePoint();
                $licenseePoint->setId( $data["id"] );
                if( isset( $creationUser ) ) {
                    $licenseePoint->setCreationUserId( $creationUser );
                }
                if( isset( $modificationUser ) ) {
                    $licenseePoint->setModificationUserId( $modificationUser );
                }
                $licenseePoint->setLicenseeId( $licensee );
                $licenseePoint->setNbPoints( $data["nb_points"] );
                $licenseePoint->setNbPointsFftt( $data["nb_points_fftt"] );
                $licenseePoint->setDatetimePoints( new \DateTime( $data["datetime_points"] ) );


                if( !empty( $licensee ) )
                {
                    $licensee->setNbCurrentPoints( $data["nb_points_fftt"] );
                    $licensee->setMonthlyIncrease( 0 );
                }

                $licenseePoint->setCreatedAt( new \DateTime($data["created_at"]) );
                $licenseePoint->setUpdatedAt( new \DateTime($data["updated_at"]) );


                $manager->persist($licenseePoint);
                $manager->persist($licensee);

                /* Pour forcer l'id */
                $metadata = $manager->getClassMetaData(get_class($licenseePoint));
                $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

                $manager->flush();
            }
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 4;
    }
}

?>