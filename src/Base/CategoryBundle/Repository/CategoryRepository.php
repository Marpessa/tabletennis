<?php

namespace Base\CategoryBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function getCategories() {
        $q = $this->createQueryBuilder('c')
             ->where('c.is_published = 1')
             ->orderBy('c.title', 'DESC')
             ->getQuery();

        return $q;
    }
}

?>