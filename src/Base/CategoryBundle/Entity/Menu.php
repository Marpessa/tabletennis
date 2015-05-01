<?php

namespace Base\CategoryBundle\Entity;

use Base\CategoryBundle\Entity\Category;

use Doctrine\ORM\Mapping as Orm;

/**
 * @Orm\Entity(repositoryClass="Base\CategoryBundle\Repository\MenuRepository")
 */

class Menu extends Category {
}