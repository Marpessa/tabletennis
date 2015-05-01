<?php

namespace Base\NewsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
{
    public function getNews()
    {
        $q = $this->createQueryBuilder('n')
                  ->select('n')
                  ->addSelect('u.username AS u_username')
                  ->addSelect('m.id AS m_id')
                  ->addSelect('m.width AS m_width')
                  ->addSelect('m.height AS m_height')
                  ->innerJoin('n.modification_user_id', 'u')
                  ->leftJoin('n.media_id', 'm')
                  ->where('n.is_published = 1')
                  ->orderBy('n.updatedAt', 'DESC')
                  ->getQuery();

        return $q;
    }

    public function getCurrentNews($slug)
    {
        $q = $this->createQueryBuilder('n')
                  ->select('n.title, n.content')
                  ->addSelect('c.id AS c_id')
                  ->addSelect('u.username AS u_username')
                  ->addSelect('m.name AS m_name, m.id AS m_id, m.width AS m_width, m.height AS m_height')
                  ->innerJoin('n.modification_user_id', 'u')
                  ->leftJoin('n.media_id', 'm')
                  ->leftJoin('n.category_id', 'c')
                  ->where('n.is_published = 1')
                  ->andWhere('n.slug = :slug')
                  ->setParameter('slug', $slug)
                  ->getQuery();

        return $q;
    }

    public function getRelatedNews($current_slug, $category_id, $limit = 4)
    {
        $q = $this->createQueryBuilder('n')
                  ->select('n.title, n.content, n.slug, n.updatedAt')
                  ->addSelect('u.username AS u_username')
                  ->innerJoin('n.modification_user_id', 'u')
                  ->where('n.is_published = 1')
                  ->andWhere('n.slug != :slug')
                  ->andWhere('n.category_id = :category_id')
                  ->setParameter('slug', $current_slug)
                  ->setParameter('category_id', $category_id)
                  ->orderBy('n.updatedAt', 'DESC')
                  ->setMaxResults($limit)
                  ->getQuery();

        return $q;
    }

    public function findAllOrderedByUpdatedAt($limit = 10)
    {
        $query = $this->createQueryBuilder('n')
                      ->select('n.title, n.content, n.slug, n.updatedAt')
                      ->addSelect('u.username AS u_username')
                      ->addSelect('m.name AS m_name')
                      ->addSelect('m.id AS m_id')
                      ->addSelect('m.width AS m_width')
                      ->addSelect('m.height AS m_height')
                      ->innerJoin('n.modification_user_id', 'u')
                      ->leftJoin('n.media_id', 'm')
                      ->where('n.is_published = :is_published')
                      ->orderBy('n.updatedAt', 'DESC')
                      ->setMaxResults($limit)
                      ->setParameter('is_published', '1')
                      ->getQuery();

        try {
            return $query->getResult($query::HYDRATE_SCALAR);
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}

?>
