<?php

namespace TableTennis\ScoringTableBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="scoring_table")
 */
class ScoringTable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", length=3, scale=2)
     * @Assert\NotBlank
     */
    public $points_away;

    /**
     * @ORM\Column(type="decimal", length=3, scale=2)
     */
    public $normal_victory;

    /**
     * @ORM\Column(type="decimal", length=3, scale=2)
     */
    public $normal_defeat;

    /**
     * @ORM\Column(type="decimal", length=3, scale=2)
     */
    public $anormal_victory;

    /**
     * @ORM\Column(type="decimal", length=3, scale=2)
     */
    public $anormal_defeat;

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
     * Set points_away
     *
     * @param decimal $pointsAway
     */
    public function setPointsAway($pointsAway)
    {
        $this->points_away = $pointsAway;
    }

    /**
     * Get points_away
     *
     * @return decimal 
     */
    public function getPointsAway()
    {
        return $this->points_away;
    }

    /**
     * Set normal_victory
     *
     * @param decimal $normalVictory
     */
    public function setNormalVictory($normalVictory)
    {
        $this->normal_victory = $normalVictory;
    }

    /**
     * Get normal_victory
     *
     * @return decimal 
     */
    public function getNormalVictory()
    {
        return $this->normal_victory;
    }

    /**
     * Set normal_defeat
     *
     * @param decimal $normalDefeat
     */
    public function setNormalDefeat($normalDefeat)
    {
        $this->normal_defeat = $normalDefeat;
    }

    /**
     * Get normal_defeat
     *
     * @return decimal 
     */
    public function getNormalDefeat()
    {
        return $this->normal_defeat;
    }

    /**
     * Set anormal_victory
     *
     * @param decimal $anormalVictory
     */
    public function setAnormalVictory($anormalVictory)
    {
        $this->anormal_victory = $anormalVictory;
    }

    /**
     * Get anormal_victory
     *
     * @return decimal 
     */
    public function getAnormalVictory()
    {
        return $this->anormal_victory;
    }

    /**
     * Set anormal_defeat
     *
     * @param decimal $anormalDefeat
     */
    public function setAnormalDefeat($anormalDefeat)
    {
        $this->anormal_defeat = $anormalDefeat;
    }

    /**
     * Get anormal_defeat
     *
     * @return decimal 
     */
    public function getAnormalDefeat()
    {
        return $this->anormal_defeat;
    }
}