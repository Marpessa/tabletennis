<?php

namespace Base\ForumBundle\Repository;

use Herzult\Bundle\ForumBundle\Entity\TopicRepository as HerzultTopicRepository;

class TopicRepository extends HerzultTopicRepository
{
    /**
     * @see TopicRepositoryInterface::findOneByTimeStampAndCategoryAndSlug
     */
    public function findOneByTimeStampAndCategoryAndSlug($timestamp, $category, $slug)
    {
        return $this->findOneBy(array(
            'createdAt'  => new \Datetime( date( 'Y-m-d H:i:s', $timestamp ) ),
            'slug'       => $slug,
            'category'   => $category->getId()
        ));
    }
}

?>
