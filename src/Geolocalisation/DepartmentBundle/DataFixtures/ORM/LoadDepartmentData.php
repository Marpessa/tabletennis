<?php

// src/Geolocalisation/DepartmentBundle/DataFixtures/ORM/LoadDepartmentData.php
namespace Geolocalisation\DepartmentBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Geolocalisation\DepartmentBundle\Entity\Department;

class LoadDepartmentData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_department.php');

        foreach( $dataList as $data )
        {
            if( !empty( $data["region_id"] ) ) {
                $region = $manager->getRepository('Geolocalisation\RegionBundle\Entity\Region')->find( $data["region_id"] );
            }

            $department = new Department();
            $department->setId( $data["id"] );
            $department->setCode( $data["code"] );
            $department->setIso( $data["iso"] );
            $department->setName( $data["name"] );
            if( !empty( $region ) ) {
                $department->setRegionId( $region );
            }

            $manager->persist( $department );

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($department));
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