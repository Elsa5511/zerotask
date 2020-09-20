<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoadLashingPointAttachment
 *
 * @ORM\Entity
 * @ORM\Table(name="load_lashing_point_attachment")
 */
class LoadLashingPointAttachment extends PointAttachment
{
     /**
     * @var LoadLashingPoint
     *
     * @ORM\ManyToOne(targetEntity="LoadLashingPoint", inversedBy="loadLashingPointAttachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="load_lashing_point_id", referencedColumnName="lashing_point_id")
     * })
     */
    private $loadLashingPoint;

    /**
     * Set load lashing point
     *
     * @param LoadLashingPoint $loadLashingPoint
     * @return LoadLashingPointAttachment
     */
    public function setLoadLashingPoint(LoadLashingPoint $loadLashingPoint = null)
    {
        $this->loadLashingPoint = $loadLashingPoint;

        return $this;
    }

    /**
     * Get load lashing point
     *
     * @return LoadLashingPoint
     */
    public function getLoadLashingPoint()
    {
        return $this->loadLashingPoint;
    }

}
