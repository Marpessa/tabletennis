<?php

namespace TableTennis\FfttFeedBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FfttFeedRepository extends EntityRepository
{

    public function getFfttFeeds(){

        $q = $this->getEntityManager()->createQueryBuilder('lm')
                  ->select('f.title, f.description, f.website, f.datetime_publication')
                  ->from('TableTennisFfttFeedBundle:FfttFeed', 'f')
                  ->orderBy('f.datetime_publication', 'DESC')
                  ->getQuery();

        return $q;
    }
}