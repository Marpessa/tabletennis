<?php

namespace TableTennis\MatchTypeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MatchTypeRepository extends EntityRepository
{
    public function getCurrentMatchType($id) {
        $q = $this->createQueryBuilder('mt')
                  ->select('mt.title, mt.coefficient, mt.type')
                  ->where('mt.id = :id')
                  ->setParameter('id', $id)
                  ->getQuery();

        return $q;
    }
}