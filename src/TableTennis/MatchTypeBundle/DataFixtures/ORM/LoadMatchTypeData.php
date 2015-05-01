<?php

// src/TableTennis/MatchTypeBundle/DataFixtures/ORM/LoadMatchTypeData.php
namespace TableTennis\MatchTypeBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use TableTennis\MatchTypeBundle\Entity\MatchType;

class LoadMatchTypeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_matchType.php');

        foreach( $dataList as $data )
        {
            $matchType = new MatchType();
            $matchType->setId( $data["id"] );
            $matchType->setCoefficient( $data["coefficient"] );
            $matchType->setTitle( $data["title"] );
            $matchType->setType( $data["type"] );

            $manager->persist($matchType);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($matchType));
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