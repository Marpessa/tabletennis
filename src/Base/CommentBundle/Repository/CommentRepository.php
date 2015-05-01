<?php

namespace Base\CommentBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function getComments( $currentRequestUri )
    {
        $q = $this->createQueryBuilder('c')
                  ->select('c.id, c.name, c.content, c.createdAt')
                  ->addSelect('u.username AS u_username')
                  ->addSelect('m.name AS m_name, m.id AS m_id, m.width AS m_width, m.height AS m_height')
                  ->leftJoin('c.user_id', 'u')
                  ->leftJoin('u.media_id', 'm')
                  ->where('c.is_published = 1')
                  ->andWhere('c.link = :currentRequestUri')
                  ->orderBy('c.createdAt', 'ASC')
                  ->setParameter('currentRequestUri', $currentRequestUri )
                  ->getQuery();

        return $q;
    }

    public function getLastComments($limit=5)
    {
        $q = $this->createQueryBuilder('c')
                  ->select('c.id, c.name, c.content, c.createdAt, c.link')
                  ->addSelect('u.username AS u_username, u.licensee_number AS u_licenseeNumber')
                  ->addSelect('m.name AS m_name, m.id AS m_id, m.width AS m_width, m.height AS m_height')
                  ->leftJoin('c.user_id', 'u')
                  ->leftJoin('u.media_id', 'm')
                  ->where('c.is_published = 1')
                  ->orderBy('c.createdAt', 'DESC')
                  ->setMaxResults($limit)
                  ->getQuery();

        return $q;
    }
}

?>
