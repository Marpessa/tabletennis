<?php

namespace TableTennis\FfttBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ParameterRepository extends EntityRepository
{
	public function getParameter( $type ){
        
        $q = $this->getEntityManager()->createQueryBuilder('p')
                  ->select('p.id, p.type, p.nextUpdate')
                  ->from('TableTennisFfttBundle:Parameter', 'p')
                  ->where('p.type = :type')
                  ->setParameter('type', $type)
                  ->getQuery();

        return $q;
    }
}