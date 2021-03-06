<?php

namespace TableTennis\LicenseeBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="TableTennis\LicenseeBundle\Repository\LicenseeRepository")
 * @ORM\Table(name="licensee")
 */
class Licensee {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $licensee_number;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = "3",
     *      max = "255",
     *      minMessage = "Le nom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le nom ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = "3",
     *      max = "255",
     *      minMessage = "Le prénom doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le prénom ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ranking;

    /**
     * @ORM\Column(type="decimal", length=3, scale=2, nullable=true)
     */
    private $nb_current_points;

    /**
     * @ORM\Column(type="decimal", length=3, scale=2, nullable=true)
     */
    private $monthly_increase;

    /**
     * @ORM\Column(type="string",length=2, columnDefinition="ENUM('P', 'B1', 'B2', 'M1', 'M2', 'C1', 'C2', 'J1', 'J2', 'J3', 'S', 'V1', 'V2', 'V3', 'V4')")
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @ORM\Column(type="string",length=2, columnDefinition="ENUM('M', 'F')")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="string",length=4, columnDefinition="ENUM('play', 'rest')")
     */
    private $status;

     /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="TableTennis\TeamBundle\Entity\Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $team_id;

     /**
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="TableTennis\ClubBundle\Entity\Club")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $club_id;

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

    /**
     * @ORM\Column(name="is_deleted", type="boolean", length=1, nullable=true)
     */
    private $isDeleted;

    /**
     * @Gedmo\Slug(fields={"lastname", "firstname"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

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
     * Set licensee_number
     *
     * @param integer $licenseeNumber
     */
    public function setLicenseeNumber($licenseeNumber)
    {
        $this->licensee_number = $licenseeNumber;
    }

    /**
     * Get licensee_number
     *
     * @return integer 
     */
    public function getLicenseeNumber()
    {
        return $this->licensee_number;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set ranking
     *
     * @param integer
     */
    public function setRanking($ranking)
    {
        $this->ranking = $ranking;
    }

    /**
     * Get ranking
     *
     * @return integer
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set nb_current_points
     *
     * @param decimal $nbCurrentPoints
     */
    public function setNbCurrentPoints($nbCurrentPoints)
    {
        $this->nb_current_points = $nbCurrentPoints;
    }

    /**
     * Get nb_current_points
     *
     * @return decimal
     */
    public function getNbCurrentPoints()
    {
        return $this->nb_current_points;
    }

    /**
     * Set monthly_increase
     *
     * @param decimal $monthly_increase
     */
    public function setMonthlyIncrease($monthly_increase)
    {
        $this->monthly_increase = $monthly_increase;
    }

    /**
     * Get monthly_increase
     *
     * @return decimal
     */
    public function getMonthlyIncrease()
    {
        return $this->monthly_increase;
    }

    /**
     * Set category
     *
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
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
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
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
     * Set team_id
     *
     * @param TableTennis\TeamBundle\Entity\Team $teamId
     */
    public function setTeamId(\TableTennis\TeamBundle\Entity\Team $teamId)
    {
        $this->team_id = $teamId;
    }

    /**
     * Get team_id
     *
     * @return TableTennis\TeamBundle\Entity\Team 
     */
    public function getTeamId()
    {
        return $this->team_id;
    }

    /**
     * Set club_id
     *
     * @param TableTennis\ClubBundle\Entity\Club $clubId
     */
    public function setClubId(\TableTennis\ClubBundle\Entity\Club $clubId)
    {
        $this->club_id = $clubId;
    }

    /**
     * Get club_id
     *
     * @return TableTennis\ClubBundle\Entity\Club 
     */
    public function getClubId()
    {
        return $this->club_id;
    }


    public function __toString()
    {
        return $this->lastname . " " . $this->firstname;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Licensee
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    
        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }
}