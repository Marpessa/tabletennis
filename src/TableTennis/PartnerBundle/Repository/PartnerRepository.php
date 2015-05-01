<?php

namespace TableTennis\PartnerBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PartnerRepository extends EntityRepository
{
    public function getPartners(){

        $q = $this->getEntityManager()->createQueryBuilder('p')
                  ->select('p.title')
                  ->addSelect('p.slug')
                  ->addSelect('m.id AS m_id')
                  ->addSelect('m.width AS m_width')
                  ->addSelect('m.height AS m_height')
                  ->from('TableTennisPartnerBundle:Partner', 'p')
                  ->innerJoin('p.media_id', 'm')
                  ->where('p.is_published = 1')
                  ->orderBy('p.title', 'ASC')
                  ->getQuery();

        return $q;
    }

    public function getCurrentPartner( $slug ){
        $q = $this->createQueryBuilder('p')
                  ->select('p.title')
                  ->addSelect('p.slug')
                  ->addSelect('p.content')
                  ->addSelect('p.link')
                  ->addSelect('m.id AS m_id')
                  ->addSelect('m.width AS m_width')
                  ->addSelect('m.height AS m_height')
                  ->innerJoin('p.media_id', 'm')
                  ->where('p.is_published = 1')
                  ->andWhere('p.slug = :slug')
                  ->setParameter('slug', $slug)
                  ->getQuery();

        return $q;
    }
}