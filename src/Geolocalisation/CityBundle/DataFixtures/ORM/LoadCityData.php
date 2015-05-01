<?php

// src/Geolocalisation/CityBundle/DataFixtures/ORM/LoadCityData.php
namespace Geolocalisation\CityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Geolocalisation\CityBundle\Entity\City;

class LoadCityData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        //require_once(__DIR__.'/../../../../../external_scripts/load_city.php');

        
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