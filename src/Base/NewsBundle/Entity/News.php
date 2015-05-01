<?php

namespace Base\NewsBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Sonata\MediaBundle\Model\MediaInterface;

use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

/**
 * @ORM\Entity(repositoryClass="Base\NewsBundle\Repository\NewsRepository")
 * @ORM\Table(name="news")
 */
class News implements RoutedItemInterface {

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
     *      minMessage = "Votre titre doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre titre ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = "3",
     *      minMessage = "Votre contenu doit faire au moins {{ limit }} caractères"
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=5, columnDefinition="ENUM('adult', 'young')")
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    private $is_published;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Base\CategoryBundle\Entity\CategoryNews")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $category_id;

    /**
     * @var \Application\Sonata\MediaBundle\Entity\Media
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"}, fetch="LAZY")
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
     * @Gedmo\Slug(fields={"title"})
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
     * Set category_id
     *
     * @param Base\CategoryBundle\Entity\Category $categoryId
     */
    public function setCategoryId(\Base\CategoryBundle\Entity\Category $categoryId)
    {
        $this->category_id = $categoryId;
    }

    /**
     * Get category_id
     *
     * @return Base\CategoryBundle\Entity\Category 
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set media_id
     *
     * @param MediaInterface $media_id
     */
    public function setMediaId(MediaInterface $mediaId = NULL)
    {
        $this->media_id = $mediaId;
    }

    /**
     * Get media_id
     *
     * @return MediaInterface
     */
    public function getMediaId()
    {
        return $this->media_id;
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



    
    public function getFeedItemTitle() {
        return $this->title;
    }

    public function getFeedItemDescription() {
        return strip_tags( $this->content );
    }

    public function getFeedItemPubDate() {
        $updatedAt = $this->updatedAt;
        if( !( $updatedAt instanceOf \Datetime ) ) {
            $updatedAt = new \Datetime();
            $updatedAt->sub(new \DateInterval('P1Y'));
        }
        return $updatedAt;
    }

    public function getFeedItemRouteName() {
        return "_baseNewsShow";
    }

    public function getFeedItemRouteParameters() {
        $updatedAt = $this->updatedAt;
        if( !( $updatedAt instanceOf \Datetime ) ) {
            $updatedAt = new \Datetime();
            $updatedAt->sub(new \DateInterval('P1Y'));
        }
        return array( "year" => $updatedAt->format('Y'), "month" => $updatedAt->format('m'), "slug" => $this->slug );
    }
    
    public function getFeedItemUrlAnchor() {}

    /**
     * Returns a custom media field
     *
     * @return string
     */
    public function getFeedMediaItem()
    {
        return array(
            'type'   => 'image/jpeg',
            'length' => 500,
            'value'  =>  'http://cpfaizenay.com/images/cpfaizenay/logo_facebook.jpg'
        );
    }
}