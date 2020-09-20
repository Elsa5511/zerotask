<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoadLiftingPointAttachment
 *
 * @ORM\Entity
 * @ORM\Table(name="load_lifting_point_attachment")
 */
class LoadLiftingPointAttachment extends PointAttachment
{
     /**
     * @var LoadLiftingPoint
     *
     * @ORM\ManyToOne(targetEntity="LoadLiftingPoint", inversedBy="loadLiftingPointAttachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="load_lifting_point_id", referencedColumnName="lifting_point_id")
     * })
     */
    private $loadLiftingPoint;

    /**
     * Set load lifting point
     *
     * @param LoadLiftingPoint $loadLiftingPoint
     * @return LoadLiftingPointAttachment
     */
    public function setLoadLiftingPoint(LoadLiftingPoint $loadLiftingPoint = null)
    {
        $this->loadLiftingPoint = $loadLiftingPoint;

        return $this;
    }

    /**
     * Get load lifting point
     *
     * @return LoadLiftingPoint
     */
    public function getLoadLiftingPoint()
    {
        return $this->loadLiftingPoint;
    }

}
