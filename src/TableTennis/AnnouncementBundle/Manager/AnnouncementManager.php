<?php

namespace TableTennis\AnnouncementBundle\Manager;

use Doctrine\ORM\EntityManager;
use TableTennis\AnnouncementBundle\Entity\Announcement;

class AnnouncementManager extends \Twig_Extension
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadAnnouncement($currentUri) {
        return $this->em
                    ->getRepository('TableTennisAnnouncementBundle:Announcement')
                    ->findLastByLink($currentUri);
    }

    public function getFunctions()
    {
        return array(
            'announcement_render' => new \Twig_Function_Method($this, 'render'),
        );
    }

    public function render($currentUri)
    {
        return $this->loadAnnouncement($currentUri);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'announcement';
    }
}