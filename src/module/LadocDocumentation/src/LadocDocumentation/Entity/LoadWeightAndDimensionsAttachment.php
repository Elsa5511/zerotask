<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("load_weight_and_dimensions_attachment")
 */
class LoadWeightAndDimensionsAttachment extends PointAttachment {
    /**
     * @var LoadWeightAndDimensions
     *
     * @ORM\ManyToOne(targetEntity="LoadWeightAndDimensions", inversedBy="attachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="load_weight_and_dimensions_id", referencedColumnName="id")
     * })
     */
    protected $loadWeightAndDimensions;

    /**
     * @return LoadWeightAndDimensions
     */
    public function getLoadWeightAndDimensions() {
        return $this->loadWeightAndDimensions;
    }

    /**
     * @param LoadWeightAndDimensions $loadWeightAndDimensions
     */
    public function setLoadWeightAndDimensions(LoadWeightAndDimensions $loadWeightAndDimensions = null) {
        $this->loadWeightAndDimensions = $loadWeightAndDimensions;
    }
}
