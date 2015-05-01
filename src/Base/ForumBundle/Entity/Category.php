<?php

namespace Base\ForumBundle\Entity;

use Herzult\Bundle\ForumBundle\Entity\Category as BaseCategory;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Herzult\Bundle\ForumBundle\Entity\CategoryRepository")
 * @ORM\Table(name="forum_category")
 */
class Category extends BaseCategory
{
    /**
     * Set id
     *
     * @param integer
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}

?>
