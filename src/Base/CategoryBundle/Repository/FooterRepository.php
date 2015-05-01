<?php

namespace Base\CategoryBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FooterRepository extends EntityRepository
{
    public function getCategoriesFooter() {
        $q = $this->createQueryBuilder('c')
             ->where('c.is_published = 1')
             ->orderBy('c.num_order', 'ASC')
             ->getQuery();

        return $q;
    }
}

?>