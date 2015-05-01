<?php

// src/Geolocalisation/CityZipCodeBundle/DataFixtures/ORM/LoadCityZipCodeData.php
namespace Geolocalisation\CityZipCodeBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Geolocalisation\CityZipCodeBundle\Entity\CityZipCode;

class LoadCityZipCodeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        //require_once(__DIR__.'/../../../../../external_scripts/load_cityZipCode.php');

        
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 5;
    }
}

?>