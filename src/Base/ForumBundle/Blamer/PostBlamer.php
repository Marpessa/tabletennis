<?php

namespace Base\ForumBundle\Blamer;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Herzult\Bundle\ForumBundle\Blamer\AbstractSecurityBlamer;

class PostBlamer extends AbstractSecurityBlamer
{
    public function blame($object)
    {
        $user = $this->security->getToken()->getUser();
        if (!is_object($user) || !$user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $object->setCreationUserId( $this->security->getToken()->getUser() );
    }
}