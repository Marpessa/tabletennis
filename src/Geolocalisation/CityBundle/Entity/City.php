<?php

namespace Geolocalisation\CityBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="city")
 */
class City {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $iso;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $rawname;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $longitude_radian;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $latitude_radian;

    /**
     * @ORM\Column(type="integer")
     */
    private $maintown_id;

    /**
     * @var Department
     *
     * @ORM\ManyToOne(targetEntity="Geolocalisation\DepartmentBundle\Entity\Department")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $department_id;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Geolocalisation\RegionBundle\Entity\Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $region_id;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Geolocalisation\CountryBundle\Entity\Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $country_id;

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
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set iso
     *
     * @param string $iso
     */
    public function setIso($iso)
    {
        $this->iso = $iso;
    }

    /**
     * Get iso
     *
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
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
     * Set rawname
     *
     * @param string $rawname
     */
    public function setRawname($rawname)
    {
        $this->rawname = $rawname;
    }

    /**
     * Get rawname
     *
     * @return string
     */
    public function getRawname()
    {
        return $this->rawname;
    }

    /**
     * Set longitude_radian
     *
     * @param string $longitudeRadian
     */
    public function setLongitudeRadian($longitudeRadian)
    {
        $this->longitude_radian = $longitudeRadian;
    }

    /**
     * Get longitude_radian
     *
     * @return string
     */
    public function getLongitudeRadian()
    {
        return $this->longitude_radian;
    }

    /**
     * Set latitude_radian
     *
     * @param string $latitudeRadian
     */
    public function setLatitudeRadian($latitudeRadian)
    {
        $this->latitude_radian = $latitudeRadian;
    }

    /**
     * Get latitude_radian
     *
     * @return string
     */
    public function getLatitudeRadian()
    {
        return $this->latitude_radian;
    }

    /**
     * Set maintown_id
     *
     * @param integer $maintownId
     */
    public function setMaintownId($maintownId)
    {
        $this->maintown_id = $maintownId;
    }

    /**
     * Get maintown_id
     *
     * @return integer
     */
    public function getMaintownId()
    {
        return $this->maintown_id;
    }

    /**
     * Set department_id
     *
     * @param Geolocalisation\DepartmentBundle\Entity\Department $departmentId
     */
    public function setDepartmentId(\Geolocalisation\DepartmentBundle\Entity\Department $departmentId)
    {
        $this->department_id = $departmentId;
    }

    /**
     * Get department_id
     *
     * @return Geolocalisation\DepartmentBundle\Entity\Department
     */
    public function getDepartmentId()
    {
        return $this->department_id;
    }

    /**
     * Set region_id
     *
     * @param Geolocalisation\RegionBundle\Entity\Region $regionId
     */
    public function setRegionId(\Geolocalisation\RegionBundle\Entity\Region $regionId)
    {
        $this->region_id = $regionId;
    }

    /**
     * Get region_id
     *
     * @return Geolocalisation\RegionBundle\Entity\Region
     */
    public function getRegionId()
    {
        return $this->region_id;
    }

    /**
     * Set country_id
     *
     * @param Geolocalisation\CountryBundle\Entity\Country $countryId
     */
    public function setCountryId(\Geolocalisation\CountryBundle\Entity\Country $countryId)
    {
        $this->country_id = $countryId;
    }

    /**
     * Get country_id
     *
     * @return Geolocalisation\CountryBundle\Entity\Country
     */
    public function getCountryId()
    {
        return $this->country_id;
    }
}