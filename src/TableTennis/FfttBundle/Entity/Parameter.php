<?php

namespace TableTennis\FfttBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="TableTennis\FfttBundle\Repository\ParameterRepository")
 * @ORM\Table(name="parameter")
 */
class Parameter {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=14, columnDefinition="ENUM('licensee', 'licensee_point')")
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @var datetime $nextUpdate
     *
     * @ORM\Column(name="next_update", type="datetime")
     */
    private $nextUpdate;

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
     * Set nextUpdate
     *
     * @param datetime $nextUpdate
     */
    public function setNextUpdate($nextUpdate)
    {
        $this->nextUpdate = $nextUpdate;
    }

    /**
     * Get nextUpdate
     *
     * @return datetime 
     */
    public function getNextUpdate()
    {
        return $this->nextUpdate;
    }

}