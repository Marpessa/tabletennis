<?php

namespace Base\CategoryBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Base\CategoryBundle\Repository\CategoryRepository")
 * @ORM\Table(name="category")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=8)
 * @ORM\DiscriminatorMap({"category" = "Category", "menu" = "Menu", "footer" = "Footer", "news" = "CategoryNews"})
 */
class Category {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = "3",
     *      max = "255",
     *      minMessage = "Votre nom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre nom ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    protected $link;

    /**
     * @ORM\Column(columnDefinition="tinyint(4)")
     */
    protected $num_order;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    protected $is_published;

    /**
     *
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Base\CategoryBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $category_parent_id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="creation_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $creation_user_id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="modification_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $modification_user_id;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    protected $slug;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set id
     *
     * @param integer
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set num_order
     *
     * @param string $numOrder
     */
    public function setNumOrder($numOrder)
    {
        $this->num_order = $numOrder;
    }

    /**
     * Get num_order
     *
     * @return string 
     */
    public function getNumOrder()
    {
        return $this->num_order;
    }

    /**
     * Set is_published
     *
     * @param boolean $isPublished
     */
    public function setIsPublished($isPublished)
    {
        $this->is_published = $isPublished;
    }

    /**
     * Get is_published
     *
     * @return boolean 
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * Set category_parent_id
     *
     * @param integer $categoryParentId
     */
    public function setCategoryParentId($categoryParentId)
    {
        $this->category_parent_id = $categoryParentId;
    }

    /**
     * Get category_parent_id
     *
     * @return integer 
     */
    public function getCategoryParentId()
    {
        return $this->category_parent_id;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set creation_user_id
     *
     * @param Application\Sonata\UserBundle\Entity\User $creationUserId
     */
    public function setCreationUserId(\Application\Sonata\UserBundle\Entity\User $creationUserId)
    {
        $this->creation_user_id = $creationUserId;
    }

    /**
     * Get creation_user_id
     *
     * @return Application\Sonata\UserBundle\Entity\User 
     */
    public function getCreationUserId()
    {
        return $this->creation_user_id;
    }

    /**
     * Set modification_user_id
     *
     * @param Application\Sonata\UserBundle\Entity\User $modificationUserId
     */
    public function setModificationUserId(\Application\Sonata\UserBundle\Entity\User $modificationUserId)
    {
        $this->modification_user_id = $modificationUserId;
    }

    /**
     * Get modification_user_id
     *
     * @return Application\Sonata\UserBundle\Entity\User 
     */
    public function getModificationUserId()
    {
        return $this->modification_user_id;
    }
}