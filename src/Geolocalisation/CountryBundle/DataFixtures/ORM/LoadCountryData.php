<?php

// src/Geolocalisation/CountryBundle/DataFixtures/ORM/LoadCountryData.php
namespace Geolocalisation\CountryBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Geolocalisation\CountryBundle\Entity\Country;

class LoadCountryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_country.php');

        foreach( $dataList as $data )
        {
            $country = new Country();
            $country->setId( $data["id"] );
            $country->setCode( $data["code"] );
            $country->setIso( $data["iso"] );
            $country->setName( $data["name"] );

            $manager->persist( $country );

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($country));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->flush();
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 1;
    }
}

?>