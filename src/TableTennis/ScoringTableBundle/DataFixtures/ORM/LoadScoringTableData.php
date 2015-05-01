<?php

// src/TableTennis/ScoringTableBundle/DataFixtures/ORM/LoadScoringTableData.php
namespace TableTennis\ScoringTableBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use TableTennis\ScoringTableBundle\Entity\ScoringTable;

class LoadScoringTableData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_scoringTable.php');

        foreach( $dataList as $data )
        {
            $scoringTable = new ScoringTable();
            $scoringTable->setId( $data["id"] );
            $scoringTable->setPointsAway( $data["points_away"] );
            $scoringTable->setNormalVictory( $data["normal_victory"] );
            $scoringTable->setNormalDefeat( $data["normal_defeat"] );
            $scoringTable->setAnormalVictory( $data["anormal_victory"] );
            $scoringTable->setAnormalDefeat( $data["anormal_defeat"] );

            $manager->persist( $scoringTable );

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($scoringTable));
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