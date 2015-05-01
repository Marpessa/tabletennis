<?php

namespace Base\CategoryBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository
{
    public function getCategoriesMenu() {
        $q = $this->createQueryBuilder('c')
             ->where('c.is_published = 1')
             ->orderBy('c.num_order', 'ASC')
             ->getQuery();

        return $q;
    }
}

?>