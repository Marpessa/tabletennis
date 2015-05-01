<?php

namespace TableTennis\AnnouncementBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AnnouncementRepository extends EntityRepository
{
    public function findLastByLink( $currentRequestUri )
    {
        //var_dump($currentUri);
        $query = $this->createQueryBuilder('a')
                      ->where('a.is_published = 1')
                      ->andWhere('a.link = :currentRequestUri')
                      ->orderBy('a.updatedAt', 'DESC')
                      ->setMaxResults(1)
                      ->setParameter('currentRequestUri', $currentRequestUri )
                      ->getQuery();

        try {
            return $query->getSingleResult($query::HYDRATE_SCALAR);
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}