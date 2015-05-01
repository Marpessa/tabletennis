<?php

namespace TableTennis\TeamBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    public function getTeams() {
        
        $q = $this->createQueryBuilder('t')
             ->select('t.name')
             ->addSelect('t.content')
             ->addSelect('m.id AS m_id')
             ->addSelect('m.width AS m_width')
             ->addSelect('m.height AS m_height')
             ->leftJoin('t.media_id', 'm')
             ->orderBy('t.num_order', 'ASC')
             ->getQuery();

        return $q;
    }
}

?>
