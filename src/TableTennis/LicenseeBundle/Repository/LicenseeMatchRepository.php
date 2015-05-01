<?php

namespace TableTennis\LicenseeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LicenseeMatchRepository extends EntityRepository
{

    public function getLicenseeMatchs( $licensee_id, $startDate=NULL, $endDate=NULL, $limit=5 ){

        $endDate = clone( $endDate->add( new \DateInterval('P1M') ) );

        $q = $this->getEntityManager()->createQueryBuilder('lm')
                  ->select('lm.datetime_match, lm.opponent_lastname, lm.opponent_firstname, lm.opponent_point, lm.opponent_ranking, lm.category, lm.coefficient, lm.points_evaluation')
                  ->addSelect('mt.title AS match_type_title')
                  ->from('TableTennisLicenseeBundle:LicenseeMatch', 'lm')
                  ->leftJoin('lm.match_type_id', 'mt')
                  ->where('lm.licensee_id = :licensee_id')
                  ->andWhere('lm.datetime_match BETWEEN :startDate AND :endDate')
                  ->orderBy('lm.datetime_match', 'DESC')
                  ->setMaxResults( $limit )
                  ->setParameter('licensee_id', $licensee_id)
                  ->setParameter('startDate', $startDate->format('Y-m-d'))
                  ->setParameter('endDate', $endDate->format('Y-m-d'))
                  ->getQuery();

        return $q;
    }
}