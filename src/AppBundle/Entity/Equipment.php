<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Equipment
 *
 * @ORM\MappedSuperclass
 */
class Equipment implements EquipmentInterface
{
    const TYPE_ELEVATOR = 'elevator';
    const TYPE_STOP_POINT = 'stop_point';

    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string a reference of equipment object
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="source_name", type="string", length=255)
     */
    protected $sourceName;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sourceName
     *
     * @param string $sourceName
     *
     * @return StopPoint
     */
    public function setSourceName(string $sourceName) : Equipment
    {
        $this->sourceName = $sourceName;

        return $this;
    }

    /**
     * Get sourceName
     *
     * @return string
     */
    public function getSourceName() : string
    {
        return $this->sourceName;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Equipment
     */
    public function setCode(string $code): Equipment
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return array the list of available equipmentId types
     */
    public static function getAvailableTypes()
    {
        return [
            self::TYPE_ELEVATOR,
            self::TYPE_STOP_POINT
        ];
    }

    /**
     * Tells whether or not the given equipmentId values are the same as $this.
     * Some properties (such as the creation datetime)
     *
     * @param Equipment $equipment
     * @return bool
     */
    public function equals(self $equipment)
    {
        foreach ($this->getObjectVarsWithoutMetadatas($equipment) as $key => $value) {
            if ($this->$key != $value) {
                return false;
            }
        }
        return true;
    }

    /**
     * Updates the current instance from the given equipmentId
     *
     * @param Equipment $equipment
     * @return $this
     */
    public function updateFrom(self $equipment)
    {
        foreach ($this->getObjectVarsWithoutMetadatas($equipment) as $key => $value) {
            $this->$key = $value;
        }
        $this->updatedAt = new \DateTime('now', (new \DateTimeZone('UTC')));
        return $this;
    }

    /**
     * Slightly modified \get_object_vars() to get rid of the property/value we do not want when comparing
     * two instances, so we are able to tell that two instances of this class are equal even if
     * the meta-datas we add (such as: createdAt, status) differ
     *
     * @param Equipment $equipment
     * @return array
     */
    public function getObjectVarsWithoutMetadatas(self $equipment)
    {
        $propsToExclude = ['createdAt', 'updatedAt', 'status'];
        return array_filter(
            get_object_vars($equipment),
            function ($propName) use ($propsToExclude) {
                return !in_array($propName, $propsToExclude);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Equipment
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
     * @return Equipment
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
}
