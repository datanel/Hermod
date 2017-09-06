<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="location_patch", indexes={@ORM\Index(name="location_patch_equipment_id_idx", columns={"equipment_id"})})
 */
class LocationPatch implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean the reporting mode used, can be either
     *  - false if that patch was created by simply giving it a humanLocation
     *  - true if the reporter provided his GPS geolocation to the API to
     *    indicate the new humanLocation
     */
    private $usingReporterGeolocation;

    /**
     * @ORM\Column(type="guid")
     *
     * @var EquipmentId
     */
    private $equipmentId;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the current latitude
     */
    private $currentLat = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the stop point current longitude
     */
    private $currentLon = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the suggested latitude
     */
    private $patchedLat = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the suggested longitude
     */
    private $patchedLon = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the end-user geolocation latitude
     */
    private $reporterLat = 0;

    /**
     * @ORM\Column(type="float")
     *
     * @var float the end-user geolocation longitude
     */
    private $ReporterLon = 0;

    /**
     * @ORM\Column(type="integer")
     *
     * @var float accuracy (in meters) of the end-user GPS device
     */
    private $reporterAccuracy = 0;

    /**
     * Many Patches have One User.
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="locationPatches")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User The user submitting the patch
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return LocationPatch
     */
    public function setId(string $id) : LocationPatch
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isUsingReporterGeolocation(): bool
    {
        return $this->usingReporterGeolocation;
    }

    /**
     * @param boolean $usingReporterGeolocation
     * @return LocationPatch
     */
    public function setUsingReporterGeolocation(bool $usingReporterGeolocation): LocationPatch
    {
        $this->usingReporterGeolocation = $usingReporterGeolocation;
        return $this;
    }

    /**
     * @return int
     */
    public function getEquipmentId(): string
    {
        return $this->equipmentId;
    }

    /**
     * @param int $equipmentId
     * @return LocationPatch
     */
    public function setEquipmentId(string $equipmentId): LocationPatch
    {
        $this->equipmentId = $equipmentId;
        return $this;
    }

    /**
     * @return float
     */
    public function getCurrentLat(): float
    {
        return $this->currentLat;
    }

    /**
     * @param float $currentLat
     * @return LocationPatch
     */
    public function setCurrentLat(float $currentLat): LocationPatch
    {
        $this->currentLat = $currentLat;
        return $this;
    }

    /**
     * @return float
     */
    public function getCurrentLon(): float
    {
        return $this->currentLon;
    }

    /**
     * @param float $currentLon
     * @return LocationPatch
     */
    public function setCurrentLon(float $currentLon): LocationPatch
    {
        $this->currentLon = $currentLon;
        return $this;
    }

    /**
     * @return float
     */
    public function getPatchedLat(): float
    {
        return $this->patchedLat;
    }

    /**
     * @param float $patchedLat
     * @return LocationPatch
     */
    public function setPatchedLat(float $patchedLat): LocationPatch
    {
        $this->patchedLat = $patchedLat;
        return $this;
    }

    /**
     * @return float
     */
    public function getPatchedLon(): float
    {
        return $this->patchedLon;
    }

    /**
     * @param float $patchedLon
     * @return LocationPatch
     */
    public function setPatchedLon(float $patchedLon): LocationPatch
    {
        $this->patchedLon = $patchedLon;
        return $this;
    }

    /**
     * @return float
     */
    public function getReporterLat(): float
    {
        return $this->reporterLat;
    }

    /**
     * @param float $reporterLat
     * @return LocationPatch
     */
    public function setReporterLat(float $reporterLat): LocationPatch
    {
        $this->reporterLat = $reporterLat;
        return $this;
    }

    /**
     * @return float
     */
    public function getReporterLon(): float
    {
        return $this->ReporterLon;
    }

    /**
     * @param float $ReporterLon
     * @return LocationPatch
     */
    public function setReporterLon(float $ReporterLon): LocationPatch
    {
        $this->ReporterLon = $ReporterLon;
        return $this;
    }

    /**
     * @return float
     */
    public function getReporterAccuracy(): float
    {
        return $this->reporterAccuracy;
    }

    /**
     * @param float $reporterAccuracy
     * @return LocationPatch
     */
    public function setReporterAccuracy(float $reporterAccuracy): LocationPatch
    {
        $this->reporterAccuracy = $reporterAccuracy;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return LocationPatch
     */
    public function setUser(User $user): LocationPatch
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return LocationPatch
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return LocationPatch
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function jsonSerialize() : array
    {
        return [
            'created_at' => $this->createdAt->format(\DateTime::ISO8601),
            'updated_at' => $this->updatedAt->format(\DateTime::ISO8601),
            'patched_using_geoloc' => $this->isUsingReporterGeolocation(),
            'equipment_id' => $this->getEquipmentId(),
            'current_location' => [
                'lat' => $this->getCurrentLat(),
                'lon' => $this->getCurrentLon(),
            ],
            'patched_location' => [
                'lat' => $this->getPatchedLat(),
                'lon' => $this->getPatchedLon(),
            ],
            'reported_location' => [
                'location' => [
                    'lat' => $this->getReporterLat(),
                    'lon' => $this->getReporterLon()
                ],
                'accuracy' => $this->getReporterAccuracy()
            ],
        ];
    }
}
