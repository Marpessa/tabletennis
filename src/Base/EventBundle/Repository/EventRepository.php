<?php

namespace Base\EventBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function getEvents() {
        $q = $this->createQueryBuilder('e')
             ->addSelect('u.username AS u_username')
             ->addSelect('m.id AS m_id')
             ->addSelect('m.width AS m_width')
             ->addSelect('m.height AS m_height')
             ->innerJoin('e.modification_user_id', 'u')
             ->leftJoin('e.media_id', 'm')
             ->where('e.is_published = 1')
             ->orderBy('e.updatedAt', 'DESC')
             ->getQuery();

        return $q;
    }

    public function getCurrentEvent($slug) {
        
        $q = $this->createQueryBuilder('e')
                  ->select('e.content, e.title')
                  ->addSelect('m.id AS m_id')
                  ->addSelect('m.width AS m_width')
                  ->addSelect('m.height AS m_height')
                  ->leftJoin('e.media_id', 'm')
                  ->where('e.is_published = 1')
                  ->andWhere('e.slug = :slug')
                  ->setParameter('slug', $slug)
                  ->getQuery();

        return $q;
    }

    public function getRelatedEvents($current_slug, $limit = 4)
    {
        $q = $this->createQueryBuilder('e')
                  ->select('e.title, e.content, e.slug, e.updatedAt')
                  ->addSelect('u.username AS u_username')
                  ->innerJoin('e.modification_user_id', 'u')
                  ->where('e.is_published = 1')
                  ->andWhere('e.slug != :slug')
                  ->setParameter('slug', $current_slug)
                  ->orderBy('e.updatedAt', 'DESC')
                  ->setMaxResults($limit)
                  ->getQuery();

        return $q;
    }

    public function findAllOrderedByUpdatedAt($limit = 5) {
        $q = $this->createQueryBuilder('e')
                  ->addSelect('u.username AS u_username')
                  ->addSelect('m.name AS m_name')
                  ->innerJoin('e.modification_user_id', 'u')
                  ->leftJoin('e.media_id', 'm')
                  ->where('e.is_published = 1')
                  ->orderBy('e.updatedAt', 'DESC')
                  ->setMaxResults($limit)
                  ->getQuery();

        try {
            return $q->getResult($q::HYDRATE_SCALAR);
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}

?>
