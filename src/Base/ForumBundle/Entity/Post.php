<?php

namespace Base\ForumBundle\Entity;

use Herzult\Bundle\ForumBundle\Entity\Post as BasePost;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Base\ForumBundle\Repository\PostRepository")
 * @ORM\Table(name="forum_post")
 */
class Post extends BasePost
{
    /**
     * @ORM\ManyToOne(targetEntity="Topic")
     */
    protected $topic;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = "4",
     *      minMessage = "Votre message doit faire au moins {{ limit }} caractères"
     * )
     *
     * @var string
     */
    protected $message;

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
     * Set id
     *
     * @param integer
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
    

    
    public function getAuthorName()
    {
        return 'anonymous';
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
}

?>
