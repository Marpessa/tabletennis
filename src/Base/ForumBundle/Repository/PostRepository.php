<?php

namespace Base\ForumBundle\Repository;

use Herzult\Bundle\ForumBundle\Entity\PostRepository as HerzultPostRepository;

class PostRepository extends HerzultPostRepository
{
    /**
     * @see PostRepositoryInterface::findAllByTopic
     */
    public function findAllByTopic($topic, $asPaginator = false)
    {
        $q = $this->createQueryBuilder('post')
                  ->select('post.id, post.message, post.number, post.createdAt, post.updatedAt')
                  ->addSelect('u.username AS u_username')
                  ->addSelect('m.name AS m_name, m.id AS m_id, m.width AS m_width, m.height AS m_height')
                  ->leftJoin('post.creation_user_id', 'u')
                  ->leftJoin('u.media_id', 'm')
                  ->orderBy('post.createdAt')
                  ->where('post.topic = :topic')
                  ->setParameter('topic', $topic->getId())
                  ->getQuery();

        return $q;
    }

    public function findLastPost($limit = 5)
    {
        $q = $this->createQueryBuilder('post')
                  ->select('post.id, post.message, post.number, post.createdAt, post.updatedAt')
                  ->addSelect('u.username AS u_username, u.licensee_number AS u_licenseeNumber')
                  ->addSelect('t.createdAt AS t_createdAt, t.slug AS t_slug')
                  ->addSelect('cat.slug AS cat_slug')
                  ->addSelect('m.name AS m_name, m.id AS m_id, m.width AS m_width, m.height AS m_height')
                  ->leftJoin('post.creation_user_id', 'u')
                  ->leftJoin('post.topic', 't')
                  ->leftJoin('t.category', 'cat')
                  ->leftJoin('u.media_id', 'm')
                  ->orderBy('post.createdAt', 'DESC')
                  ->setMaxResults($limit)
                  ->getQuery();

        return $q;
    }
}

?>
