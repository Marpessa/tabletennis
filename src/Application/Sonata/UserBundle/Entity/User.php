<?php

namespace Application\Sonata\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

//use FOS\AdvancedEncoderBundle\Security\Encoder\EncoderAwareInterface;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;

class User extends BaseUser //implements EncoderAwareInterface
{
    /**
     * @var integer $id
     */
    protected $id;
    

    /** @ORM\Column(type="string", length=255) */
    protected $algorithm = 'sha512';

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $media_id;

    /**
     * @var Licensee
     *
     * @ORM\ManyToOne(targetEntity="TableTennis\LicenseeBundle\Entity\Licensee")
     * @ORM\JoinColumn(name="licensee_number", referencedColumnName="licensee_number", onDelete="SET NULL")
     */
    private $licensee_number;

    /**
     * @var Licensee
     *
     */
    protected $licensee;

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
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set salt
     *
     * @param string
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
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
     * Get licensee_number
     *
     * @return string $licensee_number
     */
    public function getLicenseeNumber()
    {
        return $this->licensee_number;
    }

    /**
     * Set licensee_number
     *
     * @param string
     */
    public function setLicenseeNumber($licensee_number)
    {
        $this->licensee_number = $licensee_number;
    }

    /**
     * Get licensee
     *
     * @return string $licensee
     */
    public function getLicensee()
    {
        return $this->licensee;
    }

    /**
     * Set licensee
     *
     * @param string
     */
    public function setLicensee($licensee = null)
    {
        $this->licensee = $licensee;
    }


    /**
     * Set algorithm
     *
     * @param string
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

}