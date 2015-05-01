<?php

namespace TableTennis\LicenseeBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="TableTennis\LicenseeBundle\Repository\LicenseePointRepository")
 * @ORM\Table(name="licensee_point")
 */
class LicenseePoint {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Licensee
     *
     * @ORM\ManyToOne(targetEntity="TableTennis\LicenseeBundle\Entity\Licensee")
     * @ORM\JoinColumn(name="licensee_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $licensee_id;

    /**
     * @ORM\Column(type="decimal", length=3, scale=2, nullable=true)
     */
    private $nb_points;

    /**
     * @ORM\Column(type="decimal", length=3, scale=2, nullable=true)
     */
    private $nb_points_fftt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime_points;

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
     * @var datetime $created
     *
     * @ORM\Column(name="created_at",type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var datetime $updated
     *
     * @ORM\Column(name="updated_at",type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

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
     * Set nb_points
     *
     * @param decimal $nbPoints
     */
    public function setNbPoints($nbPoints)
    {
        $this->nb_points = $nbPoints;
    }

    /**
     * Get nb_points
     *
     * @return decimal 
     */
    public function getNbPoints()
    {
        return $this->nb_points;
    }

    /**
     * Set nb_points_fftt
     *
     * @param integer $nbPointsFftt
     */
    public function setNbPointsFftt($nbPointsFftt)
    {
        $this->nb_points_fftt = $nbPointsFftt;
    }

    /**
     * Get nb_points_fftt
     *
     * @return integer 
     */
    public function getNbPointsFftt()
    {
        return $this->nb_points_fftt;
    }

    /**
     * Set datetime_points
     *
     * @param datetime $datetimePoints
     */
    public function setDatetimePoints($datetimePoints)
    {
        $this->datetime_points = $datetimePoints;
    }

    /**
     * Get datetime_points
     *
     * @return datetime 
     */
    public function getDatetimePoints()
    {
        return $this->datetime_points;
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
     * Set licensee_id
     *
     * @param TableTennis\LicenseeBundle\Entity\Licensee $licenseeId
     */
    public function setLicenseeId(\TableTennis\LicenseeBundle\Entity\Licensee $licenseeId)
    {
        $this->licensee_id = $licenseeId;
    }

    /**
     * Get licensee_id
     *
     * @return TableTennis\LicenseeBundle\Entity\Licensee 
     */
    public function getLicenseeId()
    {
        return $this->licensee_id;
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