<?php

namespace Base\PageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{
    public function getPages()
    {
        $q = $this->getEntityManager()->createQueryBuilder('p')
             ->select('p.id, p.title, p.slug')
             ->from('BasePageBundle:Page', 'p')
             ->getQuery();

        return $q;
    }

    public function getCurrentPage($slug)
    {
        $q = $this->getEntityManager()->createQueryBuilder('p')
             ->select('p.content, p.title')
             ->from('BasePageBundle:Page', 'p')
             ->where('p.slug = :slug')
             ->setParameter('slug', $slug)
             ->getQuery();
        
        return $q;
    }
}

?>
