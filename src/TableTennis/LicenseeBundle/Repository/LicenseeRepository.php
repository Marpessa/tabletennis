<?php

namespace TableTennis\LicenseeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LicenseeRepository extends EntityRepository
{

    public function getLicensees(){
        
        $q = $this->getEntityManager()->createQueryBuilder('l')
                  ->select('l.id, l.slug, l.licensee_number, l.lastname, l.firstname, l.category, l.nb_current_points, l.title')
                  ->from('TableTennisLicenseeBundle:Licensee', 'l')
                  ->groupBy('l.licensee_number')
                  ->orderBy('l.lastname', 'ASC')
                  ->getQuery();

        return $q;
    }

    public function getLicenseesByMonthlyIncrease(){

        $q = $this->getEntityManager()->createQueryBuilder('l')
                  ->select('l.id, l.slug, l.licensee_number, l.lastname, l.firstname, l.category, l.nb_current_points, l.monthly_increase')
                  ->from('TableTennisLicenseeBundle:Licensee', 'l')
                  ->where('l.monthly_increase IS NOT NULL')
                  ->groupBy('l.licensee_number')
                  ->orderBy('l.monthly_increase', 'DESC')
                  ->getQuery();

        return $q;
    }
    
    public function getLicenseesByNbCurrentPoints(){

        $q = $this->getEntityManager()->createQueryBuilder('l')
                  ->select('l.id, l.slug, l.licensee_number, l.lastname, l.firstname, l.category, l.nb_current_points, l.monthly_increase')
                  ->from('TableTennisLicenseeBundle:Licensee', 'l')
                  ->where('l.monthly_increase IS NOT NULL')
                  ->groupBy('l.licensee_number')
                  ->orderBy('l.nb_current_points', 'DESC')
                  ->getQuery();

        return $q;
    }
/*
    public function getCurrentLicenseeUser( $licensee_number ){
        
        $q = $this->getEntityManager()->createQueryBuilder('u')
                  ->select('l.slug, l.licensee_number, l.category')
                  ->from('ApplicationSonataUserBundle:User', 'u')
                  ->leftJoin('TableTennisLicenseeBundle:Licensee', 'l')
                  ->where('u.licensee_number = :licensee_number')
                  ->setParameter('licensee_number', $licensee_number )
                  ->getQuery();

        return $q;
    }*/

    public function getCurrentLicensee( $licensee_number ){

        $q = $this->getEntityManager()->createQueryBuilder('l')
                  ->select('l.id, l.slug, l.licensee_number, l.lastname, l.firstname, l.category, l.nb_current_points, l.monthly_increase')
                  ->addSelect('c.name AS club_name')
                  //->addSelect('u.username AS user_username, u.lastname AS user_lastname, u.firstname AS user_firstname')
                  //->addSelect('m.name AS m_name, m.id AS m_id, m.width AS m_width, m.height AS m_height')
                  ->from('TableTennisLicenseeBundle:Licensee', 'l')
                  ->leftJoin('l.club_id', 'c')
                  //->leftJoin('l.licensee_user_id', 'u')
                  //->leftJoin('u.media_id', 'm')
                  ->where('l.licensee_number = :licensee_number')
                  //->andWhere('l.slug = :slug')
                  ->setParameter('licensee_number', $licensee_number)
                  //->setParameter('slug', $slug)
                  ->getQuery();

        return $q;
    }
}