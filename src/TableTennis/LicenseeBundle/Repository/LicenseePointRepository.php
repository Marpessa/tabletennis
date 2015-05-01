<?php

namespace TableTennis\LicenseeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LicenseePointRepository extends EntityRepository
{
    /*public function getLastLicenseesEvolution()
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping;
        $rsm->addEntityResult('TableTennis\LicenseeBundle\Entity\LicenseePoint', 'lp');
        $rsm->addFieldResult('lp', 'id', 'id');
        $rsm->addFieldResult('lp', 'nb_points', 'nb_points');
        $rsm->addFieldResult('lp', 'nb_points_fftt', 'nb_points_fftt');
        $rsm->addFieldResult('lp', 'datetime_points', 'datetime_points');

        $rsm->addEntityResult('TableTennis\LicenseeBundle\Entity\Licensee', 'l');
        $rsm->addFieldResult('l', 'id', 'id');
        $rsm->addFieldResult('l', 'lastname', 'lastname');
        $rsm->addFieldResult('l', 'firstname', 'firstname');
        $rsm->addFieldResult('l', 'slug', 'slug');
        $rsm->addFieldResult('l', 'licensee_number', 'licensee_number');


        $sql = 'SELECT lp.id, lp.licensee_id, lp.nb_points_fftt, lp.datetime_points,
                       l.id, l.lastname, l.firstname, l.slug, l.licensee_number
                FROM licensee_point lp
                INNER JOIN licensee l ON l.id = lp.licensee_id
                WHERE lp.nb_points_fftt IS NOT NULL
                AND lp.datetime_points IN ( ( SELECT MAX( lp2.datetime_points )
                                              FROM licensee_point lp2
                                              WHERE lp.licensee_id = lp2.licensee_id ),
                                            ( SELECT DATE_SUB( MAX( lp2.datetime_points ), INTERVAL 1 MONTH)
                                              FROM licensee_point lp2
                                              WHERE lp.licensee_id = lp2.licensee_id ) )
                ORDER BY lp.licensee_id DESC, lp.datetime_points DESC';
        
        $query = $this->_em->createNativeQuery($sql, $rsm);

        try {
            return $query->getResult($query::HYDRATE_SCALAR);
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }*/
    

    public function getLicenseePoints( $licensee_id, $limit=5 ) {
        
        $q = $this->getEntityManager()->createQueryBuilder('lp')
                  ->select('lp.id, lp.nb_points_fftt, lp.datetime_points')
                  ->from('TableTennisLicenseeBundle:LicenseePoint', 'lp')
                  ->where('lp.licensee_id = :licensee_id')
                  ->andWhere('lp.nb_points_fftt IS NOT NULL')
                  ->orderBy('lp.datetime_points', 'DESC')
                  ->setMaxResults($limit)
                  ->setParameter('licensee_id', $licensee_id)
                  ->getQuery();

        return $q;
    }
}