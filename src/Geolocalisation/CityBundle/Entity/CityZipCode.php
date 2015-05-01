<?php

namespace Geolocalisation\CityBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Geolocatisation\CityBundle\Entity\City as GeolocatisationCity;

/**
 * @ORM\Entity
 * @ORM\Table(name="city_zipCode")
 */
class CityZipCode {

    /**
     * @var City
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Geolocalisation\CityBundle\Entity\City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $city_id;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=10, nullable=false)
     * @Assert\NotBlank
     */
    private $zip_code;

    /**
     * Set zip_code
     *
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zip_code = $zipCode;
    }

    /**
     * Get zip_code
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * Set city_id
     *
     * @param Geolocalisation\CityBundle\Entity\City $cityId
     */
    public function setCityId(GeolocatisationCity $cityId)
    {
        $this->city_id = $cityId;
    }

    /**
     * Get city_id
     *
     * @return Geolocalisation\CityBundle\Entity\City
     */
    public function getCityId()
    {
        return $this->city_id;
    }
}