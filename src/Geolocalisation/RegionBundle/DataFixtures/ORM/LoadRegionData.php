<?php

// src/Geolocalisation/RegionBundle/DataFixtures/ORM/LoadRegionData.php
namespace Geolocalisation\RegionBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Geolocalisation\RegionBundle\Entity\Region;

class LoadRegionData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_region.php');

        foreach( $dataList as $data )
        {
            if( !empty( $data["country_id"] ) ) {
                $country = $manager->getRepository('Geolocalisation\CountryBundle\Entity\Country')->find( $data["country_id"] );
            }

            $region = new Region();
            $region->setId( $data["id"] );
            $region->setCode( $data["code"] );
            $region->setIso( $data["iso"] );
            $region->setName( $data["name"] );
            if( !empty( $country ) ) {
                $region->setCountryId( $country );
            }

            $manager->persist( $region );

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($region));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->flush();
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 2;
    }
}

?>