<?php

namespace TableTennis\UsefulInformationBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UsefulInformationRepository extends EntityRepository
{
   
    public function getCurrentUsefulInformation($slug) {

        $q = $this->createQueryBuilder('us')
                  ->select('us.content, us.title')
                  ->addSelect('m.id AS m_id')
                  ->addSelect('m.width AS m_width')
                  ->addSelect('m.height AS m_height')
                  ->leftJoin('us.media_id', 'm')
                  ->where('us.is_published = 1')
                  ->andWhere('us.slug = :slug')
                  ->setParameter('slug', $slug)
                  ->getQuery();

        return $q;
    }

    public function findAllOrderedByUpdatedAt($limit = 5)
    {
        $q = $this->createQueryBuilder('us')
                  ->addSelect('u.username AS u_username')
                  ->addSelect('m.name AS m_name')
                  ->innerJoin('us.modification_user_id', 'u')
                  ->leftJoin('us.media_id', 'm')
                  ->where('us.is_published = 1')
                  ->orderBy('us.updatedAt', 'DESC')
                  ->setMaxResults($limit)
                  ->getQuery();

        try {
            return $q->getResult($q::HYDRATE_SCALAR);
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}