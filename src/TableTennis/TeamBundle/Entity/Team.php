<?php

namespace TableTennis\TeamBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="TableTennis\TeamBundle\Repository\TeamRepository")
 * @ORM\Table(name="team")
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = "3",
     *      max = "255",
     *      minMessage = "Votre nom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre nom ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = "3",
     *      max = "255",
     *      minMessage = "Votre nom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre nom ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = "3",
     *      max = "255",
     *      minMessage = "Votre nom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre nom ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $team_category;

    /**
     * @ORM\Column(columnDefinition="tinyint(4)")
     */
    private $num_order;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $media_id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="creation_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $creation_user_id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="modification_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $modification_user_id;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set team_category
     *
     * @param string $teamCategory
     */
    public function setTeamCategory($teamCategory)
    {
        $this->team_category = $teamCategory;
    }

    /**
     * Get team_category
     *
     * @return string 
     */
    public function getTeamCategory()
    {
        return $this->team_category;
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
     * Set media_id
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $mediaId
     */
    public function setMediaId(\Application\Sonata\MediaBundle\Entity\Media $mediaId)
    {
        $this->media_id = $mediaId;
    }

    /**
     * Get media_id
     *
     * @return Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getMediaId()
    {
        return $this->media_id;
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
}